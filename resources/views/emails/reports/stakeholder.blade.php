@component('mail::message')
# Supply Chain Report

Dear {{ $stakeholder->name }},

A new supply chain report has been generated for you. Please find it attached to this email.

@if($report->message)
**Message from Admin:**
{{ $report->message }}
@endif

@if($report->include_analytics)
This report includes detailed analytics about your supply chain activities.
@endif

@if($report->include_summary)
A comprehensive summary of your operations is included in this report.
@endif

You can also download this report from your dashboard at any time.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
