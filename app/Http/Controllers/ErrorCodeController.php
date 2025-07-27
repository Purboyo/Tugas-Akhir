<?php

namespace App\Http\Controllers;

use App\Models\ErrorCode;
use Illuminate\Http\Request;

class ErrorCodeController extends Controller
{
public function index()
    {
        $errorCodes = ErrorCode::all();
        return view('admin.error_codes.index', compact('errorCodes'));
    }

    public function create()
    {
        // Ambil kode terakhir
        $lastCode = ErrorCode::orderBy('id', 'desc')->first();

        if ($lastCode) {
            $lastNumber = (int) substr($lastCode->code, 2);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newCode = 'KD' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return view('admin.error_codes.create', compact('newCode'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|max:10|unique:error_codes,code',
            'description' => 'required|max:50',
        ]);

        ErrorCode::create($request->only('code', 'description'));

        return redirect()->route('admin.error-codes.index')->with('success', 'Error code added.');
    }

    public function edit(ErrorCode $errorCode)
    {
        return view('admin.error_codes.edit', compact('errorCode'));
    }

    public function update(Request $request, ErrorCode $errorCode)
    {
        $request->validate([
            'description' => 'required|max:50',
        ]);

        // Kode tidak bisa diubah, jadi cukup update deskripsi saja
        $errorCode->update([
            'description' => $request->description
        ]);

        return redirect()->route('admin.error-codes.index')->with('success', 'Error code updated.');
    }

    public function destroy(ErrorCode $errorCode)
    {
        $errorCode->delete();
        return redirect()->route('admin.error-codes.index')->with('success', 'Error code deleted.');
    }
}
