<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Submitted Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .ticket-info { background: #e8f4fd; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0; }
        .footer { background: #34495e; color: white; padding: 15px; text-align: center; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Report Submitted Successfully</h1>
        </div>
        
        <div class="content">
            <p>Dear {{ $report->user->name }},</p>
            
            <p>Your report has been successfully submitted to our system. We will review it and take appropriate action.</p>
            
            <div class="ticket-info">
                <h3>Report Details</h3>
                <p><strong>Ticket Number:</strong> {{ $report->ticket_no }}</p>
                <p><strong>Title:</strong> {{ $report->title }}</p>
                <p><strong>Category:</strong> {{ $report->category }}</p>
                <p><strong>Priority:</strong> {{ ucfirst($report->priority) }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $report->status)) }}</p>
                <p><strong>Submitted:</strong> {{ $report->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            <p>You can track the progress of your report using the ticket number above.</p>
            
            <p>Thank you for your report. We will keep you updated on its progress.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
