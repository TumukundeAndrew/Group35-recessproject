<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Supply Chain Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .stakeholder-info {
            margin-bottom: 20px;
        }
        .report-content {
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #333;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-title">Supply Chain Report</div>
        <div>Generated on {{ date('F d, Y') }}</div>
    </div>

    <div class="stakeholder-info">
        <h2>Stakeholder Information</h2>
        <table>
            <tr>
                <th>Name</th>
                <td>{{ $stakeholder->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $stakeholder->email }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ ucfirst($stakeholder->type) }}</td>
            </tr>
        </table>
    </div>

    <div class="report-content">
        <h2>{{ $report['title'] }}</h2>
        <div>{!! nl2br(e($report['content'])) !!}</div>
    </div>

    <div class="footer">
        <p>This report is confidential and intended only for {{ $stakeholder->name }}.</p>
        <p>© {{ date('Y') }} Sun Seed Supply Chain. All rights reserved.</p>
    </div>
</body>
</html> 