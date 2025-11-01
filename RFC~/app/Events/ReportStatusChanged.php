<?php

namespace App\Events;

use App\Models\Report;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;
    public $oldStatus;
    public $newStatus;
    public $changedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Report $report, string $oldStatus, string $newStatus, $changedBy = null)
    {
        $this->report = $report;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('user.' . $this->report->user_id),
            new PrivateChannel('admin.dashboard'),
        ];

        if ($this->report->assigned_to) {
            $channels[] = new PrivateChannel('user.' . $this->report->assigned_to);
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'report' => $this->report->load(['user', 'department', 'assignedUser']),
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy,
            'message' => 'Report status changed from ' . $this->oldStatus . ' to ' . $this->newStatus,
            'ticket_no' => $this->report->ticket_no,
        ];
    }
}
