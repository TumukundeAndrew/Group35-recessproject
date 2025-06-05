<?php

namespace App\Mail;

use App\Models\ReportSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reportSchedule;
    public $content;
    public $format;

    public function __construct(ReportSchedule $reportSchedule, $content, $format)
    {
        $this->reportSchedule = $reportSchedule;
        $this->content = $content;
        $this->format = $format;
    }

    public function build()
    {
        $mail = $this->subject("{$this->reportSchedule->name} Report");

        switch ($this->format) {
            case 'pdf':
                return $mail->view('emails.report')
                    ->attachData($this->content, "{$this->reportSchedule->name}.pdf", [
                        'mime' => 'application/pdf'
                    ]);
            case 'csv':
                return $mail->view('emails.report')
                    ->attachData($this->content, "{$this->reportSchedule->name}.csv", [
                        'mime' => 'text/csv'
                    ]);
            default: // html
                return $mail->html($this->content);
        }
    }
} 