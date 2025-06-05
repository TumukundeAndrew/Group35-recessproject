<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Models\Stakeholder;
use Illuminate\Support\Facades\Log;

class GenerateReports extends Command
{
    protected $signature = 'reports:generate';
    protected $description = 'Generate and send scheduled reports to stakeholders';

    public function handle()
    {
        $now = now();
        
        try {
            // Get reports scheduled for this time
            $reports = Report::where('is_active', true)
                ->where('frequency', $this->getCurrentFrequency())
                ->where('scheduled_time', $now->format('H:i'))
                ->get();
                
            foreach ($reports as $report) {
                $this->processReport($report);
            }
            
            $this->info('Reports generated and sent successfully.');
            
        } catch (\Exception $e) {
            Log::error('Error generating reports: ' . $e->getMessage());
            $this->error('Error generating reports. Check logs for details.');
        }
    }
    
    protected function processReport(Report $report)
    {
        $reportClass = config("reports.reports.{$report->type}");
        
        if (!$reportClass || !class_exists($reportClass)) {
            Log::warning("Report type {$report->type} not found or invalid.");
            return;
        }
        
        $reportInstance = new $reportClass;
        
        foreach ($report->stakeholders as $stakeholder) {
            try {
                $content = $reportInstance->generate($stakeholder);
                $reportInstance->send($stakeholder, $content);
                
                Log::info("Report {$report->name} sent to stakeholder {$stakeholder->name}");
                
            } catch (\Exception $e) {
                Log::error("Error sending report to stakeholder {$stakeholder->name}: " . $e->getMessage());
            }
        }
    }
    
    protected function getCurrentFrequency()
    {
        $now = now();
        
        if ($now->day === 1) {
            return 'monthly';
        }
        
        if ($now->dayOfWeek === 1) { // Monday
            return 'weekly';
        }
        
        return 'daily';
    }
} 