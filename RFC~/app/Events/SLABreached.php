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

class SLABreached implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;
    public $slaType;

    /**
     * Create a new event instance.
     */
    public function __construct(Report $report, string $slaType = 'response')
    {
        $this->report = $report;
        $this->slaType = $slaType;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.dashboard'),
            new PrivateChannel('department.' . $this->report->department_id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'report' => $this->report->load(['user', 'department', 'assignedUser']),
            'sla_type' => $this->slaType,
            'message' => 'SLA breached for report: ' . $this->report->title,
            'ticket_no' => $this->report->ticket_no,
            'priority' => $this->report->priority,
        ];
    }
}
