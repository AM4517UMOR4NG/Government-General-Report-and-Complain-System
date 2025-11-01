<?php

namespace App\Listeners;

use App\Events\SLABreached;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendSLABreachNotification implements ShouldQueue
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
    public function handle(SLABreached $event): void
    {
        $report = $event->report;

        // Notify admin users
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\SLABreachNotification($report));
        }

        // Notify department head
        if ($report->department_id) {
            $departmentHead = User::where('role', 'department_head')
                ->where('department_id', $report->department_id)
                ->first();
            
            if ($departmentHead) {
                $departmentHead->notify(new \App\Notifications\SLABreachNotification($report));
            }
        }

        // Notify assigned staff
        if ($report->assigned_to) {
            $report->assignedUser->notify(new \App\Notifications\SLABreachNotification($report));
        }

        // Send email notifications
        foreach ($adminUsers as $admin) {
            Mail::to($admin->email)->send(new \App\Mail\SLABreachMail($report));
        }
    }
}
