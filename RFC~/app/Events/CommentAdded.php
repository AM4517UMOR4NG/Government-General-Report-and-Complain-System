<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('admin.dashboard'),
        ];

        // Get the report/complaint from the comment
        $reportable = $this->comment->commentable;
        
        if ($reportable) {
            $channels[] = new PrivateChannel('user.' . $reportable->user_id);
            
            if ($reportable->assigned_to) {
                $channels[] = new PrivateChannel('user.' . $reportable->assigned_to);
            }
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'comment' => $this->comment->load(['user']),
            'reportable' => $this->comment->commentable,
            'message' => 'New comment added',
        ];
    }
}
