<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Laboratory;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
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
        if ($request->has('reminder_date')) {
            try {
                $parsedDate = Carbon::parse($request->reminder_date)->format('Y-m-d');
                $request->merge(['reminder_date' => $parsedDate]);
            } catch (\Exception $e) {
                return back()->withErrors(['reminder_date' => 'Format tanggal tidak valid.']);
            }
        }
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'laboratory_id' => [
                'required',
                'exists:laboratories,id',
                Rule::unique('reminders')->where(function ($query) use ($request) {
                    return $query->whereDate('reminder_date', Carbon::parse($request->reminder_date)->format('Y-m-d'));
                }),
            ],
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
    $labs = Laboratory::where('technician_id', $reminder->user_id)->get();
    $users = User::where('role', 'teknisi')->get();

    return view('admin.reminder.edit', compact('reminder', 'users', 'labs'));
}

public function update(Request $request, $id)
{
    if ($request->has('reminder_date')) {
        try {
            $parsedDate = Carbon::parse($request->reminder_date)->format('Y-m-d');
            $request->merge(['reminder_date' => $parsedDate]);
        } catch (\Exception $e) {
            return back()->withErrors(['reminder_date' => 'Format tanggal tidak valid.']);
        }
    }
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'laboratory_id' => 'required|exists:laboratories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'reminder_date' => 'required|date',
    ]);

    $reminder = Reminder::findOrFail($id);

    $reminder->update($request->only([
        'user_id',
        'laboratory_id',
        'title',
        'description',
        'reminder_date',
    ]));

    return redirect()->route('admin.reminder.index')
        ->with('success', 'Reminder berhasil diperbarui.');
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

public function getLaboratories($technicianId)
{
    $labs = Laboratory::where('technician_id', $technicianId)
        ->with('reminders') // relasi ke Reminder
        ->get()
        ->map(function ($lab) {
            return [
                'id' => $lab->id,
                'lab_name' => $lab->lab_name,
                'has_reminder' => $lab->reminders()->exists(), // true kalau sudah ada reminder
            ];
        });

    return response()->json($labs);
}



    
}

