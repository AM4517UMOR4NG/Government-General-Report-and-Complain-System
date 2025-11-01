<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
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
        return (new MailMessage)
            ->subject('New Report Submitted - ' . $this->report->ticket_no)
            ->greeting('Hello ' . $notifiable->name)
            ->line('A new report has been submitted: ' . $this->report->title)
            ->line('Ticket Number: ' . $this->report->ticket_no)
            ->line('Priority: ' . ucfirst($this->report->priority))
            ->line('Category: ' . $this->report->category)
            ->action('View Report', url('/admin/reports/' . $this->report->id))
            ->line('Please review and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'report_submitted',
            'report_id' => $this->report->id,
            'ticket_no' => $this->report->ticket_no,
            'title' => $this->report->title,
            'priority' => $this->report->priority,
            'category' => $this->report->category,
            'user_name' => $this->report->user->name,
            'message' => 'New report submitted: ' . $this->report->title,
        ];
    }
}
