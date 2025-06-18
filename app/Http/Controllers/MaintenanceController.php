<?php

namespace App\Http\Controllers;
use App\Models\Reminder;
use App\Models\Pc;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance;
use App\Models\HistoryMaintenance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class MaintenanceController extends Controller
{

public function index()
{
    $userId = auth::id();

    // Ambil semua reminder yang dikirim ke teknisi ini
    $reminders = Reminder::with('laboratory')
        ->where('user_id', $userId)
        ->latest()
        ->get();

    return view('teknisi.maintenance.index', compact('reminders'));
}


public function create(Reminder $reminder)
{
    if ($reminder->user_id !== auth::id()) {
        abort(403, 'Akses ditolak.');
    }

    $pcs = Pc::where('lab_id', $reminder->laboratory_id)->get();
    return view('teknisi.maintenance.create', compact('reminder', 'pcs'));
}


public function store(Request $request)
{
    $validated = $request->validate([
        'reminder_id' => 'required|exists:reminders,id',
        'laboratory_id' => 'required|exists:laboratories,id',
        'user_id' => 'required|exists:users,id',
        'note' => 'nullable|string',
        'pcs' => 'required|array',
        'pcs.*.pc_id' => 'required|exists:pcs,id',
        'pcs.*.status' => 'required|in:Good,Bad',
    ]);


    // 1. Simpan ke tabel `maintenances`
    $maintenance = Maintenance::create([
        'reminder_id' => $validated['reminder_id'],
        'laboratory_id' => $validated['laboratory_id'],
        'user_id' => $validated['user_id'],
        'note' => $validated['note'] ?? null,
    ]);

    // 2. Simpan ke tabel `history_maintenances`
    foreach ($validated['pcs'] as $pc) {
        HistoryMaintenance::create([
            'maintenance_id' => $maintenance->id,
            'pc_id' => $pc['pc_id'],
            'status' => $pc['status'],
        ]);
    }

    return redirect()->route('teknisi.maintenance.index')->with('success', 'Maintenance berhasil disimpan!');
}


public function history(Request $request)
{
    $selectedId = $request->input('maintenance_id');

    // Ambil semua maintenance milik teknisi
    $maintenances = Maintenance::where('user_id', auth::id())->with('laboratory')->latest()->get();

    // Jika ada ID yang dipilih, filter history berdasarkan itu
    $query = HistoryMaintenance::with(['pc', 'maintenance.laboratory', 'maintenance.user'])
        ->latest();

    if ($selectedId) {
        $query->where('maintenance_id', $selectedId);
    }

    $history = $query->paginate(10);

    // Chart data hanya untuk maintenance terpilih
    $chartData = HistoryMaintenance::select('status', DB::raw('count(*) as total'))
        ->when($selectedId, function ($q) use ($selectedId) {
            $q->where('maintenance_id', $selectedId);
        })
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    return view('teknisi.maintenance.history', compact('history', 'chartData', 'maintenances', 'selectedId'));
}


}
