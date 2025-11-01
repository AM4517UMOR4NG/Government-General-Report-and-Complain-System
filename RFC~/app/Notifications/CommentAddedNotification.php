<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        // Only use database notifications in development
        if (app()->environment('local', 'development')) {
            return ['database'];
        }
        
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reportable = $this->comment->commentable;
        $ticketNo = $reportable->ticket_no ?? 'N/A';

        return (new MailMessage)
            ->subject('New Comment Added - ' . $ticketNo)
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new comment has been added to your report.')
            ->line('Ticket Number: ' . $ticketNo)
            ->line('Comment by: ' . $this->comment->user->name)
            ->line('Comment: ' . substr($this->comment->content, 0, 100) . '...')
            ->action('View Report', url('/reports/' . $reportable->id))
            ->line('Please review the comment.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $reportable = $this->comment->commentable;
        
        return [
            'type' => 'comment_added',
            'comment_id' => $this->comment->id,
            'reportable_id' => $reportable->id,
            'ticket_no' => $reportable->ticket_no ?? 'N/A',
            'commenter_name' => $this->comment->user->name,
            'comment_preview' => substr($this->comment->content, 0, 100),
            'message' => 'New comment added by ' . $this->comment->user->name,
        ];
    }
}
