<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportAssignedNotification extends Notification implements ShouldQueue
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
            ->subject('Report Assigned to You - ' . $this->report->ticket_no)
            ->greeting('Hello ' . $notifiable->name)
            ->line('A report has been assigned to you: ' . $this->report->title)
            ->line('Ticket Number: ' . $this->report->ticket_no)
            ->line('Priority: ' . ucfirst($this->report->priority))
            ->line('SLA Due: ' . $this->report->sla_due_at?->format('Y-m-d H:i:s'))
            ->action('View Report', url('/staff/reports/' . $this->report->id))
            ->line('Please start working on this report.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'report_assigned',
            'report_id' => $this->report->id,
            'ticket_no' => $this->report->ticket_no,
            'title' => $this->report->title,
            'priority' => $this->report->priority,
            'sla_due_at' => $this->report->sla_due_at,
            'message' => 'Report assigned to you: ' . $this->report->title,
        ];
    }
}
