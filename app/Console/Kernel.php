<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\GenerateReports::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Scheduled report generation
        $schedule->command('reports:generate')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/reports.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}




// <!-- <?php
// namespace App\Console;

// use App\Models\ScheduledReport;
// use App\Models\ReportLog;
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Console\Scheduling\Schedule;
// use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// class Kernel extends ConsoleKernel
// {
    // protected function schedule(Schedule $schedule)
//     {
//         $schedule->call(function () {
//             $reports = ScheduledReport::where('frequency', 'daily')->get();
//             foreach ($reports as $report) {
//                 // Generate and send report (implement Mail class separately)
//                 // Mail::to($report->stakeholder->email)->send(new ReportMail($report));
//                 ReportLog::create([
//                     'scheduled_report_id' => $report->id,
//                     'sent_at' => now(),
//                     'status' => 'sent',
//                 ]);
//             }
//         })->daily();
//     }

//     protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
// {
//     $schedule->command('reports:send-stakeholder')->dailyAt('08:00');
// }


//     protected function commands()
//     {
//         $this->load(__DIR__.'/Commands');
//         require base_path('routes/console.php');
//     }
// } -->
