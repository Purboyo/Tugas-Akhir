<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\HistoryMaintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class HistoryController extends Controller
{


public function historyreportpc(Request $request)
{
    $userId = Auth::id();

    $dataQuery = HistoryMaintenance::with([
        'pc',
        'maintenance.reminder.laboratory',
        'maintenance.reminder.user'
    ])->whereHas('maintenance.reminder.user', fn($q) => $q->where('id', $userId));

    // Global filter optional (maintenance_id)
    if ($request->filled('maintenance_id')) {
        $dataQuery->where('maintenance_id', $request->maintenance_id);
    }

    $allHistories = $dataQuery->get();

    // Semua lab tanpa difilter dulu, agar dropdown tetap penuh
    $availableLabs = $allHistories->pluck('maintenance.reminder.laboratory.lab_name')
        ->unique()->sort()->values();

    $globalLab = $request->lab;

    // Dipakai hanya untuk filtering data yang ditampilkan di bawah
    $filteredLabs = $availableLabs;
    if ($globalLab) {
        $filteredLabs = $availableLabs->filter(fn($lab) => $lab === $globalLab)->values();
    }

    // Tanggal-tanggal tersedia per lab
    $availableDatesPerLab = $availableLabs->mapWithKeys(function ($labName) use ($allHistories) {
        $dates = $allHistories->filter(fn($item) =>
            optional($item->maintenance->reminder->laboratory)->lab_name === $labName
        )->pluck('created_at')
         ->map(fn($d) => $d->format('Y-m-d'))
         ->unique()->sort()->values();

        return [$labName => $dates];
    });

    // Group dan paginasi berdasarkan lab yang difilter (filteredLabs)
    $groupedByLab = $filteredLabs->mapWithKeys(function ($labName) use ($allHistories, $request) {
        $slug = Str::slug($labName);

        $labData = $allHistories->filter(fn($item) =>
            optional($item->maintenance->reminder->laboratory)->lab_name === $labName
        );

        $dateKey = "date_$slug";
        if ($request->filled($dateKey)) {
            $labData = $labData->filter(fn($item) =>
                $item->created_at->toDateString() === $request->input($dateKey)
            );
        }

        $pageKey = "page_$slug";
        $currentPage = $request->input($pageKey, 1);
        $perPage = 10;
        $items = $labData->values();

        $paginated = new LengthAwarePaginator(
            $items->slice(($currentPage - 1) * $perPage, $perPage),
            $items->count(),
            $perPage,
            $currentPage,
            ['pageName' => $pageKey, 'path' => request()->url(), 'query' => request()->query()]
        );

        return [$labName => $paginated];
    });

    // Chart global (dari semua data, tidak hanya yang ditampilkan)
    $chartData = $dataQuery->select('status', DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    unset($chartData['Pending']);

    return view('Share.maintenance.history', [
        'chartData' => $chartData,
        'availableLabs' => $availableLabs, // semua lab untuk dropdown
        'groupedByLab' => $groupedByLab,   // hanya lab yang difilter untuk tampilan bawah
        'availableDatesPerLab' => $availableDatesPerLab,
        'selectedId' => $request->input('maintenance_id'),
        'role' => Auth::user()->role,
    ]);
}


}
