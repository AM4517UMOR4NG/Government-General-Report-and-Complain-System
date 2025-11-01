<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;
    protected $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, string $newStatus)
    {
        $this->report = $report;
        $this->newStatus = $newStatus;
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
        $statusMessages = [
            'verified' => 'Your report has been verified and is being processed.',
            'rejected' => 'Your report has been rejected. Please review the feedback.',
            'in_progress' => 'Your report is now being worked on.',
            'resolved' => 'Your report has been resolved.',
            'closed' => 'Your report has been closed.',
            'escalated' => 'Your report has been escalated due to SLA breach.',
        ];

        $message = $statusMessages[$this->newStatus] ?? 'Your report status has been updated.';

        return (new MailMessage)
            ->subject('Report Status Update - ' . $this->report->ticket_no)
            ->greeting('Hello ' . $notifiable->name)
            ->line($message)
            ->line('Ticket Number: ' . $this->report->ticket_no)
            ->line('New Status: ' . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->action('View Report', url('/reports/' . $this->report->id))
            ->line('Thank you for using our service.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'status_changed',
            'report_id' => $this->report->id,
            'ticket_no' => $this->report->ticket_no,
            'title' => $this->report->title,
            'old_status' => $this->report->status,
            'new_status' => $this->newStatus,
            'message' => 'Report status changed to: ' . ucfirst(str_replace('_', ' ', $this->newStatus)),
        ];
    }
}
