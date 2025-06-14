<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::with('user')->latest()->get();
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'reminder_date' => 'required|date',
        ]);

        Reminder::create($request->all());

        return redirect()->route('admin.reminder.index')->with('success', 'Reminder berhasil ditambahkan!');
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
}

