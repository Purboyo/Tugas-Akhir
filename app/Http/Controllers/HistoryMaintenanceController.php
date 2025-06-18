<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maintenance;
use App\Models\HistoryMaintenance;
use Illuminate\Support\Facades\DB;

class HistoryMaintenanceController extends Controller
{

public function index(Request $request)
{
    // Ambil semua maintenance untuk filter
    $maintenances = Maintenance::orderBy('created_at', 'desc')->get();

    // Filter berdasarkan maintenance_id jika ada
    $query = HistoryMaintenance::with(['pc', 'maintenance']);

    if ($request->has('maintenance_id') && $request->maintenance_id != '') {
        $query->where('maintenance_id', $request->maintenance_id);
    }

    // Data untuk tabel
    $pcs = $query->paginate(10);

    // Data untuk chart
    $chartData = $query->select('status', \DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status');

    return view('teknisi.history_maintenance.index', compact('pcs', 'chartData', 'maintenances'));
}

}
