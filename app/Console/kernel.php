<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reminders:send-daily')->everyMinute()->appendOutputTo(storage_path('logs/reminder-log.txt'));
        // $schedule->command('reminders:send-daily')->dailyAt('07:00');
    }

    protected function commands()
    {
        // ✅ Penting: Memuat command secara otomatis
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
