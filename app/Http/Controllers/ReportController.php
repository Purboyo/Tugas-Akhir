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
        $search = $request->input('search');
    
        $reports = Report::with(['reporter', 'pc.lab', 'form', 'answers.question'])
            ->when($search, function ($query, $search) {
                $query->whereHas('reporter', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('npm', 'like', '%' . $search . '%');
                })
                ->orWhereHas('pc', function ($q) use ($search) {
                    $q->where('pc_name', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->get();
    
        return view('admin.report.index', compact('reports'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forms = Form::with('questions')->get();
        $computers = PC::with('lab')->get();
        return view('admin.report.create', compact('forms', 'computers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'npm' => 'required|string|max:100',
            'computer_id' => 'required|exists:computers,id',
            'form_id' => 'required|exists:forms,id',
            'answers' => 'required|array',
        ]);

        $reporter = Reporter::create([
            'name' => $request->name,
            'npm' => $request->npm,
        ]);

        $report = Report::create([
            'reporter_id' => $reporter->id,
            'computer_id' => $request->computer_id,
            'form_id' => $request->form_id,
        ]);

        foreach ($request->answers as $questionId => $answer) {
            Report_answer::create([
                'report_id' => $report->id,
                'question_id' => $questionId,
                'answer_text' => is_array($answer) ? implode(', ', $answer) : $answer,
            ]);
        }

        return redirect()->route('reports.create')->with('success', 'Laporan berhasil disimpan.');
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
    
}
