<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class AdministrationDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $department = $user->department;

        // Statistik departemen
        $stats = [
            'total_reports' => Report::where('department_id', $department->id)->count(),
            'pending_reports' => Report::where('department_id', $department->id)
                ->where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('department_id', $department->id)
                ->where('status', 'in_progress')->count(),
            'resolved_reports' => Report::where('department_id', $department->id)
                ->where('status', 'resolved')->count(),
            'total_complaints' => Complaint::where('department_id', $department->id)->count(),
            'pending_complaints' => Complaint::where('department_id', $department->id)
                ->where('status', 'pending')->count(),
            'investigating_complaints' => Complaint::where('department_id', $department->id)
                ->where('status', 'investigating')->count(),
            'resolved_complaints' => Complaint::where('department_id', $department->id)
                ->where('status', 'resolved')->count(),
        ];

        // Laporan departemen
        $departmentReports = Report::with(['user', 'assignedUser'])
            ->where('department_id', $department->id)
            ->latest()
            ->limit(10)
            ->get();

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

        return view('administration.dashboard', compact(
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
        $reports = Report::with(['user', 'assignedUser'])
            ->where('department_id', $user->department_id)
            ->latest()
            ->paginate(20);

        return view('administration.reports', compact('reports'));
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
}
