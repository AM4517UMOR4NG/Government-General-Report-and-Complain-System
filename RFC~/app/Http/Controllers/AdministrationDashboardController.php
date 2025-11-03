<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdministrationDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $department = $user->department;

        // Statistik departemen
        if ($user->role === 'department_head') {
            $stats = [
                'total_reports' => Report::where('department_id', $department->id)->count(),
                'pending_reports' => Report::where('department_id', $department->id)
                    ->whereIn('status', ['submitted', 'pending'])->count(),
                'in_progress_reports' => Report::where('department_id', $department->id)
                    ->where('status', 'in_progress')->count(),
                'resolved_reports' => Report::where('department_id', $department->id)
                    ->where('status', 'resolved')->count(),
                'total_complaints' => Complaint::where('department_id', $department->id)->count(),
                'pending_complaints' => Complaint::where('department_id', $department->id)
                    ->whereIn('status', ['submitted', 'pending'])->count(),
                'investigating_complaints' => Complaint::where('department_id', $department->id)
                    ->where('status', 'investigating')->count(),
                'resolved_complaints' => Complaint::where('department_id', $department->id)
                    ->where('status', 'resolved')->count(),
            ];
        } else {
            // Statistik untuk staff - hanya laporan yang ditugaskan kepada mereka
            $stats = [
                'total_reports' => Report::where('assigned_to', $user->id)->count(),
                'pending_reports' => Report::where('assigned_to', $user->id)
                    ->whereIn('status', ['submitted', 'pending'])->count(),
                'in_progress_reports' => Report::where('assigned_to', $user->id)
                    ->where('status', 'in_progress')->count(),
                'resolved_reports' => Report::where('assigned_to', $user->id)
                    ->where('status', 'resolved')->count(),
                'total_complaints' => Complaint::where('assigned_to', $user->id)->count(),
                'pending_complaints' => Complaint::where('assigned_to', $user->id)
                    ->whereIn('status', ['submitted', 'pending'])->count(),
                'investigating_complaints' => Complaint::where('assigned_to', $user->id)
                    ->where('status', 'investigating')->count(),
                'resolved_complaints' => Complaint::where('assigned_to', $user->id)
                    ->where('status', 'resolved')->count(),
            ];
        }

        // Laporan departemen
        if ($user->role === 'department_head') {
            $departmentReports = Report::with(['user', 'assignedUser'])
                ->where('department_id', $department->id)
                ->latest()
                ->limit(10)
                ->get();
        } else {
            // Staff melihat laporan yang ditugaskan kepada mereka + laporan baru
            $departmentReports = Report::with(['user', 'assignedUser'])
                ->where('department_id', $department->id)
                ->where(function($query) use ($user) {
                    $query->where('assigned_to', $user->id)
                          ->orWhereNull('assigned_to')
                          ->orWhereIn('status', ['submitted', 'pending']);
                })
                ->latest()
                ->limit(10)
                ->get();
        }

        // Keluhan departemen
        $departmentComplaints = Complaint::with(['user', 'assignedUser'])
            ->where('department_id', $department->id)
            ->latest()
            ->limit(10)
            ->get();

        // Staff departemen
        $departmentStaff = User::where('department_id', $department->id)
            ->where('role', '!=', 'citizen')
            ->get();

        // Tambahan statistik untuk dashboard modern
        $stats['today_reports'] = Report::where('department_id', $department->id)
            ->whereDate('created_at', today())->count();
        $stats['completed_today'] = Report::where('department_id', $department->id)
            ->whereDate('resolved_at', today())->count();
        $stats['pending_action'] = Report::where('department_id', $department->id)
            ->whereIn('status', ['submitted', 'pending', 'verified'])
            ->count();

        return view('administration.modern-dashboard', compact(
            'stats', 
            'department', 
            'departmentReports', 
            'departmentComplaints', 
            'departmentStaff'
        ));
    }

    public function reports()
    {
        $user = Auth::user();
        
        // Department Head melihat semua laporan di departemen
        if ($user->role === 'department_head') {
            $reports = Report::with(['user', 'assignedUser'])
                ->where('department_id', $user->department_id)
                ->latest()
                ->paginate(20);
        } else {
            // Staff melihat semua laporan masyarakat di departemen mereka (termasuk yang ditugaskan dan yang belum)
            $reports = Report::with(['user', 'assignedUser'])
                ->where('department_id', $user->department_id)
                ->latest()
                ->paginate(20);
        }

        // Ambil daftar staff untuk assignment dropdown
        $staffList = User::where('department_id', $user->department_id)
            ->where('role', 'staff')
            ->where('id', '!=', $user->id)
            ->get();

        return view('administration.reports', compact('reports', 'staffList'));
    }


    public function complaints()
    {
        $user = Auth::user();
        $complaints = Complaint::with(['user', 'assignedUser'])
            ->where('department_id', $user->department_id)
            ->latest()
            ->paginate(20);

        return view('administration.complaints', compact('complaints'));
    }

    public function staff()
    {
        $user = Auth::user();
        $staff = User::where('department_id', $user->department_id)
            ->where('role', '!=', 'citizen')
            ->paginate(20);

        return view('administration.staff', compact('staff'));
    }

    public function assignReport(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil ditugaskan');
    }

    public function assignComplaint(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'investigating'
        ]);

        return redirect()->back()->with('success', 'Keluhan berhasil ditugaskan');
    }

    public function downloadReport($id)
    {
        $user = Auth::user();
        $report = Report::with(['user','department','assignedUser'])
            ->where('department_id', $user->department_id)
            ->findOrFail($id);

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
    }

    public function confirmReport($id)
    {
        $user = Auth::user();
        $report = Report::where('department_id', $user->department_id)->findOrFail($id);

        // Hanya Kepala Departemen atau Staff terkait yang boleh konfirmasi
        if (!in_array($user->role, ['department_head', 'staff'])) {
            abort(403);
        }
        if ($user->role === 'staff' && $report->assigned_to !== $user->id) {
            abort(403);
        }

        // Gunakan WorkflowService agar: set status, generate queue_no (jika belum), dan trigger event/notification
        $workflow = app(\App\Services\WorkflowService::class);
        $workflow->verifyReport($report, $user);

        // Catat audit tambahan (opsional, karena WorkflowService juga melog)
        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'action' => 'confirmed',
            'old_values' => null,
            'new_values' => ['status' => 'verified', 'queue_no' => $report->queue_no],
            'user_id' => $user->id,
            'performed_at' => now()
        ]);

        return back()->with('success', 'Laporan berhasil dikonfirmasi. Nomor antrian: '.($report->queue_no ?? '-'));
    }

    public function sendReportToHead($id)
    {
        $user = Auth::user();
        $report = Report::where('department_id', $user->department_id)->findOrFail($id);
        
        // Pastikan laporan sudah dikonfirmasi terlebih dahulu
        if (!in_array($report->status, ['verified', 'in_progress'])) {
            return back()->with('error', 'Laporan harus dikonfirmasi terlebih dahulu sebelum diteruskan ke Kepala Departemen.');
        }

        // Hanya staff yang ditugaskan ATAU kepala departemen yang boleh meneruskan
        if ($user->role === 'staff' && $report->assigned_to !== $user->id) {
            return back()->with('error', 'Anda tidak berhak meneruskan laporan ini. Laporan belum ditugaskan kepada Anda.');
        }
        
        $head = User::where('role','department_head')->where('department_id',$user->department_id)->first();
        if (!$head) {
            return back()->with('error', 'Tidak ditemukan kepala departemen.');
        }
        
        $report->update([
            'assigned_to' => $head->id,
            'status' => 'assigned',
            'last_activity_at' => now()
        ]);

        // Log audit
        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'action' => 'forwarded_to_head',
            'old_values' => null,
            'new_values' => ['assigned_to' => $head->id, 'status' => 'assigned'],
            'user_id' => $user->id,
            'performed_at' => now()
        ]);

        return back()->with('success', 'Laporan berhasil diteruskan ke Kepala Departemen.');
    }

    /**
     * Confirm (if needed) and forward the report to the head in one step.
     */
    public function confirmAndSend($id)
    {
        $user = Auth::user();
        $report = Report::where('department_id', $user->department_id)->findOrFail($id);

        // Authorization: assigned staff or department head
        if ($user->role === 'staff' && $report->assigned_to !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengirim laporan ini. Laporan belum ditugaskan kepada Anda.');
        }

        // If still submitted/pending, mark as verified first
        if (in_array($report->status, ['submitted', 'pending'])) {
            $report->update([
                'status' => 'verified',
                'last_activity_at' => now(),
            ]);

            // Audit: confirmed by staff/head
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'action' => 'confirmed',
                'old_values' => null,
                'new_values' => ['status' => 'verified'],
                'user_id' => $user->id,
                'performed_at' => now()
            ]);
        }

        // Then forward to head
        $head = User::where('role','department_head')->where('department_id',$user->department_id)->first();
        if (!$head) {
            return back()->with('error', 'Tidak ditemukan kepala departemen.');
        }

        $report->update([
            'assigned_to' => $head->id,
            'status' => 'assigned',
            'last_activity_at' => now(),
        ]);

        // Audit: forwarded to head
        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'action' => 'forwarded_to_head',
            'old_values' => null,
            'new_values' => ['assigned_to' => $head->id, 'status' => 'assigned'],
            'user_id' => $user->id,
            'performed_at' => now()
        ]);

        return back()->with('success', 'Laporan dikonfirmasi dan dikirim ke Kepala Departemen.');
    }

    /**
     * Head returns a report to a selected staff after review.
     */
    public function returnToStaff(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'department_head') {
            return back()->with('error', 'Hanya Kepala Departemen yang dapat mengembalikan laporan ke staff.');
        }

        $request->validate(['assigned_to' => 'required|integer|exists:users,id']);

        $report = Report::where('department_id', $user->department_id)->findOrFail($id);

        $report->update([
            'assigned_to' => $request->assigned_to,
            'status' => 'reviewed',
            'last_activity_at' => now(),
        ]);

        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'action' => 'returned_to_staff',
            'old_values' => null,
            'new_values' => ['assigned_to' => $request->assigned_to, 'status' => 'reviewed'],
            'user_id' => $user->id,
            'performed_at' => now()
        ]);

        return back()->with('success', 'Laporan dikembalikan ke staff untuk tindak lanjut.');
    }

    /**
     * Staff confirms back to admin after completing actions.
     */
    public function confirmToAdmin($id)
    {
        $user = Auth::user();
        $report = Report::where('department_id', $user->department_id)->findOrFail($id);
        if ($user->role !== 'staff' || $report->assigned_to !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengonfirmasi laporan ini ke admin.');
        }

        $report->update([
            'assigned_to' => null,
            'status' => 'awaiting_admin',
            'last_activity_at' => now(),
        ]);

        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'action' => 'confirmed_to_admin',
            'old_values' => null,
            'new_values' => ['assigned_to' => null, 'status' => 'awaiting_admin'],
            'user_id' => $user->id,
            'performed_at' => now()
        ]);

        return back()->with('success', 'Laporan telah dikonfirmasi ke admin.');
    }

    /**
     * Update report status (for staff and department head)
     */
    public function updateReport(Request $request, $id)
    {
        $user = Auth::user();
        $report = Report::findOrFail($id);

        // Authorization check
        if ($user->role === 'staff') {
            // Staff can only update reports assigned to them
            if ($report->assigned_to !== $user->id) {
                abort(403, 'Unauthorized');
            }
        } elseif ($user->role === 'department_head') {
            // Department head can update reports in their department
            if ($report->department_id !== $user->department_id) {
                abort(403, 'Unauthorized');
            }
        } else {
            abort(403, 'Unauthorized');
        }

        // Update status
        $oldStatus = $report->status;
        $report->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes ?? $report->resolution_notes,
            'last_activity_at' => now(),
        ]);

        // Mark as resolved if status is resolved
        if ($request->status === 'resolved' && !$report->resolved_at) {
            $report->update(['resolved_at' => now()]);
        }

        // Create audit log
        AuditLog::create([
            'auditable_type' => Report::class,
            'auditable_id' => $report->id,
            'action' => 'status_updated',
            'old_values' => ['status' => $oldStatus],
            'new_values' => ['status' => $request->status],
            'user_id' => $user->id,
            'performed_at' => now()
        ]);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'report' => $report]);
        }

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
