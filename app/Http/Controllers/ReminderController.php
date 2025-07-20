<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Laboratory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderNotification;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{

public function index(Request $request)
{
    // Update status semua reminder sebelum load
    Reminder::updateReminderStatuses();

    $search = $request->input('search');

    // Ambil data reminder dengan status pending
    $activeReminders = Reminder::with(['user', 'laboratory', 'maintenance', 'historyMaintenance'])
        ->when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhereHas('laboratory', function ($labQuery) use ($search) {
                    $labQuery->where('lab_name', 'like', "%{$search}%");
                });
        })
        ->where('status', 'pending') // ambil dari field status
        ->orderBy('reminder_date', 'asc')
        ->paginate(5, ['*'], 'active_page');

    // Ambil data reminder dengan status completed dan missed
    $completedReminders = Reminder::with(['user', 'laboratory', 'maintenance', 'historyMaintenance'])
        ->when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhereHas('laboratory', function ($labQuery) use ($search) {
                    $labQuery->where('lab_name', 'like', "%{$search}%");
                });
        })
        ->whereIn('status', ['completed', 'missed']) // ambil dari field status
        ->orderBy('reminder_date', 'desc')
        ->paginate(5, ['*'], 'completed_page');

    return view('admin.reminder.index', [
        'activeReminders' => $activeReminders,
        'completedReminders' => $completedReminders,
    ]);
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
        'laboratory_id' => [
            'required',
            'exists:laboratories,id',
            Rule::unique('reminders')->where(function ($query) use ($request) {
                return $query->whereDate('reminder_date', Carbon::parse($request->reminder_date)->format('Y-m-d'));
            }),
        ],
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'reminder_date' => 'required|date|after_or_equal:today',
    ]);

    $reminder = Reminder::create([
        'laboratory_id' => $request->laboratory_id,
        'title' => $request->title,
        'description' => $request->description,
        'reminder_date' => $request->reminder_date,
        'status' => 'pending',
    ]);

    // Load relasi laboratory dan teknisi (user)
    $reminder->load('laboratory.technician');

    // Kirim email ke teknisi (user) jika tersedia
    $technician = $reminder->laboratory->technician;
    if ($technician && $technician->email) {
        try {
            Mail::to($technician->email)->send(new ReminderNotification($reminder));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email reminder: ' . $e->getMessage());
        }
    }


    return redirect()->route('admin.reminder.index')->with('success', 'Reminder berhasil ditambahkan dan email telah dikirim.');
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
    // Ambil semua data laboratorium dengan teknisi tertentu
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

