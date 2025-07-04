<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class SendReminderEmails extends Command
{
    protected $signature = 'reminders:send-daily';
    protected $description = 'Mengirim email reminder harian ke teknisi';

    public function handle()
{
    Log::info('Reminder command is running');

    $today = Carbon::today()->format('Y-m-d');

    $reminders = Reminder::with(['user', 'laboratory'])
        ->whereDate('reminder_date', $today)
        ->get();

    if ($reminders->isEmpty()) {
        Log::info('No reminders today');
        $this->info('Tidak ada reminder hari ini.');
        return;
    }

    foreach ($reminders as $reminder) {
        if ($reminder->user && $reminder->user->email) {
            Mail::to($reminder->user->email)->send(new ReminderNotification($reminder));
            Log::info("Email sent to: {$reminder->user->email}");
            $this->info("Email dikirim ke: {$reminder->user->email}");
        } else {
            Log::warning("Reminder tanpa email teknisi: {$reminder->id}");
        }
    }
}
}

