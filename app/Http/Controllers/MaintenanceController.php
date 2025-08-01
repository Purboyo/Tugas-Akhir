<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Pc;
use App\Models\Maintenance;
use App\Models\HistoryMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil semua lab milik user
        $labIds = $user->labs()->pluck('id');

        // Ambil semua reminder dari lab-lab tersebut
        $reminders = Reminder::with('laboratory')
            ->whereIn('laboratory_id', $labIds)
            ->latest()
            ->get();

        return view('teknisi.maintenance.index', compact('reminders'));
    }


    // Form pengisian maintenance berdasarkan reminder
    public function create(Reminder $reminder)
    {
        $user = auth()->user();
        $allowedLabIds = $user->labs()->pluck('id');

        if (!in_array($reminder->laboratory_id, $allowedLabIds->toArray())) {
            abort(403, 'Akses ditolak.');
        }

        // Ambil PC sesuai laboratorium dari reminder
        $pcs = Pc::where('lab_id', $reminder->laboratory_id)->get();

        return view('teknisi.maintenance.create', compact('reminder', 'pcs'));
    }

    // Simpan maintenance dan history pengecekan PC
    public function store(Request $request)
{
    $validated = $request->validate([
        'reminder_id' => 'required|exists:reminders,id',
        'note' => 'nullable|string',
        'pcs' => 'required|array',
        'pcs.*.pc_id' => 'required|exists:pcs,id',
        'pcs.*.status' => 'required|in:Good,Bad',
        'pcs.*.note' => 'nullable|string',
    ]);

    // Ambil reminder untuk keperluan penyimpanan
    $reminder = Reminder::findOrFail($validated['reminder_id']);

    // Buat maintenance umum
    $maintenance = Maintenance::create([
        'reminder_id' => $reminder->id,
        'note' => $validated['note'] ?? null,
    ]);

    // Buat histori tiap PC dengan catatan masing-masing
    foreach ($validated['pcs'] as $pc) {
        HistoryMaintenance::create([
            'maintenance_id' => $maintenance->id,
            'pc_id' => $pc['pc_id'],
            'status' => $pc['status'],
            'note' => $pc['note'] ?? null, 
        ]);
    }

    return redirect()->route('teknisi.maintenance.index')->with('success', 'Maintenance berhasil disimpan!');
}





}
