<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportSchedule->name }} Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #dee2e6;
        }
        .content {
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            font-size: 0.9em;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $reportSchedule->name }}</h2>
        <p>Generated on: {{ now()->format('F j, Y H:i:s') }}</p>
    </div>

    <div class="content">
        @if($format === 'html')
            {!! $content !!}
        @else
            <p>Your report is attached to this email in {{ strtoupper($format) }} format.</p>
            <p>Please find the attached file named "{{ $reportSchedule->name }}.{{ $format }}".</p>
        @endif
    </div>

    <div class="footer">
        <p>This is an automated report from your Supply Chain Management System.</p>
        <p>If you have any questions or issues, please contact your system administrator.</p>
    </div>
</body>
</html>
