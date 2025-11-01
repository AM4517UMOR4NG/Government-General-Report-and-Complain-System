<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .section { margin: 20px 0; }
        .field { margin: 10px 0; }
        .label { font-weight: bold; color: #333; }
        .value { color: #666; }
        .attachments { margin-top: 20px; }
        .file-item { background: #f8f9fa; padding: 10px; margin: 5px 0; border-radius: 3px; }
        .status-badge { padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .status-submitted { background: #ffc107; color: #000; }
        .status-verified { background: #17a2b8; color: #fff; }
        .status-in-progress { background: #007bff; color: #fff; }
        .status-resolved { background: #28a745; color: #fff; }
        .status-closed { background: #6c757d; color: #fff; }
        .priority-badge { padding: 4px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .priority-urgent { background: #dc3545; color: #fff; }
        .priority-high { background: #fd7e14; color: #fff; }
        .priority-medium { background: #ffc107; color: #000; }
        .priority-low { background: #6c757d; color: #fff; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ now()->format('d M Y, H:i:s') }}</p>
    </div>

    <div class="section">
        <h2>Report Information</h2>
        
        <div class="field">
            <span class="label">Ticket Number:</span>
            <span class="value">{{ $report->ticket_no }}</span>
        </div>
        
        <div class="field">
            <span class="label">Title:</span>
            <span class="value">{{ $report->title }}</span>
        </div>
        
        <div class="field">
            <span class="label">Description:</span>
            <div class="value">{{ $report->description }}</div>
        </div>
        
        <div class="field">
            <span class="label">Category:</span>
            <span class="value">{{ $report->category }}</span>
        </div>
        
        <div class="field">
            <span class="label">Status:</span>
            <span class="status-badge status-{{ $report->status }}">{{ ucfirst(str_replace('_', ' ', $report->status)) }}</span>
        </div>
        
        <div class="field">
            <span class="label">Priority:</span>
            <span class="priority-badge priority-{{ $report->priority }}">{{ ucfirst($report->priority) }}</span>
        </div>
        
        <div class="field">
            <span class="label">Department:</span>
            <span class="value">{{ $report->department->name ?? 'N/A' }}</span>
        </div>
        
        <div class="field">
            <span class="label">Assigned To:</span>
            <span class="value">{{ $report->assignedUser->name ?? 'Unassigned' }}</span>
        </div>
        
        <div class="field">
            <span class="label">Location:</span>
            <span class="value">{{ $report->location ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="section">
        <h2>User Information</h2>
        
        <div class="field">
            <span class="label">Created By:</span>
            <span class="value">{{ $report->user->name }} ({{ $report->user->email }})</span>
        </div>
        
        <div class="field">
            <span class="label">Created At:</span>
            <span class="value">{{ $report->created_at->format('d M Y, H:i:s') }}</span>
        </div>
        
        <div class="field">
            <span class="label">Last Updated:</span>
            <span class="value">{{ $report->updated_at->format('d M Y, H:i:s') }}</span>
        </div>
    </div>

    @if($report->attachments && count($report->attachments) > 0)
    <div class="section attachments">
        <h2>Attachments</h2>
        <p>This report has {{ count($report->attachments) }} attachment(s):</p>
        
        @foreach($report->attachments as $file)
        <div class="file-item">
            <strong>{{ basename($file) }}</strong>
        </div>
        @endforeach
    </div>
    @endif

    @if($report->resolution_notes)
    <div class="section">
        <h2>Resolution Notes</h2>
        <div class="value">{{ $report->resolution_notes }}</div>
    </div>
    @endif

    <div class="section">
        <h2>System Information</h2>
        <div class="field">
            <span class="label">Report ID:</span>
            <span class="value">{{ $report->id }}</span>
        </div>
        
        <div class="field">
            <span class="label">SLA Due:</span>
            <span class="value">{{ $report->sla_due_at ? $report->sla_due_at->format('d M Y, H:i:s') : 'N/A' }}</span>
        </div>
        
        <div class="field">
            <span class="label">Is Escalated:</span>
            <span class="value">{{ $report->is_escalated ? 'Yes' : 'No' }}</span>
        </div>
        
        <div class="field">
            <span class="label">Reassign Count:</span>
            <span class="value">{{ $report->reassign_count }}</span>
        </div>
    </div>
</body>
</html>
