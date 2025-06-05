<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StakeholderReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    private $pdf;
    private $filename;

    /**
     * Create a new message instance.
     */
    public function __construct($data, $pdf, $filename)
    {
        $this->data = $data;
        $this->pdf = $pdf;
        $this->filename = $filename;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->data['subject'])
            ->view('emails.stakeholder_report')
            ->attachData($this->pdf, $this->filename, [
                'mime' => 'application/pdf',
            ]);
    }
}
