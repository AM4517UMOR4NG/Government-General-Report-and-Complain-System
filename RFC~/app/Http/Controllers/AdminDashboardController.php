<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $stats = [
            'total_users' => User::count(),
            'total_reports' => Report::count(),
            'total_complaints' => Complaint::count(),
            'total_departments' => Department::count(),
            'pending_reports' => Report::whereIn('status', ['submitted', 'pending'])->count(),
            'pending_complaints' => Complaint::whereIn('status', ['submitted', 'pending'])->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'resolved_complaints' => Complaint::where('status', 'resolved')->count(),
            'in_progress_reports' => Report::whereIn('status', ['in_progress', 'assigned', 'verified'])->count(),
            'today_reports' => Report::whereDate('created_at', today())->count(),
            'pending_assignments' => Report::whereNull('assigned_to')->whereIn('status', ['submitted', 'pending'])->count(),
            'completed_today' => Report::whereDate('resolved_at', today())->count(),
            'sla_breached' => Report::where('sla_due_at', '<', now())->whereNotIn('status', ['resolved', 'closed'])->count(),
            'due_soon' => Report::whereBetween('sla_due_at', [now(), now()->addHours(24)])->whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        // Statistik berdasarkan departemen
        $departmentStats = Department::withCount(['reports', 'complaints', 'users'])
            ->with(['reports' => function($query) {
                $query->whereIn('status', ['submitted', 'pending']);
            }, 'complaints' => function($query) {
                $query->whereIn('status', ['submitted', 'pending']);
            }])
            ->get();

        // Laporan terbaru
        $recentReports = Report::with(['user', 'department', 'assignedUser'])
            ->latest()
            ->limit(10)
            ->get();

        // Keluhan terbaru
        $recentComplaints = Complaint::with(['user', 'department', 'assignedUser'])
            ->latest()
            ->limit(10)
            ->get();

        // Semua departemen
        $departments = Department::all();

        // Statistik bulanan
        $monthlyStats = [
            'reports' => Report::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'complaints' => Complaint::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return view('admin.modern-dashboard', compact(
            'stats', 
            'departmentStats', 
            'recentReports', 
            'recentComplaints', 
            'monthlyStats',
            'departments'
        ));
    }

    public function reports()
    {
        $reports = Report::with(['user', 'department', 'assignedUser'])
            ->latest()
            ->paginate(20);

        // Get all staff for assignment dropdown
        $staffList = User::where('role', 'staff')->get();

        return view('admin.reports', compact('reports', 'staffList'));
    }

    public function complaints()
    {
        $complaints = Complaint::with(['user', 'department', 'assignedUser'])
            ->latest()
            ->paginate(20);

        return view('admin.complaints', compact('complaints'));
    }

    public function users()
    {
        try {
            // Get users with department relationship, paginate 20 per page
            $users = User::with('department')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
            
            // Get all departments for filters
            $departments = Department::all();

            return view('admin.users', compact('users', 'departments'));
        } catch (\Exception $e) {
            \Log::error('Error loading users: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data pengguna. Silakan coba lagi.');
        }
    }

    public function departments()
    {
        $departments = Department::withCount(['users', 'reports', 'complaints'])->get();

        return view('admin.departments', compact('departments'));
    }

    /**
     * System monitoring dashboard
     */
    public function monitoring()
    {
        // Real-time statistics
        $stats = [
            'total_reports' => Report::count(),
            'total_complaints' => Complaint::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in_progress')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'escalated_reports' => Report::where('is_escalated', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        // Department performance
        $departmentPerformance = Department::withCount(['reports', 'complaints', 'users'])
            ->with(['reports' => function($query) {
                $query->whereIn('status', ['resolved', 'closed']);
            }])
            ->get()
            ->map(function($dept) {
                $totalReports = $dept->reports_count;
                $resolvedReports = $dept->reports->count();
                
                return [
                    'name' => $dept->name,
                    'total_reports' => $totalReports,
                    'resolved_reports' => $resolvedReports,
                    'resolution_rate' => $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 2) : 0,
                    'staff_count' => $dept->users_count,
                ];
            });

        // Recent activity
        $recentActivity = collect();
        
        // Recent reports
        $recentReports = Report::with(['user', 'department'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($report) {
                return [
                    'type' => 'report',
                    'title' => $report->title,
                    'user' => $report->user->name,
                    'department' => $report->department->name,
                    'status' => $report->status,
                    'created_at' => $report->created_at,
                ];
            });

        // Recent complaints
        $recentComplaints = Complaint::with(['user', 'department'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($complaint) {
                return [
                    'type' => 'complaint',
                    'title' => $complaint->title,
                    'user' => $complaint->user->name,
                    'department' => $complaint->department->name,
                    'status' => $complaint->status,
                    'created_at' => $complaint->created_at,
                ];
            });

        $recentActivity = $recentReports->merge($recentComplaints)
            ->sortByDesc('created_at')
            ->take(10);

        // SLA monitoring
        $slaStats = [
            'total_with_sla' => Report::whereNotNull('sla_due_at')->count(),
            'sla_breached' => Report::where('is_escalated', true)->count(),
            'sla_due_soon' => Report::where('sla_due_at', '<=', now()->addHours(24))
                ->where('is_escalated', false)
                ->whereNotIn('status', ['resolved', 'closed'])
                ->count(),
        ];

        // Monthly trends
        $monthlyTrends = [
            'reports' => Report::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'complaints' => Complaint::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return view('admin.monitoring', compact(
            'stats',
            'departmentPerformance',
            'recentActivity',
            'slaStats',
            'monthlyTrends'
        ));
    }

    public function confirmReport($id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'confirmed';
        $report->save();
        return redirect()->back()->with('success', 'Laporan berhasil dikonfirmasi.');
    }

    public function assignReport(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $assignedTo = User::findOrFail($request->assigned_to);
        
        // Use WorkflowService for proper assignment
        $workflowService = app(\App\Services\WorkflowService::class);
        $workflowService->assignReport($report, $assignedTo, Auth::user(), $request->notes);
        
        return redirect()->back()->with('success', 'Laporan berhasil ditugaskan ke ' . $assignedTo->name . '.');
    }

    public function editReport($id)
    {
        $report = Report::findOrFail($id);
        $departments = Department::all();
        $staff = User::where('role', 'staff')->get();
        return view('admin.reports_edit', compact('report', 'departments', 'staff'));
    }

    public function updateReport(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->update($request->only([
            'title', 'description', 'category', 'status', 'priority', 'department_id', 'assigned_to', 'location'
        ]));
        return redirect()->route('admin.reports')->with('success', 'Laporan berhasil diperbarui.');
    }

    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->route('admin.reports')->with('success', 'Laporan berhasil dihapus.');
    }

    public function downloadReport($id)
    {
        $report = Report::with(['user','department','assignedUser'])->findOrFail($id);

        // Check if ZipArchive is available
        if (!class_exists('ZipArchive')) {
            return $this->downloadReportAsJson($report);
        }

        try {
            $zip = new \ZipArchive();
            $zipPath = storage_path('app/temp/report_'.$report->id.'_'.time().'.zip');
            if (!is_dir(dirname($zipPath))) {
                mkdir(dirname($zipPath), 0755, true);
            }
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return back()->with('error', 'Gagal membuat arsip unduhan.');
            }

            $metadata = [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'category' => $report->category,
                'status' => $report->status,
                'priority' => $report->priority,
                'department' => optional($report->department)->name,
                'location' => $report->location,
                'created_at' => (string)$report->created_at,
                'updated_at' => (string)$report->updated_at,
                'user' => $report->user ? ['id'=>$report->user->id,'name'=>$report->user->name,'email'=>$report->user->email] : null,
                'assigned_to' => $report->assignedUser ? ['id'=>$report->assignedUser->id,'name'=>$report->assignedUser->name] : null,
            ];
            $zip->addFromString('report.json', json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            if (is_array($report->attachments)) {
                foreach ($report->attachments as $relPath) {
                    $abs = storage_path('app/public/'.$relPath);
                    if (file_exists($abs)) {
                        $zip->addFile($abs, 'attachments/'.basename($relPath));
                    }
                }
            }

            $zip->close();
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Zip creation failed: ' . $e->getMessage());
            return $this->downloadReportAsJson($report);
        }
    }

    /**
     * Download report as JSON (fallback when ZipArchive is not available)
     */
    private function downloadReportAsJson($report)
    {
        $metadata = [
            'id' => $report->id,
            'title' => $report->title,
            'description' => $report->description,
            'category' => $report->category,
            'status' => $report->status,
            'priority' => $report->priority,
            'department' => optional($report->department)->name,
            'location' => $report->location,
            'created_at' => (string)$report->created_at,
            'updated_at' => (string)$report->updated_at,
            'user' => $report->user ? ['id'=>$report->user->id,'name'=>$report->user->name,'email'=>$report->user->email] : null,
            'assigned_to' => $report->assignedUser ? ['id'=>$report->assignedUser->id,'name'=>$report->assignedUser->name] : null,
            'attachments' => $report->attachments ?? [],
        ];

        $filename = 'report_' . $report->ticket_no . '_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($metadata)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    public function sendReportToHead($id)
    {
        $report = Report::findOrFail($id);
        if (!$report->department_id) {
            return back()->with('error', 'Laporan belum memiliki departemen.');
        }
        
        $head = User::where('role','department_head')->where('department_id',$report->department_id)->first();
        if (!$head) {
            return back()->with('error', 'Tidak ditemukan kepala departemen untuk laporan ini.');
        }
        
        // Use WorkflowService for proper assignment
        $workflowService = app(\App\Services\WorkflowService::class);
        $workflowService->assignReport($report, $head, Auth::user(), 'Dikirim ke Kepala Departemen oleh Admin');
        
        return back()->with('success', 'Laporan berhasil dikirim ke Kepala Departemen: ' . $head->name . '.');
    }

    public function confirmComplaint($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->status = 'confirmed';
        $complaint->save();
        return redirect()->back()->with('success', 'Keluhan berhasil dikonfirmasi.');
    }

    public function assignComplaint(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->assigned_to = $request->assigned_to;
        $complaint->status = 'investigating';
        $complaint->save();
        return redirect()->back()->with('success', 'Keluhan berhasil ditugaskan ke staff.');
    }

    public function editComplaint($id)
    {
        $complaint = Complaint::findOrFail($id);
        $departments = Department::all();
        $staff = User::where('role', 'staff')->get();
        return view('admin.complaints_edit', compact('complaint', 'departments', 'staff'));
    }

    public function updateComplaint(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update($request->only([
            'title', 'description', 'category', 'status', 'priority', 'department_id', 'assigned_to', 'location'
        ]));
        return redirect()->route('admin.complaints')->with('success', 'Keluhan berhasil diperbarui.');
    }

    public function deleteComplaint($id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->delete();
        return redirect()->route('admin.complaints')->with('success', 'Keluhan berhasil dihapus.');
    }
}
