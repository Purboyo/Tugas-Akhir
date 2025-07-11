<?php

namespace App\Http\Controllers;

use App\Models\Laboratory as Lab;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HasUserRole;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LaboratoryController extends Controller
{
    use HasUserRole;

    public function __construct()
    {
        $this->setUserRole();
    }
    // Menampilkan semua lab
public function index(Request $request)
{
    $user = Auth::user();
    $role = $user->role;
    $search = $request->input('search');

    // Base query dengan eager load relasi pcs dan technician
    $query = Lab::with('pcs', 'technician');

    // Jika user adalah teknisi, filter berdasarkan technician_id
    if ($role === 'teknisi') {
        $query->where('technician_id', $user->id);
    }

    // Tambahkan pencarian jika ada input search
    if ($search) {
        $query->where('lab_name', 'like', '%' . $search . '%');
    }

    $labs = $query->orderBy('lab_name')->paginate(10);

    return view($this->role .'.labs.index', compact('labs', 'role'));
}


    // Menampilkan form untuk membuat lab baru
    public function create()
    {
        $technicians = User::where('role', 'teknisi')->get(); // Ambil teknisi
        return view('admin.labs.create', compact('technicians'));
    }

    // Menyimpan lab baru
    public function store(Request $request)
    {
        $request->validate([
            'lab_name' => 'required|string|max:255',
            'technician_id' => 'required|exists:users,id',
        ]);

        Lab::create($request->only(['lab_name', 'technician_id']));

        return redirect()->route($this->role.'.lab.index')->with('success', 'Laboratory add successfully.');
    }

    // Menampilkan form untuk mengedit lab
    public function edit(Lab $lab)
    {
        $technicians = User::where('role', 'teknisi')->get(); // Ambil teknisi
        return view('admin.labs.edit', compact('lab', 'technicians'));
    }

    // Mengupdate data lab
    public function update(Request $request, Lab $lab)
    {
        $request->validate([
            'lab_name' => 'required|string|max:255',
            'technician_id' => 'required|exists:users,id',
        ]);

        $lab->update($request->only(['lab_name', 'technician_id']));

        return redirect()->route($this->role. '.lab.index')->with('success', 'Laboratory update successfully.');
    }

    // Menghapus lab
    public function destroy(Lab $lab)
    {
        $lab->delete();
        return redirect()->route($this->role.'.lab.index')->with('success', 'Laboratory delete successfully.');
    }
}
