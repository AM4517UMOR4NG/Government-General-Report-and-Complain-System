<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\Complaint;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckSpamReports extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reports:check-spam';

    /**
     * The console command description.
     */
    protected $description = 'Check for potential spam or duplicate reports';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for potential spam reports...');

        // Check for duplicate titles within 24 hours
        $duplicateReports = Report::select('title', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('title')
            ->having('count', '>', 3)
            ->get();

        foreach ($duplicateReports as $duplicate) {
            $this->warn("Potential spam detected: '{$duplicate->title}' appears {$duplicate->count} times in 24 hours");
            
            // Flag reports for admin review
            Report::where('title', $duplicate->title)
                ->where('created_at', '>=', now()->subDay())
                ->update(['status' => 'pending_review']);
        }

        // Check for duplicate complaints
        $duplicateComplaints = Complaint::select('title', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDay())
            ->groupBy('title')
            ->having('count', '>', 3)
            ->get();

        foreach ($duplicateComplaints as $duplicate) {
            $this->warn("Potential spam detected: '{$duplicate->title}' appears {$duplicate->count} times in 24 hours");
            
            // Flag complaints for admin review
            Complaint::where('title', $duplicate->title)
                ->where('created_at', '>=', now()->subDay())
                ->update(['status' => 'pending_review']);
        }

        $totalFlagged = $duplicateReports->count() + $duplicateComplaints->count();
        
        if ($totalFlagged > 0) {
            $this->info("Flagged {$totalFlagged} potential spam items for review.");
        } else {
            $this->info('No potential spam detected.');
        }

        return Command::SUCCESS;
    }
}
