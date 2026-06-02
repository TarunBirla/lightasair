<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class RequestLeadMail extends Mailable
{
    public $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    public function build()
    {
        return $this->subject('New Equipment Request')
                    ->view('emails.request-lead');
    }
}