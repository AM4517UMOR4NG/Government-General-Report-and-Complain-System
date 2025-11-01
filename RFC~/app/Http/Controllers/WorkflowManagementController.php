<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowManagementController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Admin assigns report to staff
     */
    public function adminAssignToStaff(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $assignedTo = User::findOrFail($request->assigned_to);

        // Verify the assigned user is staff
        if (!$assignedTo->isStaff()) {
            return back()->with('error', 'User yang dipilih bukan staff.');
        }

        // Use WorkflowService for proper assignment
        $this->workflowService->assignReport($report, $assignedTo, Auth::user(), $request->notes);

        return back()->with('success', 'Laporan berhasil ditugaskan ke staff: ' . $assignedTo->name . '.');
    }

    /**
     * Admin assigns report to department head
     */
    public function adminAssignToHead(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        
        if (!$report->department_id) {
            return back()->with('error', 'Laporan belum memiliki departemen.');
        }

        $head = User::where('role', 'department_head')
            ->where('department_id', $report->department_id)
            ->first();

        if (!$head) {
            return back()->with('error', 'Tidak ditemukan kepala departemen untuk laporan ini.');
        }

        // Use WorkflowService for proper assignment
        $this->workflowService->assignReport($report, $head, Auth::user(), 'Dikirim ke Kepala Departemen oleh Admin');

        return back()->with('success', 'Laporan berhasil dikirim ke Kepala Departemen: ' . $head->name . '.');
    }

    /**
     * Staff confirms and forwards to department head
     */
    public function staffConfirmAndForward($id)
    {
        $user = Auth::user();
        $report = Report::where('department_id', $user->department_id)->findOrFail($id);

        // Authorization: assigned staff or department head
        if ($user->role === 'staff' && $report->assigned_to !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengirim laporan ini. Laporan belum ditugaskan kepada Anda.');
        }

        return DB::transaction(function () use ($report, $user) {
            // If still submitted/pending, mark as verified first
            if (in_array($report->status, ['submitted', 'pending'])) {
                $report->update([
                    'status' => 'verified',
                    'last_activity_at' => now(),
                ]);

                // Audit: confirmed by staff
                AuditLog::create([
                    'auditable_type' => Report::class,
                    'auditable_id' => $report->id,
                    'action' => 'confirmed_by_staff',
                    'old_values' => null,
                    'new_values' => ['status' => 'verified'],
                    'user_id' => $user->id,
                    'performed_at' => now()
                ]);
            }

            // Then forward to head
            $head = User::where('role', 'department_head')
                ->where('department_id', $user->department_id)
                ->first();

            if (!$head) {
                return back()->with('error', 'Tidak ditemukan kepala departemen.');
            }

            $this->workflowService->assignReport($report, $head, $user, 'Dikonfirmasi dan diteruskan ke Kepala Departemen');

            return back()->with('success', 'Laporan dikonfirmasi dan dikirim ke Kepala Departemen: ' . $head->name . '.');
        });
    }

    /**
     * Department head reviews and returns to staff
     */
    public function headReviewAndReturn(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'department_head') {
            return back()->with('error', 'Hanya Kepala Departemen yang dapat mengembalikan laporan ke staff.');
        }

        $request->validate([
            'assigned_to' => 'required|integer|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::where('department_id', $user->department_id)->findOrFail($id);
        $assignedTo = User::findOrFail($request->assigned_to);

        // Verify the assigned user is staff in the same department
        if (!$assignedTo->isStaff() || $assignedTo->department_id !== $user->department_id) {
            return back()->with('error', 'User yang dipilih bukan staff di departemen ini.');
        }

        return DB::transaction(function () use ($report, $assignedTo, $user, $request) {
            $this->workflowService->assignReport($report, $assignedTo, $user, $request->notes ?: 'Dikembalikan ke staff untuk tindak lanjut');

            // Update status to reviewed
            $report->update([
                'status' => 'reviewed',
                'last_activity_at' => now(),
            ]);

            // Audit: reviewed by head
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'action' => 'reviewed_by_head',
                'old_values' => null,
                'new_values' => ['status' => 'reviewed', 'assigned_to' => $assignedTo->id],
                'user_id' => $user->id,
                'performed_at' => now()
            ]);

            return back()->with('success', 'Laporan dikembalikan ke staff: ' . $assignedTo->name . ' untuk tindak lanjut.');
        });
    }

    /**
     * Staff confirms completion to admin
     */
    public function staffConfirmToAdmin(Request $request, $id)
    {
        $user = Auth::user();
        $report = Report::where('department_id', $user->department_id)->findOrFail($id);

        if ($user->role !== 'staff' || $report->assigned_to !== $user->id) {
            return back()->with('error', 'Anda tidak berhak mengonfirmasi laporan ini ke admin.');
        }

        $request->validate([
            'completion_notes' => 'required|string|max:1000',
        ]);

        return DB::transaction(function () use ($report, $user, $request) {
            $report->update([
                'assigned_to' => null,
                'status' => 'awaiting_admin_approval',
                'last_activity_at' => now(),
                'completion_notes' => $request->completion_notes,
            ]);

            // Audit: confirmed to admin
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'action' => 'confirmed_to_admin',
                'old_values' => null,
                'new_values' => [
                    'assigned_to' => null, 
                    'status' => 'awaiting_admin_approval',
                    'completion_notes' => $request->completion_notes
                ],
                'user_id' => $user->id,
                'performed_at' => now()
            ]);

            return back()->with('success', 'Laporan telah dikonfirmasi ke admin untuk persetujuan akhir.');
        });
    }

    /**
     * Admin approves and closes report
     */
    public function adminApproveAndClose(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return back()->with('error', 'Hanya admin yang dapat menyetujui dan menutup laporan.');
        }

        $report = Report::findOrFail($id);

        $request->validate([
            'final_notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($report, $user, $request) {
            $report->update([
                'status' => 'resolved',
                'resolved_at' => now(),
                'final_notes' => $request->final_notes,
                'last_activity_at' => now(),
            ]);

            // Audit: approved by admin
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'action' => 'approved_by_admin',
                'old_values' => null,
                'new_values' => [
                    'status' => 'resolved',
                    'resolved_at' => now(),
                    'final_notes' => $request->final_notes
                ],
                'user_id' => $user->id,
                'performed_at' => now()
            ]);

            // Fire event for status change
            event(new \App\Events\ReportStatusChanged($report, 'awaiting_admin_approval', 'resolved', $user));

            return back()->with('success', 'Laporan telah disetujui dan ditutup. User akan mendapat notifikasi.');
        });
    }

    /**
     * Admin rejects report back to staff
     */
    public function adminRejectToStaff(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return back()->with('error', 'Hanya admin yang dapat menolak laporan.');
        }

        $report = Report::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $assignedTo = User::findOrFail($request->assigned_to);

        return DB::transaction(function () use ($report, $assignedTo, $user, $request) {
            $this->workflowService->assignReport($report, $assignedTo, $user, $request->rejection_reason);

            $report->update([
                'status' => 'needs_revision',
                'last_activity_at' => now(),
            ]);

            // Audit: rejected by admin
            AuditLog::create([
                'auditable_type' => Report::class,
                'auditable_id' => $report->id,
                'action' => 'rejected_by_admin',
                'old_values' => null,
                'new_values' => [
                    'status' => 'needs_revision',
                    'assigned_to' => $assignedTo->id,
                    'rejection_reason' => $request->rejection_reason
                ],
                'user_id' => $user->id,
                'performed_at' => now()
            ]);

            return back()->with('success', 'Laporan ditolak dan dikembalikan ke staff: ' . $assignedTo->name . '.');
        });
    }
}
