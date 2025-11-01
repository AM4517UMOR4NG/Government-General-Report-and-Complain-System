<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Assigned to You</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #e74c3c; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .ticket-info { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .sla-warning { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .footer { background: #34495e; color: white; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Report Assigned to You</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $assignedTo->name }},</p>
            
            <p>A report has been assigned to you for handling. Please review and take appropriate action.</p>
            
            <div class="ticket-info">
                <h3>Report Details</h3>
                <p><strong>Ticket Number:</strong> {{ $report->ticket_no }}</p>
                <p><strong>Title:</strong> {{ $report->title }}</p>
                <p><strong>Category:</strong> {{ $report->category }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($report->priority) }}</p>
                <p><strong>Department:</strong> {{ $report->department->name ?? 'N/A' }}</p>
                <p><strong>Location:</strong> {{ $report->location ?? 'N/A' }}</p>
            </div>
            
            @if($report->sla_due_at)
            <div class="sla-warning">
                <h3>⚠️ SLA Information</h3>
                <p><strong>SLA Due:</strong> {{ $report->sla_due_at->format('d M Y, H:i') }}</p>
                <p>Please ensure you complete this report before the SLA deadline.</p>
            </div>
            @endif
            
            <p>Please log in to the system to view the full report details and start working on it.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
