<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\HistoryMaintenance;
use App\Models\HistoryReportPC;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class HistoryController extends Controller
{


public function historymaintenancepc(Request $request)
{
    $user = Auth::user();

    // Query dasar
    $dataQuery = HistoryMaintenance::with([
        'pc',
        'maintenance.reminder.laboratory',
        'maintenance.reminder.user'
    ]);

    // Filter teknisi jika role = teknisi
    if ($user->role === 'teknisi') {
        $dataQuery->whereHas('maintenance.reminder.laboratory', function ($q) use ($user) {
            $q->where('technician_id', $user->id);
        });
    }


    // Filter optional maintenance_id
    if ($request->filled('maintenance_id')) {
        $dataQuery->where('maintenance_id', $request->maintenance_id);
    }

    // Ambil semua data
    $allHistories = $dataQuery->latest()->get();

    // Semua lab untuk dropdown
    $availableLabs = $allHistories
        ->pluck('maintenance.reminder.laboratory.lab_name')
        ->filter()
        ->unique()
        ->sort()
        ->values();

    $globalLab = $request->lab;

    // Lab yang ditampilkan di bagian bawah (jika difilter)
    $filteredLabs = $availableLabs;
    if ($globalLab) {
        $filteredLabs = $availableLabs->filter(fn($lab) => $lab === $globalLab)->values();
    }

    // Tanggal-tanggal per lab untuk filter tanggal
    $availableDatesPerLab = $availableLabs->mapWithKeys(function ($labName) use ($allHistories) {
        $dates = $allHistories->filter(fn($item) =>
            optional($item->maintenance->reminder->laboratory)->lab_name === $labName
        )->pluck('created_at')
         ->map(fn($d) => $d->format('Y-m-d'))
         ->unique()->sort()->values();

        return [$labName => $dates];
    });

    // Group dan pagination per lab
    $groupedByLab = $filteredLabs->mapWithKeys(function ($labName) use ($allHistories, $request) {
        $slug = Str::slug($labName);

        $labData = $allHistories->filter(fn($item) =>
            optional($item->maintenance->reminder->laboratory)->lab_name === $labName
        );

        // Filter tanggal per lab
        $dateKey = "date_$slug";
        if ($request->filled($dateKey)) {
            $labData = $labData->filter(fn($item) =>
                $item->created_at->toDateString() === $request->input($dateKey)
            );
        }

        // Pagination
        $pageKey = "page_$slug";
        $currentPage = $request->input($pageKey, 1);
        $perPage = 10;

        $paginated = new LengthAwarePaginator(
            $labData->forPage($currentPage, $perPage)->values(),
            $labData->count(),
            $perPage,
            $currentPage,
            ['pageName' => $pageKey, 'path' => request()->url(), 'query' => request()->query()]
        );

        return [$labName => $paginated];
    });

    // Chart global
    $chartData = $allHistories->groupBy('status')->map->count()->toArray();

    // Hilangkan 'Pending'
    unset($chartData['Pending']);

    return view('share.maintenance.history', [
        'chartData' => $chartData,
        'availableLabs' => $availableLabs,
        'groupedByLab' => $groupedByLab,
        'availableDatesPerLab' => $availableDatesPerLab,
        'selectedId' => $request->input('maintenance_id'),
        'role' => $user->role,
    ]);
}

public function exportPdf(Request $request)
{
    $labs = $request->input('labs', []);
    $dates = $request->input('dates', []);

    $data = HistoryMaintenance::with([
        'pc',
        'maintenance.reminder.laboratory.technician',
        'maintenance.reminder.user'
    ])
    ->whereHas('maintenance.reminder.laboratory', function ($q) use ($labs) {
        $q->whereIn('lab_name', $labs);
    })
    ->whereIn(DB::raw('DATE(created_at)'), $dates)
    ->get()
    ->groupBy(fn($item) => $item->maintenance->reminder->laboratory->lab_name ?? 'Unknown');

    $pdf = Pdf::loadView('share.maintenance.export', compact('data', 'labs', 'dates'))
        ->setPaper('a4', 'landscape');

    return $pdf->stream('maintenance_history.pdf');
}

public function historyReportPC(Request $request)
{
    $user = Auth::user();
    $selectedLab = $request->lab;

    // Ambil semua data history (per teknisi jika teknisi, semua jika bukan)
    $query = HistoryReportPC::with(['pc.lab', 'technician'])->latest();

    if ($user->role === 'teknisi') {
        $query->where('technician_id', $user->id);
    }

    $allHistories = $query->get();

    // Ambil daftar lab yang tersedia dari relasi PC
    $availableLabs = $allHistories
        ->pluck('pc.lab.lab_name')
        ->filter()
        ->unique()
        ->sort()
        ->values();

    // Filter berdasarkan lab jika dipilih
    if ($selectedLab) {
        $allHistories = $allHistories->filter(function ($history) use ($selectedLab) {
            return optional($history->pc->lab)->lab_name === $selectedLab;
        });
    }

    // Grouping per lab
    $groupedByLab = $allHistories->groupBy(fn ($item) => optional($item->pc->lab)->lab_name ?? 'Unknown');
    $availableDatesPerLab = [];

    foreach ($groupedByLab as $labName => $items) {
        $labSlug = Str::slug($labName);
        $dateFilter = $request->input("date_$labSlug");

        // Ambil daftar tanggal tersedia per lab
        $availableDatesPerLab[$labName] = $items->pluck('created_at')->map(fn($d) => $d->toDateString())->unique()->values();

        // Filter tanggal jika dipilih
        if ($dateFilter) {
            $items = $items->filter(fn($item) => $item->created_at->toDateString() === $dateFilter);
        }

        // Pagination per lab
        $currentPage = $request->input("page_lab_$labSlug", 1);
        $perPage = 10;
        $pagedItems = new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage),
            $items->count(),
            $perPage,
            $currentPage,
            ['pageName' => "page_lab_$labSlug"]
        );

        $groupedByLab[$labName] = $pagedItems;
    }

    // Chart global
    $chartData = [
        'Good' => $allHistories->where('status', 'Good')->count(),
        'Bad'  => $allHistories->where('status', 'Bad')->count(),
    ];

    return view('share.reports.report', compact(
        'groupedByLab',
        'availableLabs',
        'chartData',
        'availableDatesPerLab',
        'selectedLab'
    ));
}


public function exportpc(Request $request)
{
    $labs = $request->labs ?? [];
    $dates = $request->dates ?? [];

    $query = HistoryReportPC::with(['pc.lab', 'technician']);

    if (!empty($labs)) {
        $query->whereHas('pc.lab', fn($q) => $q->whereIn('lab_name', $labs));
    }

    if (!empty($dates)) {
        $query->whereIn(DB::raw('DATE(created_at)'), $dates);
    }

    $histories = $query->get();

    // Group per lab
    $data = $histories->groupBy(fn($item) => $item->pc->lab->lab_name ?? 'Unknown');

    $pdf = Pdf::loadView('share.reports.export', compact('data', 'labs', 'dates'))->setPaper('a4', 'landscape');

    return $pdf->stream('report-history.pdf');
}

}