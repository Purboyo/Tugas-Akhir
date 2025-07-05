<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;
use App\Models\LabReport;
use Illuminate\Support\Facades\Log;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
View::composer('*', function ($view) {
    if (Auth::check()) {
        $user = Auth::user();
        $view->with('role', $user->role);

        // 📅 Reminder hari ini
        $todayReminders = Reminder::whereDate('reminder_date', now())->get();

        // 🛠️ Bad Reports hari ini
$badReportsRaw = LabReport::with('pc.lab')
    ->whereRaw('LOWER(status) = ?', ['bad'])
    ->whereDate('created_at', now())
    ->get();

Log::info('📦 Bad reports found:', $badReportsRaw->toArray());

$badReportsByLab = $badReportsRaw
    ->groupBy(fn($report) => optional($report->pc->lab)->lab_name ?? 'Unknown')
    ->map->count();

Log::info('🔔 Notifikasi: Jumlah Bad Report Hari Ini', $badReportsByLab->toArray());


        $view->with('todayReminders', $todayReminders);
        $view->with('badReportsByLab', $badReportsByLab);
    }
});
}
}
