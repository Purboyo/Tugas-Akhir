<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PC;
use App\Models\Laboratory as Lab;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;


class PCController extends Controller
{
    // Menampilkan semua PC
public function index(Request $request)
{
    $user = auth()->user();
    $role = $user->role;

    // Ambil semua lab milik teknisi
    $labs = Lab::with('pcs')->where('technician_id', $user->id)->get();

    // Siapkan data PCs yang sudah dipaginasi per lab
    $labsWithPaginatedPCs = $labs->map(function ($lab) use ($request) {
        $pcs = $lab->pcs;

        $labId = $lab->id;
        $currentPage = $request->input("page_lab_$labId", 1);
        $perPage = 10;

        $paginated = new LengthAwarePaginator(
            $pcs->forPage($currentPage, $perPage)->values(),
            $pcs->count(),
            $perPage,
            $currentPage,
            ['pageName' => "page_lab_$labId", 'path' => request()->url(), 'query' => request()->query()]
        );

        $lab->setRelation('pcs_paginated', $paginated);
        return $lab;
    });

    return view('teknisi.pcs.index', [
        'labs' => $labsWithPaginatedPCs,
        'role' => $role,
    ]);
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

    // Simpan data PC terlebih dahulu
    $pc = PC::create($request->only(['pc_name', 'lab_id']));

    // URL yang akan dimasukkan dalam QR
    $url = route('welcome', ['id' => $pc->id]);

    // Generate SVG menggunakan simple-qrcode (tidak butuh Imagick atau GD)
    $svg = QrCode::format('svg')->size(300)->generate($url);

    // Nama file dan simpan SVG ke storage
    $fileName = 'qr_codes/pc_' . $pc->id . '_' . Str::random(5) . '.svg';
    Storage::disk('public')->put($fileName, $svg);

    // Update kolom qr_code
    $pc->update(['qr_code' => $fileName]);

    return redirect()->route('teknisi.pc.index')->with('success', 'PC berhasil ditambahkan dan QR Code disimpan.');
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
