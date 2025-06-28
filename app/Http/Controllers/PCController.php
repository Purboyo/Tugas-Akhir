<?php

namespace App\Http\Controllers;

use App\Models\PC;
use App\Models\Laboratory as Lab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class PCController extends Controller
{
    // Menampilkan semua PC
public function index(Request $request)
{
    $user = auth::user();
    $role = $user->role;

    // Ambil semua lab milik teknisi
    $labs = Lab::where('technician_id', $user->id)->get();

    $selectedLabId = $request->input('lab_id');

    // Ambil semua PC dari lab teknisi, dan filter lab_id jika dipilih
    $pcsQuery = PC::with('lab')->whereHas('lab', function ($query) use ($user) {
        $query->where('technician_id', $user->id);
    });

    if ($selectedLabId) {
        $pcsQuery->where('lab_id', $selectedLabId);
    }

    $pcs = $pcsQuery->paginate(10);

    return view('teknisi.pcs.index', compact('pcs', 'labs', 'selectedLabId', 'role'));
}



    // Menampilkan form untuk membuat PC baru
public function create()
{
    $user = auth::user();
    $role = $user->role;

    $labs = $role === 'teknisi'
        ? Lab::where('technician_id', $user->id)->get()
        : Lab::all();

    return view('teknisi.pcs.create', compact('labs', 'role'));
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

        return redirect()->route('teknisi.pc.index')->with('success', 'PC add successfully.');
    }

    // Menampilkan form untuk mengedit PC
public function edit($id)
{
    $pc = PC::findOrFail($id);
    $user = auth::user();
    $role = $user->role;

    $labs = $role === 'teknisi'
        ? Lab::where('technician_id', $user->id)->get()
        : Lab::all();

    return view('teknisi.pcs.edit', compact('pc', 'labs', 'role'));
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

        return redirect()->route('teknisi.pc.index')->with('success', 'PC update successfully.');
    }

    // Menghapus PC
    public function destroy(PC $pc)
    {
        $pc->delete();
        return redirect()->route('teknisi.pc.index')->with('success', 'PC delete successfully.');
    }
}
