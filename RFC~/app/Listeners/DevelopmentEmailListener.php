<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DevelopmentEmailListener implements ShouldQueue
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
    public function handle($event): void
    {
        // Log email content instead of sending
        $eventName = get_class($event);
        $eventData = $this->extractEventData($event);
        
        Log::info("Email Event: {$eventName}", $eventData);
        
        $this->info("Email would be sent for event: {$eventName}");
    }

    /**
     * Extract relevant data from event
     */
    private function extractEventData($event)
    {
        $data = [];
        
        if (method_exists($event, 'getReport')) {
            $report = $event->getReport();
            $data['report_id'] = $report->id ?? null;
            $data['ticket_no'] = $report->ticket_no ?? null;
            $data['title'] = $report->title ?? null;
        }
        
        if (method_exists($event, 'getUser')) {
            $user = $event->getUser();
            $data['user_id'] = $user->id ?? null;
            $data['user_email'] = $user->email ?? null;
        }
        
        return $data;
    }
}
