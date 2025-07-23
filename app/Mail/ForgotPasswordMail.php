<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;

    public function __construct($resetLink)
    {
        $this->resetLink = $resetLink;
    }

    public function build()
    {
        return $this->subject('Reset Password - LabSI')
                    ->view('emails.forgot-password');
    }
}
