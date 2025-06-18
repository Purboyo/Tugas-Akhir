<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;


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

            // Default kosong
            $todayReminders = collect();

            if ($user->role === 'admin') {
                // Admin hanya melihat reminder hari ini
                $todayReminders = Reminder::whereDate('reminder_date', now())->get();
            } elseif ($user->role === 'teknisi') {
                // Teknisi melihat semua reminder miliknya, tidak peduli tanggal
                $todayReminders = Reminder::where('user_id', $user->id)->get();
            }

            $view->with('todayReminders', $todayReminders);
        }
    });
    }
}
