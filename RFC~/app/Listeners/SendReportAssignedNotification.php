<?php

namespace App\Listeners;

use App\Events\ReportAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReportAssignedNotification implements ShouldQueue
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
    public function handle(ReportAssigned $event): void
    {
        $report = $event->report;
        $assignedTo = $event->assignedTo;

        // Send notification to assigned user
        $assignedTo->notify(new \App\Notifications\ReportAssignedNotification($report));

        // Send email notification (with error handling)
        try {
            Mail::to($assignedTo->email)->send(new \App\Mail\ReportAssignedMail($report, $assignedTo));
        } catch (\Exception $e) {
            \Log::warning('Failed to send assignment email: ' . $e->getMessage());
        }

        // Send notification to original user
        $report->user->notify(new \App\Notifications\ReportAssignedToStaffNotification($report, $assignedTo));
    }
}
