<?php

namespace App\Services;

use App\Models\Report;
use App\Models\Complaint;
use App\Models\Assignment;
use App\Models\AuditLog;
use App\Models\Comment;
use App\Models\User;
use App\Events\ReportSubmitted;
use App\Events\ReportAssigned;
use App\Events\ReportStatusChanged;
use App\Events\CommentAdded;
use App\Events\SLABreached;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowService
{
    /**
     * Submit a new report
     */
    public function submitReport(array $data, User $user): Report
    {
        return DB::transaction(function () use ($data, $user) {
            $report = Report::create(array_merge($data, [
                'user_id' => $user->id,
                'status' => 'submitted',
            ]));

            // Log the creation
            $this->logAudit($report, 'created', null, $report->toArray());

            // Fire submitted event
            event(new ReportSubmitted($report));

            // Auto-assign to the first admin (requirement: masuk ke admin pertama)
            $firstAdmin = User::where('role', 'admin')->orderBy('id')->first();
            if ($firstAdmin) {
                // Use the same workflow pathway so audit/events are consistent
                $this->assignReport($report, $firstAdmin, $user, 'Auto-assigned to first admin on submission');
            }

            return $report;
        });
    }

    /**
     * Verify a report (admin action)
     */
    public function verifyReport(Report $report, User $admin, array $data = []): Report
    {
        return DB::transaction(function () use ($report, $admin, $data) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update(array_merge([
                'status' => 'verified',
            ], $data));

            // Assign queue number on first verification
            if (empty($report->queue_no)) {
                $report->update([
                    'queue_no' => \App\Models\Report::nextQueueNo(),
                ]);
            }

            // Log the change
            $this->logAudit($report, 'verified', $oldData, $report->toArray(), $admin);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'verified', $admin));

            return $report;
        });
    }

    /**
     * Reject a report (admin action)
     */
    public function rejectReport(Report $report, User $admin, string $reason = null): Report
    {
        return DB::transaction(function () use ($report, $admin, $reason) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'rejected',
                'resolution_notes' => $reason,
            ]);

            // Log the change
            $this->logAudit($report, 'rejected', $oldData, $report->toArray(), $admin);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'rejected', $admin));

            return $report;
        });
    }

    /**
     * Assign a report to staff
     */
    public function assignReport(Report $report, User $assignedTo, User $assignedBy, string $notes = null): Assignment
    {
        return DB::transaction(function () use ($report, $assignedTo, $assignedBy, $notes) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            // Create assignment record
            $assignment = Assignment::create([
                'assignable_id' => $report->id,
                'assignable_type' => Report::class,
                'assigned_to' => $assignedTo->id,
                'assigned_by' => $assignedBy->id,
                'notes' => $notes,
                'assigned_at' => now(),
            ]);

            // Update report
            $report->update([
                'assigned_to' => $assignedTo->id,
                'status' => 'assigned',
                'reassign_count' => $report->reassign_count + 1,
            ]);

            // Log the change
            $this->logAudit($report, 'assigned', $oldData, $report->toArray(), $assignedBy);

            // Fire event
            event(new ReportAssigned($report, $assignedTo, $assignedBy));

            return $assignment;
        });
    }

    /**
     * Start working on a report (staff action)
     */
    public function startWork(Report $report, User $staff): Report
    {
        return DB::transaction(function () use ($report, $staff) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'in_progress',
            ]);

            // Log the change
            $this->logAudit($report, 'started_work', $oldData, $report->toArray(), $staff);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'in_progress', $staff));

            return $report;
        });
    }

    /**
     * Add a comment to a report
     */
    public function addComment(Report $report, User $user, string $content, bool $isInternal = false, array $attachments = []): Comment
    {
        return DB::transaction(function () use ($report, $user, $content, $isInternal, $attachments) {
            $comment = Comment::create([
                'commentable_id' => $report->id,
                'commentable_type' => Report::class,
                'user_id' => $user->id,
                'content' => $content,
                'is_internal' => $isInternal,
                'attachments' => $attachments,
            ]);

            // Fire event
            event(new CommentAdded($comment));

            return $comment;
        });
    }

    /**
     * Set report to awaiting info
     */
    public function setAwaitingInfo(Report $report, User $staff, string $reason = null): Report
    {
        return DB::transaction(function () use ($report, $staff, $reason) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'awaiting_info',
            ]);

            // Add comment if reason provided
            if ($reason) {
                $this->addComment($report, $staff, "Awaiting additional information: {$reason}", true);
            }

            // Log the change
            $this->logAudit($report, 'awaiting_info', $oldData, $report->toArray(), $staff);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'awaiting_info', $staff));

            return $report;
        });
    }

    /**
     * Resolve a report
     */
    public function resolveReport(Report $report, User $staff, string $resolutionNotes = null, array $attachments = []): Report
    {
        return DB::transaction(function () use ($report, $staff, $resolutionNotes, $attachments) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'resolved',
                'resolution_notes' => $resolutionNotes,
                'resolved_at' => now(),
            ]);

            // Add comment with resolution details
            if ($resolutionNotes) {
                $this->addComment($report, $staff, "Report resolved: {$resolutionNotes}", true, $attachments);
            }

            // Complete assignment
            $assignment = $report->assignments()->where('status', 'active')->first();
            if ($assignment) {
                $assignment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }

            // Log the change
            $this->logAudit($report, 'resolved', $oldData, $report->toArray(), $staff);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'resolved', $staff));

            return $report;
        });
    }

    /**
     * Approve a resolved report (head department action)
     */
    public function approveReport(Report $report, User $approver): Report
    {
        return DB::transaction(function () use ($report, $approver) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'closed',
            ]);

            // Log the change
            $this->logAudit($report, 'approved', $oldData, $report->toArray(), $approver);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'closed', $approver));

            return $report;
        });
    }

    /**
     * Request changes to a resolved report
     */
    public function requestChanges(Report $report, User $requester, string $reason): Report
    {
        return DB::transaction(function () use ($report, $requester, $reason) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'in_progress',
            ]);

            // Add comment with change request
            $this->addComment($report, $requester, "Changes requested: {$reason}", true);

            // Log the change
            $this->logAudit($report, 'changes_requested', $oldData, $report->toArray(), $requester);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'in_progress', $requester));

            return $report;
        });
    }

    /**
     * Reopen a closed report
     */
    public function reopenReport(Report $report, User $user, string $reason): Report
    {
        if (!$report->canBeReopened()) {
            throw new \Exception('This report cannot be reopened.');
        }

        return DB::transaction(function () use ($report, $user, $reason) {
            $oldStatus = $report->status;
            $oldData = $report->toArray();

            $report->update([
                'status' => 'in_progress',
                'resolved_at' => null,
            ]);

            // Add comment with reopen reason
            $this->addComment($report, $user, "Report reopened: {$reason}");

            // Log the change
            $this->logAudit($report, 'reopened', $oldData, $report->toArray(), $user);

            // Fire event
            event(new ReportStatusChanged($report, $oldStatus, 'in_progress', $user));

            return $report;
        });
    }

    /**
     * Reassign a report
     */
    public function reassignReport(Report $report, User $newAssignee, User $reassigner, string $reason = null): Assignment
    {
        return DB::transaction(function () use ($report, $newAssignee, $reassigner, $reason) {
            $oldAssignee = $report->assigned_to;
            $oldData = $report->toArray();

            // Complete current assignment
            $currentAssignment = $report->assignments()->where('status', 'active')->first();
            if ($currentAssignment) {
                $currentAssignment->update([
                    'status' => 'reassigned',
                    'completed_at' => now(),
                ]);
            }

            // Create new assignment
            $assignment = Assignment::create([
                'assignable_id' => $report->id,
                'assignable_type' => Report::class,
                'assigned_to' => $newAssignee->id,
                'assigned_by' => $reassigner->id,
                'notes' => $reason,
                'assigned_at' => now(),
            ]);

            // Update report
            $report->update([
                'assigned_to' => $newAssignee->id,
                'reassign_count' => $report->reassign_count + 1,
            ]);

            // Add comment about reassignment
            if ($reason) {
                $this->addComment($report, $reassigner, "Report reassigned: {$reason}", true);
            }

            // Log the change
            $this->logAudit($report, 'reassigned', $oldData, $report->toArray(), $reassigner);

            // Fire event
            event(new ReportAssigned($report, $newAssignee, $reassigner));

            return $assignment;
        });
    }

    /**
     * Log audit trail
     */
    private function logAudit($model, string $event, array $oldValues = null, array $newValues = null, User $user = null)
    {
        AuditLog::create([
            'auditable_id' => $model->id,
            'auditable_type' => get_class($model),
            'user_id' => $user ? $user->id : Auth::id(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
