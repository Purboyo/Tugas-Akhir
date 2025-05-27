<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Laboratory as Lab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class PCController extends Controller
{
    // Menampilkan semua PC
    public function index(Request $request)
    {
        $labs = Lab::all(); // untuk dropdown
        $selectedLabId = $request->input('lab_id');

        $pcs = PC::with('lab');

        if ($selectedLabId) {
            $pcs->where('lab_id', $selectedLabId);
        }

        $pcs = $pcs->get();

        return view('admin.pcs.index', compact('pcs', 'labs', 'selectedLabId'));
    }


    // Menampilkan form untuk membuat PC baru
    public function create()
    {
        $labs = Lab::all(); // Ambil semua lab
        return view('admin.pcs.create', compact('labs'));
    }

    // Menyimpan PC baru
    public function store(Request $request)
    {
        $request->validate([
            'pc_name' => [
                'required',
                Rule::unique('pcs')->where(function ($query) use ($request) {
                    return $query->where('lab_id', $request->lab_id);
                })
            ],
            'lab_id' => 'required|exists:laboratories,id',
        ]);

        PC::create($request->only(['pc_name', 'lab_id']));

        return redirect()->route('pc.index')->with('success', 'PC berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit PC
    public function edit(PC $pc)
    {
        $labs = Lab::all();
        return view('admin.pcs.edit', compact('pc', 'labs'));
    }

    // Mengupdate data PC
    public function update(Request $request, PC $pc)
    {
        $request->validate([
            'pc_name' => [
                'required',
                Rule::unique('pcs')->where(function ($query) use ($request) {
                    return $query->where('lab_id', $request->lab_id);
                })->ignore($pc->id),
            ],
            'lab_id' => 'required|exists:laboratories,id',
        ]);

        $pc->update($request->only(['pc_name', 'lab_id']));

        return redirect()->route('pc.index')->with('success', 'PC berhasil diupdate.');
    }

    // Menghapus PC
    public function destroy(PC $pc)
    {
        $pc->delete();
        return redirect()->route('pc.index')->with('success', 'PC berhasil dihapus.');
    }
}
