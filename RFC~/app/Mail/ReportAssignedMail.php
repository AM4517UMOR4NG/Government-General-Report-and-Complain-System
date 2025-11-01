<?php

namespace App\Mail;

use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportAssignedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $report;
    public $assignedTo;

    /**
     * Create a new message instance.
     */
    public function __construct(Report $report, User $assignedTo)
    {
        $this->report = $report;
        $this->assignedTo = $assignedTo;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Report Assigned to You - ' . $this->report->ticket_no,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.report-assigned',
            with: [
                'report' => $this->report,
                'assignedTo' => $this->assignedTo,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
