<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Reminder;

class ReminderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    public function build()
    {
        return $this->subject('Reminder Maintenance: ' . $this->reminder->title)
                    ->view('emails.reminder');
    }
}
