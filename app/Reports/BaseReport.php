<?php

namespace App\Reports;

use App\Models\ReportHistory;
use App\Models\Stakeholder;
use App\Notifications\ReportFailedNotification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

abstract class BaseReport
{
    protected $reportSchedule;
    protected $stakeholder;
    protected $format;
    protected $filters;

    abstract public function generate(): array;

    public function __construct($reportSchedule, $stakeholder = null)
    {
        $this->reportSchedule = $reportSchedule;
        $this->stakeholder = $stakeholder;
        $this->format = $stakeholder?->preferred_format ?? 'html';
        $this->filters = $this->getFilters();
    }

    public function send()
    {
        try {
            // Check cache if enabled
            $cacheKey = $this->getCacheKey();
            if ($this->reportSchedule->enable_caching && Cache::has($cacheKey)) {
                $content = Cache::get($cacheKey);
            } else {
                $data = $this->generate();
                $content = $this->formatContent($data);
                
                // Store in cache if enabled
                if ($this->reportSchedule->enable_caching) {
                    Cache::put($cacheKey, $content, now()->addHours($this->reportSchedule->cache_duration));
                }
            }

            // Store report content
            $contentPath = $this->storeReport($content);

            // Send via selected delivery channels
            $this->deliverReport($content);

            // Record history
            $this->recordHistory('sent', $contentPath);

            return true;
        } catch (\Exception $e) {
            $this->handleFailure($e);
            return false;
        }
    }

    protected function formatContent(array $data)
    {
        // Set locale for the stakeholder
        App::setLocale($this->stakeholder->locale ?? config('app.locale'));

        // Format based on preferred format
        switch ($this->format) {
            case 'pdf':
                $html = View::make("reports.templates.{$this->reportSchedule->type}", $data)->render();
                return $this->convertHtmlToPdf($html);
            case 'csv':
                return $this->arrayToCsv($data);
            case 'json':
                return json_encode($this->sanitizeData($data));
            default: // html
                return view("reports.templates.{$this->reportSchedule->type}", $data)->render();
        }
    }

    protected function convertHtmlToPdf($html)
    {
        // Create a temporary HTML file
        $tempFile = tempnam(sys_get_temp_dir(), 'report_');
        file_put_contents($tempFile, $html);

        // Convert to PDF using wkhtmltopdf (must be installed on the system)
        $outputFile = $tempFile . '.pdf';
        exec("wkhtmltopdf {$tempFile} {$outputFile}");

        // Read the PDF content
        $pdfContent = file_get_contents($outputFile);

        // Clean up temporary files
        unlink($tempFile);
        unlink($outputFile);

        return $pdfContent;
    }

    protected function deliverReport($content)
    {
        $deliveryChannels = $this->getDeliveryChannels();

        foreach ($deliveryChannels as $channel) {
            switch ($channel) {
                case 'email':
                    $this->sendViaEmail($content);
                    break;
                case 'api':
                    $this->sendViaApi($content);
                    break;
                case 'webhook':
                    $this->sendViaWebhook($content);
                    break;
                case 'sms':
                    $this->sendViaSms($content);
                    break;
            }
        }
    }

    protected function sendViaEmail($content)
    {
        Mail::to($this->stakeholder->email)
            ->send(new \App\Mail\ReportMail($this->reportSchedule, $content, $this->format));
    }

    protected function sendViaApi($content)
    {
        if (!$this->stakeholder->api_config) {
            return;
        }

        // Implementation depends on your API requirements
        $client = new \GuzzleHttp\Client();
        $client->post($this->stakeholder->api_config['endpoint'], [
            'headers' => $this->stakeholder->api_config['headers'] ?? [],
            'json' => [
                'content' => $content,
                'format' => $this->format,
                'report_type' => $this->reportSchedule->type
            ]
        ]);
    }

    protected function sendViaWebhook($content)
    {
        if (!$this->stakeholder->webhook_url) {
            return;
        }

        $client = new \GuzzleHttp\Client();
        $client->post($this->stakeholder->webhook_url, [
            'json' => [
                'content' => $content,
                'format' => $this->format,
                'report_type' => $this->reportSchedule->type,
                'timestamp' => now()->timestamp
            ]
        ]);
    }

    protected function sendViaSms($content)
    {
        if (!$this->stakeholder->phone) {
            return;
        }

        // Implementation depends on your SMS provider
        // This is just an example using a fictional SMS service
        $smsService = app(\App\Services\SmsService::class);
        $smsService->send(
            $this->stakeholder->phone,
            "Your {$this->reportSchedule->name} report is ready. Access it here: " . route('reports.view', $this->getLatestHistory()->id)
        );
    }

    protected function storeReport($content)
    {
        $filename = Str::random(40) . '.' . $this->format;
        $path = "reports/{$this->reportSchedule->type}/{$filename}";
        
        Storage::disk('private')->put($path, $content);
        
        return $path;
    }

    protected function recordHistory($status, $contentPath = null, $errorMessage = null)
    {
        return ReportHistory::create([
            'report_schedule_id' => $this->reportSchedule->id,
            'stakeholder_id' => $this->stakeholder->id,
            'status' => $status,
            'content_path' => $contentPath,
            'error_message' => $errorMessage,
            'format' => $this->format,
            'delivery_channels' => $this->getDeliveryChannels()
        ]);
    }

    protected function handleFailure(\Exception $e)
    {
        Log::error("Report generation failed", [
            'report' => $this->reportSchedule->name,
            'stakeholder' => $this->stakeholder->name,
            'error' => $e->getMessage()
        ]);

        $this->recordHistory('failed', null, $e->getMessage());

        if ($this->reportSchedule->notify_on_failure) {
            \App\Models\User::admins()->get()->each(function ($admin) use ($e) {
                $admin->notify(new ReportFailedNotification(
                    $this->reportSchedule,
                    $this->stakeholder,
                    $e
                ));
            });
        }
    }

    protected function getDeliveryChannels(): array
    {
        return json_decode($this->stakeholder->delivery_preferences ?? '["email"]', true);
    }

    protected function getFilters(): array
    {
        $pivot = $this->stakeholder?->reportSchedules()
            ->where('report_schedule_id', $this->reportSchedule->id)
            ->first()?->pivot;

        return json_decode($pivot?->filters ?? '{}', true);
    }

    protected function getCacheKey(): string
    {
        return "report:{$this->reportSchedule->id}:{$this->stakeholder->id}:{$this->format}";
    }

    protected function sanitizeData($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitizeData'], $data);
        }
        return is_string($data) ? e($data) : $data;
    }

    protected function arrayToCsv($data)
    {
        $output = fopen('php://temp', 'r+');
        
        // Add headers
        fputcsv($output, array_keys(reset($data)));
        
        // Add rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }

    protected function getLatestHistory()
    {
        return ReportHistory::where([
            'report_schedule_id' => $this->reportSchedule->id,
            'stakeholder_id' => $this->stakeholder->id,
        ])->latest()->first();
    }
} 