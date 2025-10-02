<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check SLA breaches every hour
        $schedule->command('sla:check')->hourly();
        
        // Check for spam reports every 6 hours
        $schedule->command('reports:check-spam')->everySixHours();
        
        // Clean up old notifications (older than 30 days)
        $schedule->command('notifications:cleanup')->daily();
        
        // Clean up temporary files every 6 hours
        $schedule->command('files:cleanup')->everySixHours();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
