<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
public function index(Request $request)
{
    $search = $request->input('search');

    $reminders = Reminder::with('user')
        ->when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%");
        })
        ->latest()
        ->get();

    return view('admin.reminder.index', compact('reminders'));
}




    public function create()
    {
        $users = User::where('role', 'teknisi')->get(); // hanya teknisi
        return view('admin.reminder.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'laboratory_id' => 'required|exists:laboratories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
        ]);

        Reminder::create([
            'user_id' => $request->user_id,
            'laboratory_id' => $request->laboratory_id,
            'title' => $request->title,
            'description' => $request->description,
            'reminder_date' => $request->reminder_date,
            'status' => 'pending', // default saat dibuat
        ]);

        return redirect()->route('admin.reminder.index')->with('success', 'Reminder berhasil ditambahkan.');
    }

public function edit($id)
{
    $reminder = Reminder::findOrFail($id);
    $users = User::where('role', 'teknisi')->get();

    return view('admin.reminder.edit', compact('reminder', 'users'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'laboratory_id' => 'required|exists:laboratories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'reminder_date' => 'required|date',
    ]);

    $reminder = Reminder::findOrFail($id);
    $reminder->update([
        'user_id' => $request->user_id,
        'laboratory_id' => $request->laboratory_id,
        'title' => $request->title,
        'description' => $request->description,
        'reminder_date' => $request->reminder_date,
    ]);

    return redirect()->route('admin.reminder.index')->with('success', 'Reminder berhasil diperbarui.');
}


    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return back()->with('success', 'Reminder deleted!');
    }
    
    public function teknisiReminders()
    {
        $user = Auth::user();
        $reminders = Reminder::where('user_id', $user->id)->latest()->get();
        return view('teknisi.reminder.index', compact('reminders'));
    }

    public function getLaboratories($userId)
    {
        $labs = \App\Models\Laboratory::where('technician_id', $userId)->get();
        return response()->json($labs);
    }

    
}

