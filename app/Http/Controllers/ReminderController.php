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

    $activeReminders = Reminder::with(['user', 'laboratory', 'maintenance', 'historyMaintenance'])
        ->when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhereHas('laboratory', function ($labQuery) use ($search) {
                    $labQuery->where('lab_name', 'like', "%{$search}%");
                });
        })
        ->get()
        ->filter(function ($reminder) {
            return $reminder->computed_status === 'pending';
        })
        ->values();

    $completedReminders = Reminder::with(['user', 'laboratory', 'maintenance', 'historyMaintenance'])
        ->when($search, function ($query, $search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhereHas('laboratory', function ($labQuery) use ($search) {
                    $labQuery->where('lab_name', 'like', "%{$search}%");
                });
        })
        ->get()
        ->filter(function ($reminder) {
            return $reminder->computed_status !== 'pending';
        })
        ->values();

    // manual pagination per collection
    $activePage = request()->get('active_page', 1);
    $completedPage = request()->get('completed_page', 1);
    $perPage = 5;

    $activePaginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $activeReminders->forPage($activePage, $perPage),
        $activeReminders->count(),
        $perPage,
        $activePage,
        ['pageName' => 'active_page']
    );

    $completedPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $completedReminders->forPage($completedPage, $perPage),
        $completedReminders->count(),
        $perPage,
        $completedPage,
        ['pageName' => 'completed_page']
    );

    return view('admin.reminder.index', [
        'activeReminders' => $activePaginated,
        'completedReminders' => $completedPaginated,
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

        
        Reminder::create([
            'laboratory_id' => $request->laboratory_id,
            'title' => $request->title,
            'description' => $request->description,
            'reminder_date' => $request->reminder_date,
            'status' => 'pending', // default saat dibuat
        ]);

        return redirect()->route('admin.reminder.index')->with('success', 'Reminder berhasil ditambahkan.');
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

