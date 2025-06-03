<?php

namespace App\Mail;

use App\Models\Report;
use App\Models\Stakeholder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ReportToStakeholders extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $stakeholder;

    public function __construct(Report $report)
    {
        $this->report = $report;
        $this->stakeholder = $report->stakeholder;
    }

    public function build()
    {
        return $this->subject('Supply Chain Report - ' . config('app.name'))
                    ->markdown('emails.reports.stakeholder')
                    ->attach(Storage::path('public/' . $this->report->file_path), [
                        'as' => 'supply_chain_report.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
