<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Report;
use App\Models\User;

class ReportAssignedToStaffNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Report $report;
    protected User $staff;

    public function __construct(Report $report, User $staff)
    {
        $this->report = $report;
        $this->staff = $staff;
    }

    public function via(object $notifiable): array
    {
        // Send both email and database notification
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $report = $this->report;
        $staff = $this->staff;

        $subject = 'Laporan Anda Telah Ditugaskan ke Staff';
        $line1 = "Laporan dengan tiket {$report->ticket_no} telah ditugaskan ke staff: {$staff->name}.";
        $line2 = $report->queue_no ? "Nomor Antrian: {$report->queue_no}." : null;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo,')
            ->line($line1);

        if ($line2) {
            $mail->line($line2);
        }

        $mail->action('Lihat Laporan', url('/citizen/reports/' . $report->id))
             ->line('Terima kasih telah menggunakan layanan kami.');

        return $mail;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'ticket_no' => $this->report->ticket_no,
            'queue_no' => $this->report->queue_no,
            'assigned_staff_id' => $this->staff->id,
            'assigned_staff_name' => $this->staff->name,
            'message' => 'Laporan ditugaskan ke staff',
        ];
    }
}
