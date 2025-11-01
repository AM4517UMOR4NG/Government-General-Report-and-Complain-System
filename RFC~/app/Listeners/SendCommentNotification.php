<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendCommentNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentAdded $event): void
    {
        $comment = $event->comment;
        $reportable = $comment->commentable;

        // Don't notify the comment author
        $excludeUserIds = [$comment->user_id];

        // Notify report owner if not the commenter
        if ($reportable->user_id !== $comment->user_id) {
            $reportable->user->notify(new \App\Notifications\CommentAddedNotification($comment));
        }

        // Notify assigned staff if not the commenter
        if ($reportable->assigned_to && $reportable->assigned_to !== $comment->user_id) {
            $reportable->assignedUser->notify(new \App\Notifications\CommentAddedNotification($comment));
        }

        // Notify admin users for internal comments
        if ($comment->is_internal) {
            $adminUsers = \App\Models\User::where('role', 'admin')
                ->whereNotIn('id', $excludeUserIds)
                ->get();
            
            foreach ($adminUsers as $admin) {
                $admin->notify(new \App\Notifications\InternalCommentAddedNotification($comment));
            }
        }
    }
}
