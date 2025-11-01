<?php

namespace App\Events;

use App\Models\Report;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;
    public $assignedTo;
    public $assignedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Report $report, User $assignedTo, User $assignedBy)
    {
        $this->report = $report;
        $this->assignedTo = $assignedTo;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->assignedTo->id),
            new PrivateChannel('admin.dashboard'),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'report' => $this->report->load(['user', 'department']),
            'assigned_to' => $this->assignedTo,
            'assigned_by' => $this->assignedBy,
            'message' => 'Report assigned to you: ' . $this->report->title,
            'ticket_no' => $this->report->ticket_no,
        ];
    }
}
