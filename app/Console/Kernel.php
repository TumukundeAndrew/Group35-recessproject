<?php
namespace App\Console;

use App\Models\ScheduledReport;
use App\Models\ReportLog;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $reports = ScheduledReport::where('frequency', 'daily')->get();
            foreach ($reports as $report) {
                // Generate and send report (implement Mail class separately)
                // Mail::to($report->stakeholder->email)->send(new ReportMail($report));
                ReportLog::create([
                    'scheduled_report_id' => $report->id,
                    'sent_at' => now(),
                    'status' => 'sent',
                ]);
            }
        })->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
