<?php

namespace App\Http\Controllers;


use App\Models\Form;
use App\Models\Report;
use App\Models\Reporter;
use App\Models\PC;
use App\Models\Laboratory;
use App\Models\Report_answer;
use Illuminate\Http\Request;
use App\Models\LabReport;
use App\Models\HistoryReportPC;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth::user();
        $role = $user->role;
    
        $reports = Report::with(['reporter', 'pc.lab', 'form', 'answers.question'])
            ->when($role === 'teknisi', function ($query) use ($user) {
                // Filter report berdasarkan lab teknisi
                $query->whereHas('pc.lab', function ($q) use ($user) {
                    $q->where('technician_id', $user->id);
                });
            })
            ->get()
               ->sortByDesc('created_at')
                ->sortByDesc(fn($report) => $report->status === 'Bad')
                ->groupBy(fn($r) => $r->pc->lab->lab_name ?? 'Lab Tidak Diketahui');
        
        return view('teknisi.report.index', compact('reports', 'role'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        $report->load(['reporter', 'pc.lab', 'form', 'answers.question']);
    
        return view('teknisi.report.show', compact('report'));
    }
    

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     DB::transaction(function () use ($id) {
    //         $report = Report::findOrFail($id);

    //         // Hapus jawaban terkait
    //         $report->answers()->delete();

    //         // Hapus reporter jika ingin (opsional, tergantung logika sistem)
    //         $report->reporter()->delete();

    //         // Hapus report
    //         $report->delete();
    //     });

    //     return redirect()->route('teknisi.report.index')->with('success', 'Report and its answers deleted successfully.');
    // }
    public function getAnswers($id)
    {
        $report = Report::with('answers.question')->findOrFail($id);
        return $report->answers->map(function ($a) {
            return [
                'question' => $a->question->question_text,
                'answer' => $a->answer_text,
            ];
        });
    }
   public function check(Request $request, $id)
   {
       try {
           $report = Report::findOrFail($id); // Find the existing report
           $report->checked = $request->input('checked') ? true : false;
           $report->save();
           return response()->noContent();
           // Return a JSON response indicating success
            //    return response()->json(['message' => 'Checklist status updated.']);
       } catch (\Exception $e) {
           return response()->json([
               'message' => 'Failed to update status',
               'error' => $e->getMessage()
           ], 500);
       }
   }
   


public function checkAll(Request $request)
{
    $reportIds = $request->input('report_ids');

    if (empty($reportIds)) {
        return response()->json(['message' => 'No report selected.'], 400);
    }

    // Ambil report + relasi lab
    $reports = Report::with('lab')->whereIn('id', $reportIds)->get();

    foreach ($reports as $report) {
        HistoryReportPC::create([
            'pc_id' => $report->pc_id,
            'technician_id' => optional($report->lab)->technician_id ?? auth::id(),
            'description' => $report->description,
            'status' => $report->status, // sudah ikut disimpan ke tabel history
        ]);
    }

    Report::whereIn('id', $reportIds)->delete();

    return response()->json([
        'message' => 'All reports have been moved to history, page will be refreshed in 3 seconds.'
    ]);
}




public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Good,Bad,Repairing,Pending',
    ]);

    $report = Report::findOrFail($id);
    $report->status = $request->status;
    $report->save();

    return redirect()->back()->with('success', 'Status report updated successfully.');
}
public function reportBadForm()
{
    
$pcs = Report::where('status', 'Bad')
    ->where('checked', 1)
    ->with('pc')
    ->get()
    ->groupBy('pc_id');

    return view('teknisi.report.report_bad_form', compact('pcs'));
}

public function submitBadReport(Request $request)
{
    $request->validate([
        'descriptions' => 'required|array',
        'descriptions.*' => 'required|string|max:1000',
    ]);

    foreach ($request->descriptions as $pc_id => $description) {
        // Kirim ke lab_reports
        LabReport::create([
            'pc_id' => $pc_id,
            'technician_id' => auth::id(),
            'description' => $description,
        ]);

        // Simpan ke history_reports
        HistoryReportPC::create([
            'pc_id' => $pc_id,
            'technician_id' => auth::id(),
            'description' => $description,
            'status' => 'Bad',
        ]);

        // Hapus data dari tabel report
        Report::where('pc_id', $pc_id)
            ->where('status', 'Bad')
            ->where('checked', 1)
            ->delete();
    }

    return redirect()->route('teknisi.report.index')->with('success', 'Report has been submitted to kepala lab successfully.');
}

}
