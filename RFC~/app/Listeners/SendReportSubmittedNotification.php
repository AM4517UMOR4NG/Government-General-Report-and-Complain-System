<?php

namespace App\Listeners;

use App\Events\ReportSubmitted;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendReportSubmittedNotification implements ShouldQueue
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
    public function handle(ReportSubmitted $event): void
    {
        $report = $event->report;

        // Send notification to admin users
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\ReportSubmittedNotification($report));
        }

        // Send notification to department head if assigned
        if ($report->department_id) {
            $departmentHead = User::where('role', 'department_head')
                ->where('department_id', $report->department_id)
                ->first();
            
            if ($departmentHead) {
                $departmentHead->notify(new \App\Notifications\ReportSubmittedNotification($report));
            }
        }

        // Skip email in development environment
        if (!app()->environment('local', 'development')) {
            try {
                Mail::to($report->user->email)->send(new \App\Mail\ReportSubmittedMail($report));
            } catch (\Exception $e) {
                \Log::warning('Failed to send email notification: ' . $e->getMessage());
            }
        }
    }
}
