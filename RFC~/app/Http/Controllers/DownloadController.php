<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DownloadController extends Controller
{
    /**
     * Download report as PDF (alternative to ZIP)
     */
    public function downloadReportAsPdf($id)
    {
        $report = Report::with(['user', 'department', 'assignedUser'])->findOrFail($id);
        
        // Check permissions
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $data = [
            'report' => $report,
            'title' => 'Report Details - ' . $report->ticket_no,
        ];

        // Generate HTML content
        $html = view('admin.reports.pdf', $data)->render();

        // For now, return as HTML (you can add PDF generation later)
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="report_' . $report->ticket_no . '.html"');
    }

    /**
     * Download report as CSV
     */
    public function downloadReportAsCsv($id)
    {
        $report = Report::with(['user', 'department', 'assignedUser'])->findOrFail($id);
        
        // Check permissions
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $csvData = [
            ['Field', 'Value'],
            ['Ticket No', $report->ticket_no],
            ['Title', $report->title],
            ['Description', $report->description],
            ['Category', $report->category],
            ['Status', $report->status],
            ['Priority', $report->priority],
            ['Department', $report->department->name ?? 'N/A'],
            ['Assigned To', $report->assignedUser->name ?? 'Unassigned'],
            ['Created By', $report->user->name],
            ['Created At', $report->created_at->format('Y-m-d H:i:s')],
            ['Updated At', $report->updated_at->format('Y-m-d H:i:s')],
            ['Location', $report->location ?? 'N/A'],
        ];

        $filename = 'report_' . $report->ticket_no . '_' . date('Y-m-d_H-i-s') . '.csv';
        
        $csv = $this->arrayToCsv($csvData);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Download report attachments individually
     */
    public function downloadReportAttachments($id)
    {
        $report = Report::findOrFail($id);
        
        // Check permissions
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $attachments = $report->attachments ?? [];
        
        if (empty($attachments)) {
            return redirect()->back()->with('error', 'No attachments to download.');
        }

        // Create a simple HTML page with download links
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Download Report Attachments - ' . $report->ticket_no . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .file-item { margin: 10px 0; padding: 10px; border: 1px solid #ddd; }
        .download-btn { background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Report Attachments - ' . $report->ticket_no . '</h1>
    <h2>' . $report->title . '</h2>
    <p>Click the links below to download individual files:</p>';

        foreach ($attachments as $file) {
            $filename = basename($file);
            $downloadUrl = route('files.download', ['report', $report->id, $filename]);
            $html .= '<div class="file-item">
                <strong>' . $filename . '</strong><br>
                <a href="' . $downloadUrl . '" class="download-btn">Download</a>
            </div>';
        }

        $html .= '</body></html>';

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="attachments_' . $report->ticket_no . '.html"');
    }

    /**
     * Convert array to CSV
     */
    private function arrayToCsv($data)
    {
        $output = fopen('php://temp', 'r+');
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}
