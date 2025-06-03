<?php

namespace App\Notifications;

use App\Models\ReportSchedule;
use App\Models\Stakeholder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReportFailedNotification extends Notification
{
    use Queueable;

    protected $reportSchedule;
    protected $stakeholder;
    protected $exception;

    public function __construct(ReportSchedule $reportSchedule, Stakeholder $stakeholder, \Exception $exception)
    {
        $this->reportSchedule = $reportSchedule;
        $this->stakeholder = $stakeholder;
        $this->exception = $exception;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->error()
            ->subject('Report Generation Failed')
            ->line("Report generation failed for {$this->reportSchedule->name}")
            ->line("Stakeholder: {$this->stakeholder->name}")
            ->line("Error: {$this->exception->getMessage()}")
            ->action('View Report Schedule', route('admin.reports'))
            ->line('Please check the logs for more details.');
    }
} 