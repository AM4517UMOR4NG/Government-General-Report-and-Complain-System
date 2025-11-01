<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\Complaint;
use App\Events\SLABreached;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSLA extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sla:check';

    /**
     * The console command description.
     */
    protected $description = 'Check for SLA breaches and escalate reports/complaints';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking SLA breaches...');

        // Check reports with SLA breaches
        $breachedReports = Report::where('sla_due_at', '<', now())
            ->where('is_escalated', false)
            ->whereNotIn('status', ['closed', 'resolved'])
            ->get();

        foreach ($breachedReports as $report) {
            $this->escalateReport($report);
        }

        // Check complaints with SLA breaches
        $breachedComplaints = Complaint::where('sla_due_at', '<', now())
            ->where('is_escalated', false)
            ->whereNotIn('status', ['closed', 'resolved'])
            ->get();

        foreach ($breachedComplaints as $complaint) {
            $this->escalateComplaint($complaint);
        }

        $totalBreached = $breachedReports->count() + $breachedComplaints->count();
        
        if ($totalBreached > 0) {
            $this->info("Escalated {$totalBreached} items due to SLA breach.");
            Log::info("SLA Check: Escalated {$totalBreached} items", [
                'reports' => $breachedReports->count(),
                'complaints' => $breachedComplaints->count(),
            ]);
        } else {
            $this->info('No SLA breaches found.');
        }

        return Command::SUCCESS;
    }

    private function escalateReport(Report $report)
    {
        $report->update([
            'is_escalated' => true,
            'status' => 'escalated',
        ]);

        // Fire SLA breach event
        event(new SLABreached($report));

        $this->line("Escalated report: {$report->ticket_no}");
    }

    private function escalateComplaint(Complaint $complaint)
    {
        $complaint->update([
            'is_escalated' => true,
            'status' => 'escalated',
        ]);

        // Fire SLA breach event
        event(new SLABreached($complaint));

        $this->line("Escalated complaint: {$complaint->ticket_no}");
    }
}
