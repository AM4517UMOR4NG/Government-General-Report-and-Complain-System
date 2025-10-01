<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use App\Models\Complaint;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

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
            'pending_reports' => Report::where('status', 'pending')->count(),
            'pending_complaints' => Complaint::where('status', 'pending')->count(),
            'resolved_reports' => Report::where('status', 'resolved')->count(),
            'resolved_complaints' => Complaint::where('status', 'resolved')->count(),
        ];

        // Statistik berdasarkan departemen
        $departmentStats = Department::withCount(['reports', 'complaints', 'users'])
            ->with(['reports' => function($query) {
                $query->where('status', 'pending');
            }, 'complaints' => function($query) {
                $query->where('status', 'pending');
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

        return view('admin.dashboard', compact(
            'stats', 
            'departmentStats', 
            'recentReports', 
            'recentComplaints', 
            'monthlyStats'
        ));
    }

    public function reports()
    {
        $reports = Report::with(['user', 'department', 'assignedUser'])
            ->latest()
            ->paginate(20);

        return view('admin.reports', compact('reports'));
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
        $users = User::with('department')->paginate(20);
        $departments = Department::all();

        return view('admin.users', compact('users', 'departments'));
    }

    public function departments()
    {
        $departments = Department::withCount(['users', 'reports', 'complaints'])->get();

        return view('admin.departments', compact('departments'));
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
        $report = Report::findOrFail($id);
        $report->assigned_to = $request->assigned_to;
        $report->status = 'in_progress';
        $report->save();
        return redirect()->back()->with('success', 'Laporan berhasil ditugaskan ke staff.');
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
