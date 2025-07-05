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
        $user = auth::user();
        $today = Carbon::today();

        $labs = Laboratory::where('technician_id', $user->id)
            ->with('pcs', 'reports') // relasi reports harus ada (lihat model)
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

        // Jadwal maintenance
        $reminders = Reminder::with('laboratory')
            ->where('user_id', $user->id)
            ->get()
            ->filter(function ($reminder) {
                return $reminder->computed_status !== 'completed';
            });


        // Data untuk chart
        $chartLabels = $labs->pluck('lab_name');
        $chartGood = $labs->pluck('good_reports');
        $chartBad = $labs->pluck('bad_reports');

        $chartPCs = $labs->pluck('total_pcs');
        return view('teknisi.dashboard', compact('labs', 'reminders', 'chartLabels', 'chartGood', 'chartBad', 'chartPCs'));
    }

public function kepalaLab()
{
    // Ambil semua data lab + pcs
    $labs = Laboratory::with('pcs')->get();

    // Ambil semua laporan, dengan relasi PC dan Lab
    $labReports = LabReport::with(['pc.lab', 'technician'])
        ->latest()
        ->get();

    // Hitung jumlah laporan
    $totalReports = $labReports->count();

    // Filter berdasarkan status
    $pendingReports = $labReports->where('status', 'Pending')->count();
    $resolvedReports = $labReports->where('status', 'Resolved')->count();

    // Siapkan data untuk chart
    $chartData = [
        'Pending' => $pendingReports,
        'Resolved' => $resolvedReports,
    ];

    // Grouping berdasarkan Lab
    $labReportsGrouped = $labReports->groupBy(fn($report) => $report->pc->lab->id ?? 'unknown');

    return view('kepala_lab.dashboard', compact(
        'labs',
        'labReports',
        'labReportsGrouped',
        'totalReports',
        'pendingReports',
        'resolvedReports',
        'chartData'
    ));
}

public function jurusan()
{
    $totalReviewed = LabReport::where('status', 'reviewed')->count();
    $totalResolved = LabReport::where('status', 'resolved')->count();
    $totalLab = Laboratory::count();

    $latestReports = LabReport::with(['pc.lab', 'technician'])
        ->whereIn('status', ['reviewed', 'resolved'])
        ->latest()
        ->take(5)
        ->get();

    return view('jurusan.dashboard', compact(
        'totalReviewed', 'totalResolved', 'totalLab', 'latestReports'
    ));
}
}
