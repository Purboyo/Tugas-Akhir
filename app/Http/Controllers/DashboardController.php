<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;
use App\Models\Report;
use App\Models\Maintenance;
use App\Models\LabReport;
use App\Models\User;
use App\Models\Laboratory;
use App\Models\PC;
use Carbon\Carbon;

class DashboardController extends Controller
{

public function admin()
{
    $users = User::count();
    $labs = Laboratory::count();
    $technicians = User::where('role', 'teknisi')->count();

    $reminders = Reminder::with('historyMaintenance')->get();

    $reminderCompleted = $reminders->filter(function ($reminder) {
        return $reminder->computed_status === 'completed';
    })->count();

    $reminderPending = $reminders->filter(function ($reminder) {
        return $reminder->computed_status === 'pending';
    })->count();

    return view('admin.dashboard', compact(
        'users', 'reminderCompleted', 'reminderPending', 'labs', 'technicians'
    ));
}


public function teknisi()
{
    $user = Auth::user();
    $today = Carbon::today();

    // Ambil lab yang dimiliki teknisi ini
    $labs = Laboratory::where('technician_id', $user->id)
        ->with(['pcs', 'reports', 'reminders']) // pastikan relasi reminders ada di model
        ->get()
        ->map(function ($lab) use ($today) {
            // Hitung total PC
            $lab->total_pcs = $lab->pcs->count();

            // Filter report hari ini
            $todayReports = $lab->reports->filter(function ($report) use ($today) {
                return $report->created_at->isSameDay($today);
            });

            // Hitung jumlah report good/bad
            $lab->good_reports = $todayReports->where('status', 'Good')->count();
            $lab->bad_reports = $todayReports->where('status', 'Bad')->count();

            return $lab;
        });

    // Ambil semua reminder dari lab yang dimiliki teknisi
    $reminders = $labs->flatMap(function ($lab) {
        return $lab->reminders;
    })->filter(function ($reminder) {
        return $reminder->computed_status !== 'completed';
    });

    // Data untuk chart
    $chartLabels = $labs->pluck('lab_name');
    $chartGood = $labs->pluck('good_reports');
    $chartBad = $labs->pluck('bad_reports');
    $chartPCs = $labs->pluck('total_pcs');

    return view('teknisi.dashboard', compact(
        'labs', 'reminders',
        'chartLabels', 'chartGood', 'chartBad', 'chartPCs'
    ));
}

public function kepalaLab()
{
    // Ambil semua data lab + pcs
    $labs = Laboratory::with('pcs')->get();

    // Ambil semua laporan
    $labReports = LabReport::with(['pc.lab', 'technician'])->latest()->get();

    // Hitung jumlah laporan
    $totalReports = $labReports->count();
    $pendingReports = $labReports->where('status', 'Pending')->count();
    $sendReports = $labReports->where('status', 'Send')->count();

    // Data untuk chart
    $chartData = [
        'Pending' => $pendingReports,
        'Send' => $sendReports,
    ];

    // Group berdasarkan lab
    $labReportsGrouped = $labReports->groupBy(fn($report) => $report->pc->lab->id ?? 'unknown');

    return view('kepala_lab.dashboard', compact(
        'labs',
        'labReports',
        'labReportsGrouped',
        'totalReports',
        'pendingReports',
        'sendReports',
        'chartData'
    ));
}

public function jurusan()
{
    $labs = Laboratory::all();
    $labReports = LabReport::with(['pc.lab', 'technician'])
        ->where('status', 'Send')
        ->latest()
        ->get();

    return view('jurusan.dashboard', [
        'totalSend' => $labReports->count(),
        'totalLab' => $labs->count(),
    ]);
}

}
