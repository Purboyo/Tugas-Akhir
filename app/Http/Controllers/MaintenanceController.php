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
    // Tampilkan semua reminder untuk teknisi
    public function index()
    {
        $userId = auth::id();

        $reminders = Reminder::with('laboratory')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('teknisi.maintenance.index', compact('reminders'));
    }

    // Form pengisian maintenance berdasarkan reminder
    public function create(Reminder $reminder)
    {
        if ($reminder->user_id !== auth::id()) {
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
        ]);

        // Ambil reminder untuk keperluan penyimpanan
        $reminder = Reminder::findOrFail($validated['reminder_id']);

        // Buat maintenance tanpa user_id
        $maintenance = Maintenance::create([
            'reminder_id' => $reminder->id,
            'note' => $validated['note'] ?? null,
        ]);

        // Buat histori tiap PC
        foreach ($validated['pcs'] as $pc) {
            HistoryMaintenance::create([
                'maintenance_id' => $maintenance->id,
                'pc_id' => $pc['pc_id'],
                'status' => $pc['status'],
            ]);
        }

        return redirect()->route('teknisi.maintenance.index')->with('success', 'Maintenance berhasil disimpan!');
    }




}
