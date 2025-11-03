<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class CitizenDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Statistik warga
        $stats = [
            'my_reports' => Report::where('user_id', $user->id)->count(),
            'pending_reports' => Report::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('user_id', $user->id)
                ->where('status', 'in_progress')->count(),
            'resolved_reports' => Report::where('user_id', $user->id)
                ->where('status', 'resolved')->count(),
            'my_complaints' => Complaint::where('user_id', $user->id)->count(),
            'pending_complaints' => Complaint::where('user_id', $user->id)
                ->where('status', 'pending')->count(),
            'investigating_complaints' => Complaint::where('user_id', $user->id)
                ->where('status', 'investigating')->count(),
            'resolved_complaints' => Complaint::where('user_id', $user->id)
                ->where('status', 'resolved')->count(),
        ];

        // Laporan saya
        $myReports = Report::with(['department', 'assignedUser'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        // Keluhan saya
        $myComplaints = Complaint::with(['department', 'assignedUser'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        // Departemen tersedia
        $departments = Department::where('is_active', true)->get();

        // Statistik tambahan untuk dashboard modern
        $pendingCount = $myReports->whereIn('status', ['submitted', 'pending', 'verified'])->count();
        $resolvedCount = $myReports->where('status', 'resolved')->count();

        return view('citizen.modern-dashboard', compact(
            'stats', 
            'myReports', 
            'myComplaints', 
            'departments',
            'pendingCount',
            'resolvedCount'
        ));
    }

    public function createReport()
    {
        $departments = Department::where('is_active', true)->get();
        return view('citizen.reports.create', compact('departments'));
    }

    public function storeReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/reports');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        $workflowService = app(\App\Services\WorkflowService::class);
        $report = $workflowService->submitReport([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'priority' => $request->priority,
            'attachments' => $attachments ?: null,
        ], Auth::user());

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Laporan berhasil dikirim. Nomor tiket: ' . $report->ticket_no);
    }

    public function createComplaint()
    {
        $departments = Department::where('is_active', true)->get();
        return view('citizen.complaints.create', compact('departments'));
    }

    public function storeComplaint(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/complaints');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        // Create complaint
        $complaint = Complaint::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'department_id' => $request->department_id,
            'location' => $request->location,
            'priority' => $request->priority,
            'user_id' => Auth::id(),
            'status' => 'submitted',
            'attachments' => $attachments ?: null,
        ]);

        // Auto-assign complaint to first admin so it directly enters admin queue
        $firstAdmin = \App\Models\User::where('role', 'admin')->orderBy('id')->first();
        if ($firstAdmin) {
            // Save assignment record for audit trail consistency
            \App\Models\Assignment::create([
                'assignable_id' => $complaint->id,
                'assignable_type' => \App\Models\Complaint::class,
                'assigned_to' => $firstAdmin->id,
                'assigned_by' => Auth::id(),
                'notes' => 'Auto-assigned to first admin on submission',
                'assigned_at' => now(),
            ]);
            $complaint->update([
                'assigned_to' => $firstAdmin->id,
            ]);
        }

        return redirect()->route('citizen.dashboard')
            ->with('success', 'Keluhan berhasil dikirim' . ($complaint->ticket_no ? ' • Nomor tiket: ' . $complaint->ticket_no : ''));
    }

    public function myReports()
    {
        $reports = Report::with(['department', 'assignedUser'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('citizen.reports.index', compact('reports'));
    }

    public function myComplaints()
    {
        $complaints = Complaint::with(['department', 'assignedUser'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('citizen.complaints.index', compact('complaints'));
    }

    public function showReport($id)
    {
        $report = Report::with(['department', 'assignedUser'])->findOrFail($id);
        
        // Use policy to check authorization
        $this->authorize('view', $report);

        return view('citizen.reports.show', compact('report'));
    }

    public function showComplaint($id)
    {
        $complaint = Complaint::with(['department', 'assignedUser'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('citizen.complaints.show', compact('complaint'));
    }
}
