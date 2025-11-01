<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Checking authorization for report ID 10:\n\n";

// Get admin user
$admin = \App\Models\User::where('email', 'admin@government.gov')->first();
if (!$admin) {
    echo "Admin user not found!\n";
    exit;
}

echo "User: " . $admin->email . "\n";
echo "Role: " . $admin->role . "\n";
echo "Department ID: " . $admin->department_id . "\n\n";

// Get report
$report = \App\Models\Report::find(10);
if (!$report) {
    echo "Report not found!\n";
    exit;
}

echo "Report ID: " . $report->id . "\n";
echo "Report Title: " . $report->title . "\n";
echo "Report Department ID: " . $report->department_id . "\n";
echo "Report User ID: " . $report->user_id . "\n\n";

// Check authorization
auth()->login($admin);

$filePolicy = new \App\Policies\FilePolicy();
$canView = $filePolicy->view($admin, $report);
$canDownload = $filePolicy->download($admin, $report);

echo "Can View: " . ($canView ? 'YES' : 'NO') . "\n";
echo "Can Download: " . ($canDownload ? 'YES' : 'NO') . "\n";

// Check ReportPolicy
$reportPolicy = new \App\Policies\ReportPolicy();
$canViewReport = $reportPolicy->view($admin, $report);

echo "Can View Report (ReportPolicy): " . ($canViewReport ? 'YES' : 'NO') . "\n";

