<?php

namespace App\Listeners;

use App\Events\ReportStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendStatusChangedNotification implements ShouldQueue
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
    public function handle(ReportStatusChanged $event): void
    {
        $report = $event->report;
        $newStatus = $event->newStatus;

        // Send notification to report owner
        $report->user->notify(new \App\Notifications\ReportStatusChangedNotification($report, $newStatus));

        // Send email notification based on status (with error handling)
        try {
            switch ($newStatus) {
                case 'verified':
                    Mail::to($report->user->email)->send(new \App\Mail\ReportVerifiedMail($report));
                    break;
                case 'rejected':
                    Mail::to($report->user->email)->send(new \App\Mail\ReportRejectedMail($report));
                    break;
                case 'resolved':
                    Mail::to($report->user->email)->send(new \App\Mail\ReportResolvedMail($report));
                    break;
                case 'closed':
                    Mail::to($report->user->email)->send(new \App\Mail\ReportClosedMail($report));
                    break;
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send status change email: ' . $e->getMessage());
        }

        // Notify assigned staff if status affects them
        if ($report->assigned_to && in_array($newStatus, ['in_progress', 'awaiting_info', 'resolved'])) {
            $report->assignedUser->notify(new \App\Notifications\ReportStatusChangedNotification($report, $newStatus));
        }
    }
}
