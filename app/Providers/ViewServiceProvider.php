<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Reminder;
use App\Models\Laboratory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.navbar', function ($view) {
            if (Auth::check() && Auth::user()->role === 'teknisi') {
                $user = Auth::user();
                $today = Carbon::today();

                $labs = Laboratory::where('technician_id', $user->id)->with('pcs', 'reports')->get();

                // Reminder hari ini
                $todayReminders = Reminder::where('user_id', $user->id)
                    ->whereDate('reminder_date', $today)
                    ->with('laboratory')
                    ->get()
                    ->filter(fn($r) => $r->computed_status !== 'completed');

                // Bad report hari ini per lab
                $badReportsToday = $labs->map(function ($lab) use ($today) {
                    $badToday = $lab->reports->filter(function ($report) use ($today) {
                        return $report->created_at->isSameDay($today) && $report->status === 'bad';
                    })->count();

                    return [
                        'lab_name' => $lab->lab_name,
                        'bad_count' => $badToday,
                    ];
                })->filter(fn($r) => $r['bad_count'] > 0)->values();

                $view->with(compact('todayReminders', 'badReportsToday'));
            }
        });
    }
}
