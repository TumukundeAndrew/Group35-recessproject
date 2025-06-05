<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;
use App\Mail\StakeholderReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Add this to use Log

use App\Models\Stakeholder; // Ensure you import the Stakeholder model
class SendStakeholderReports extends Command
{
    // Command signature and description
    protected $signature = 'reports:send-stakeholder';
    protected $description = 'Send daily reports to all stakeholders';

    // Laravel injects the ReportService here
    

public function handle(ReportService $reportService)
{
    // Fetch stakeholders grouped by type
    $stakeholders = Stakeholder::all()->groupBy('type');

    foreach ($stakeholders as $type => $users) {
        $pdf = $reportService->generate($type);
        foreach ($users as $user) {
            Mail::to($user->email)->send(new StakeholderReportMail($pdf, $type));
        }
    }

    $this->info('Reports sent successfully!');
}

}