<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Laboratory as Lab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Traits\HasUserRole;
use Illuminate\Support\Facades\Auth;


class PCController extends Controller
{
    use HasUserRole;

    public function __construct()
    {
        $this->setUserRole();
    }
    // Menampilkan semua PC
    public function index(Request $request)
    {
        $role = auth::user()->role;
        $labs = Lab::all(); // untuk dropdown
        $selectedLabId = $request->input('lab_id');

        // Mulai query
        $pcsQuery = PC::with('lab');

        // Jika ada lab_id yang dipilih, tambahkan kondisi where
        if ($selectedLabId) {
            $pcsQuery->where('lab_id', $selectedLabId);
        }

        // Lakukan pagination
        $pcs = $pcsQuery->paginate(10);

        return view($role . '.pcs.index', compact('pcs', 'labs', 'selectedLabId'));
    }

    // Menampilkan form untuk membuat PC baru
    public function create()
    {
        $role = auth::user()->role;
        $labs = Lab::all(); // Ambil semua lab
        return view($role .'.pcs.create', compact('labs'));
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

        return redirect()->route($this->role . '.pc.index')->with('success', 'PC berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit PC
    public function edit(PC $pc)
    {
        $role = auth::user()->role;
        $labs = Lab::all();
        return view($role . '.pcs.edit', compact('pc', 'labs'));
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

        return redirect()->route($this->role . '.pc.index')->with('success', 'PC berhasil diupdate.');
    }

    // Menghapus PC
    public function destroy(PC $pc)
    {
        $pc->delete();
        return redirect()->route($this->role . '.pc.index')->with('success', 'PC berhasil dihapus.');
    }
}
