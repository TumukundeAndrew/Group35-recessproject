<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supply Chain Report</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2d3748; margin-bottom: 20px;">Hello {{ $data['stakeholder']->name }},</h2>

        <p>Please find attached your supply chain report from Sun Seed Supply Chain.</p>
        
        <p>This report includes:</p>
        <ul>
            @foreach($data['stakeholder']->supplyChainReports as $report)
                <li>{{ $report->title }}</li>
            @endforeach
        </ul>

        <p>If you have any questions about this report, please don't hesitate to contact us.</p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #edf2f7;">
            <p style="margin: 0; font-size: 14px; color: #718096;">Best regards,</p>
            <p style="margin: 0; font-size: 14px; color: #718096;">Sun Seed Supply Chain Team</p>
        </div>
    </div>
</body>
</html>
