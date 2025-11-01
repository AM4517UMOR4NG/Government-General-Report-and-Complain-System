<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Complaint;
use App\Models\User;
use App\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Verify a report
     */
    public function verifyReport(Request $request, $id)
    {
        $request->validate([
            'category' => 'sometimes|string',
            'priority' => 'sometimes|in:low,medium,high,urgent',
            'department_id' => 'sometimes|exists:departments,id',
        ]);

        $report = Report::findOrFail($id);
        $this->workflowService->verifyReport($report, Auth::user(), $request->only([
            'category', 'priority', 'department_id'
        ]));

        return redirect()->back()->with('success', 'Report verified successfully.');
    }

    /**
     * Reject a report
     */
    public function rejectReport(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $this->workflowService->rejectReport($report, Auth::user(), $request->reason);

        return redirect()->back()->with('success', 'Report rejected successfully.');
    }

    /**
     * Assign a report to staff
     */
    public function assignReport(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $assignedTo = User::findOrFail($request->assigned_to);

        $this->workflowService->assignReport($report, $assignedTo, Auth::user(), $request->notes);

        return redirect()->back()->with('success', 'Report assigned successfully.');
    }

    /**
     * Start working on a report
     */
    public function startWork($id)
    {
        $report = Report::findOrFail($id);
        
        // Check if user is assigned to this report
        if ($report->assigned_to !== Auth::id()) {
            abort(403, 'You are not assigned to this report.');
        }

        $this->workflowService->startWork($report, Auth::user());

        return redirect()->back()->with('success', 'Started working on report.');
    }

    /**
     * Add a comment to a report
     */
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            'is_internal' => 'boolean',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $report = Report::findOrFail($id);
        
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/comments');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        $this->workflowService->addComment(
            $report, 
            Auth::user(), 
            $request->content, 
            $request->boolean('is_internal'),
            $attachments
        );

        return redirect()->back()->with('success', 'Comment added successfully.');
    }

    /**
     * Set report to awaiting info
     */
    public function setAwaitingInfo(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $this->workflowService->setAwaitingInfo($report, Auth::user(), $request->reason);

        return redirect()->back()->with('success', 'Report set to awaiting information.');
    }

    /**
     * Resolve a report
     */
    public function resolveReport(Request $request, $id)
    {
        $request->validate([
            'resolution_notes' => 'required|string|max:2000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:5120',
        ]);

        $report = Report::findOrFail($id);
        
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('public/attachments/resolutions');
                $attachments[] = str_replace('public/', '', $path);
            }
        }

        $this->workflowService->resolveReport($report, Auth::user(), $request->resolution_notes, $attachments);

        return redirect()->back()->with('success', 'Report resolved successfully.');
    }

    /**
     * Approve a resolved report
     */
    public function approveReport($id)
    {
        $report = Report::findOrFail($id);
        $this->workflowService->approveReport($report, Auth::user());

        return redirect()->back()->with('success', 'Report approved and closed.');
    }

    /**
     * Request changes to a resolved report
     */
    public function requestChanges(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $this->workflowService->requestChanges($report, Auth::user(), $request->reason);

        return redirect()->back()->with('success', 'Changes requested successfully.');
    }

    /**
     * Reopen a closed report
     */
    public function reopenReport(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        
        try {
            $this->workflowService->reopenReport($report, Auth::user(), $request->reason);
            return redirect()->back()->with('success', 'Report reopened successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reassign a report
     */
    public function reassignReport(Request $request, $id)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'reason' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $newAssignee = User::findOrFail($request->assigned_to);

        $this->workflowService->reassignReport($report, $newAssignee, Auth::user(), $request->reason);

        return redirect()->back()->with('success', 'Report reassigned successfully.');
    }

    /**
     * Get report workflow history
     */
    public function getWorkflowHistory($id)
    {
        $report = Report::findOrFail($id);
        
        $auditLogs = $report->auditLogs()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $comments = $report->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $assignments = $report->assignments()
            ->with(['assignedTo', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'audit_logs' => $auditLogs,
            'comments' => $comments,
            'assignments' => $assignments,
        ]);
    }
}
