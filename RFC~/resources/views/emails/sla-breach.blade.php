<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URGENT: SLA Breach Alert</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .urgent-info { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .ticket-info { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
        .footer { background: #34495e; color: white; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 URGENT: SLA Breach Alert</h1>
        </div>
        
        <div class="content">
            <div class="urgent-info">
                <h2>⚠️ IMMEDIATE ACTION REQUIRED</h2>
                <p>A report has breached its SLA and requires immediate attention.</p>
            </div>
            
            <div class="ticket-info">
                <h3>Report Details</h3>
                <p><strong>Ticket Number:</strong> {{ $report->ticket_no }}</p>
                <p><strong>Title:</strong> {{ $report->title }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($report->priority) }}</p>
                <p><strong>Current Status:</strong> {{ ucfirst(str_replace('_', ' ', $report->status)) }}</p>
                <p><strong>SLA Due:</strong> {{ $report->sla_due_at->format('d M Y, H:i') }}</p>
                <p><strong>Department:</strong> {{ $report->department->name ?? 'N/A' }}</p>
                <p><strong>Assigned To:</strong> {{ $report->assignedUser->name ?? 'Unassigned' }}</p>
            </div>
            
            <p><strong>This report has exceeded its SLA deadline and needs immediate attention.</strong></p>
            
            <p>Please take immediate action to resolve this report or reassign it to appropriate staff.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated alert. Please take immediate action.</p>
        </div>
    </div>
</body>
</html>
