<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPhpExtensions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'php:check-extensions';

    /**
     * The console command description.
     */
    protected $description = 'Check required PHP extensions for the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking PHP extensions...');
        $this->line('');

        $requiredExtensions = [
            'zip' => 'Required for ZIP file creation',
            'mbstring' => 'Required for string handling',
            'openssl' => 'Required for encryption',
            'pdo' => 'Required for database',
            'curl' => 'Required for HTTP requests',
            'json' => 'Required for JSON handling',
            'fileinfo' => 'Required for file type detection',
        ];

        $missingExtensions = [];
        $availableExtensions = [];

        foreach ($requiredExtensions as $extension => $description) {
            if (extension_loaded($extension)) {
                $availableExtensions[] = $extension;
                $this->line("✅ {$extension}: Available - {$description}");
            } else {
                $missingExtensions[] = $extension;
                $this->line("❌ {$extension}: Missing - {$description}");
            }
        }

        $this->line('');

        if (empty($missingExtensions)) {
            $this->info('🎉 All required extensions are available!');
        } else {
            $this->error('⚠️  Missing extensions: ' . implode(', ', $missingExtensions));
            $this->line('');
            $this->line('To install missing extensions:');
            $this->line('');
            
            foreach ($missingExtensions as $extension) {
                switch ($extension) {
                    case 'zip':
                        $this->line('For ZIP extension:');
                        $this->line('  Ubuntu/Debian: sudo apt-get install php-zip');
                        $this->line('  CentOS/RHEL: sudo yum install php-zip');
                        $this->line('  Windows: Uncomment extension=zip in php.ini');
                        $this->line('  XAMPP: Enable in php.ini');
                        break;
                    case 'mbstring':
                        $this->line('For MBSTRING extension:');
                        $this->line('  Ubuntu/Debian: sudo apt-get install php-mbstring');
                        $this->line('  CentOS/RHEL: sudo yum install php-mbstring');
                        break;
                }
            }
            
            $this->line('');
            $this->line('After installing, restart your web server.');
        }

        $this->line('');
        $this->line('Current PHP version: ' . PHP_VERSION);
        $this->line('PHP configuration file: ' . php_ini_loaded_file());

        return Command::SUCCESS;
    }
}
