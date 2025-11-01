<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupDevelopmentEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:setup-dev';

    /**
     * The console command description.
     */
    protected $description = 'Setup email configuration for development environment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Setting up email configuration for development...');

        // Update .env file
        $envPath = base_path('.env');
        
        if (!File::exists($envPath)) {
            $this->error('.env file not found. Please create it first.');
            return Command::FAILURE;
        }

        $envContent = File::get($envPath);
        
        // Update mail configuration
        $mailConfig = [
            'MAIL_MAILER=log',
            'MAIL_HOST=127.0.0.1',
            'MAIL_PORT=2525',
            'MAIL_USERNAME=null',
            'MAIL_PASSWORD=null',
            'MAIL_ENCRYPTION=null',
            'MAIL_FROM_ADDRESS="noreply@government-frc.local"',
            'MAIL_FROM_NAME="${APP_NAME}"'
        ];

        foreach ($mailConfig as $config) {
            $key = explode('=', $config)[0];
            
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", $config, $envContent);
            } else {
                $envContent .= "\n" . $config;
            }
        }

        File::put($envPath, $envContent);

        $this->info('Email configuration updated successfully!');
        $this->line('');
        $this->line('Current email settings:');
        $this->line('- MAIL_MAILER: log (emails will be logged to storage/logs)');
        $this->line('- MAIL_FROM_ADDRESS: noreply@government-frc.local');
        $this->line('- MAIL_FROM_NAME: Government FRC System');
        $this->line('');
        $this->info('You can now submit reports without email errors.');
        $this->line('Check storage/logs/laravel.log to see email content.');

        return Command::SUCCESS;
    }
}
