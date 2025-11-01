<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'files:cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Clean up temporary files older than 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up temporary files...');

        $tempPath = storage_path('app/temp');
        
        if (!is_dir($tempPath)) {
            $this->info('No temp directory found.');
            return Command::SUCCESS;
        }

        $files = glob($tempPath . '/*');
        $deletedCount = 0;
        $cutoffTime = now()->subDay()->timestamp;

        foreach ($files as $file) {
            if (is_file($file)) {
                $fileTime = filemtime($file);
                if ($fileTime < $cutoffTime) {
                    if (unlink($file)) {
                        $deletedCount++;
                        $this->line("Deleted: " . basename($file));
                    }
                }
            }
        }

        $this->info("Cleaned up {$deletedCount} temporary files.");
        
        return Command::SUCCESS;
    }
}
