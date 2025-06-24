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

        return view('teknisi.report.index', compact('reports', 'role'));
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
    
        return view('teknisi.report.show', compact('report'));
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
   


public function checkAll()
{
    // Ambil semua laporan yang belum dicek
    $reports = Report::where('checked', true)->get();

    foreach ($reports as $report) {
        // Simpan ke history_reports
        HistoryReportPC::create([
            'pc_id' => $report->pc_id,
            'technician_id' => $report->technician_id,
            'description' => $report->description,
            'status' => $report->status,
        ]);
    }

    // Hapus laporan yang sudah dipindahkan
    Report::where('checked', true)->delete();

    return response()->json(['message' => 'Semua laporan telah dicentang dan dipindahkan ke riwayat. resfresh halaman untuk melihat perubahan.']);
}

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:Good,Bad,Repairing,Pending',
    ]);

    $report = Report::findOrFail($id);
    $report->status = $request->status;
    $report->save();

    return redirect()->back()->with('success', 'Status berhasil diperbarui.');
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
            'status' => 'Reported',
        ]);

        // Hapus data dari tabel report
        Report::where('pc_id', $pc_id)
            ->where('status', 'Bad')
            ->where('checked', 1)
            ->delete();
    }

    return redirect()->route('teknisi.report.index')->with('success', 'Laporan berhasil dikirim ke Kepala Lab.');
}

}
