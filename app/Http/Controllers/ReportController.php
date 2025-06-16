<?php

namespace App\Http\Controllers;


use App\Models\Form;
use App\Models\Report;
use App\Models\Reporter;
use App\Models\PC;
use App\Models\Laboratory;
use App\Models\Report_answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HasUserRole;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    use HasUserRole;

    public function __construct()
    {
        $this->setUserRole();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth::user();
        $role = $user->role;
        $search = $request->input('search');

        $reports = Report::with(['reporter', 'pc.lab', 'form', 'answers.question'])
            ->when($role === 'teknisi', function ($query) use ($user) {
                // Filter report berdasarkan PC yang lab-nya dimiliki teknisi
                $query->whereHas('pc.lab', function ($q) use ($user) {
                    $q->where('technician_id', $user->id);
                });
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('reporter', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%')
                        ->orWhere('npm', 'like', '%' . $search . '%');
                    })->orWhereHas('pc', function ($q2) use ($search) {
                        $q2->where('pc_name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->latest()
            ->get();

        return view('admin.report.index', compact('reports', 'role'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
public function create()
{

}


    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $report->load(['reporter', 'pc.lab', 'form', 'answers.question']);
    
        return view('admin.report.show', compact('report'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $report = Report::findOrFail($id);

            // Hapus jawaban terkait
            $report->answers()->delete();

            // Hapus reporter jika ingin (opsional, tergantung logika sistem)
            $report->reporter()->delete();

            // Hapus report
            $report->delete();
        });

        return redirect()->route($this->role. '.report.index')->with('success', 'Laporan berhasil dihapus.');
    }
    public function getAnswers($id)
    {
        $report = \App\Models\Report::with('answers.question')->findOrFail($id);
        return $report->answers->map(function ($a) {
            return [
                'question' => $a->question->question_text,
                'answer' => $a->answer_text,
            ];
        });
    }
    // ReportController.php
    public function check(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->checked = $request->checked;
        
        // Update status berdasarkan jawabannya
        $hasDamage = $report->answers->contains(function ($ans) {
            return strtolower($ans->answer_text) === 'rusak';
        });
        $report->status = $hasDamage ? 'rusak' : 'baik';
        $report->save();
    
        return response()->json(['success' => true]);
    }
    
    public function done()
    {
        $reports = Report::where('checked', true)->get();
    
        foreach ($reports as $report) {
            // Simpan ke history (sesuai struktur tabel Anda)
            History::create([
                'report_id' => $report->id,
                'status' => $report->status,
                'completed_at' => now()
            ]);
    
            $report->delete(); // atau ubah status menjadi "selesai"
        }
    
        return response()->json(['done' => true]);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baik,rusak,perbaikan'
        ]);

        $report = Report::findOrFail($id);
        $report->status = $request->status;
        $report->save();

        return response()->json(['success' => true]);
}


}
