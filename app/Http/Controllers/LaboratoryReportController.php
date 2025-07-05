<?php

namespace App\Http\Controllers;

use App\Models\HistoryReportPC;
use App\Models\LabReport;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryReportController extends Controller
{
    public function labReports()
    {
        $labs = Laboratory::with('pcs')->get();
        $labReports = LabReport::with(['pc', 'technician', 'pc.lab'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan lab
        $labReportsGrouped = $labReports->groupBy(fn($report) => $report->pc->lab->id ?? 'unknown');

        return view('kepala_lab.lab_reports.index', compact('labs', 'labReportsGrouped'));
    }

    public function update(Request $request, $id)
    {
        $report = LabReport::findOrFail($id);
        $report->status = $request->status;
        $report->handling_notes = $request->handling_notes;
        $report->save();

        return redirect()->back()->with('success', 'Report updated successfully.');
    }

    public function labReportsJurusan()
    {
        $labs = Laboratory::with('pcs')->get();
        $labReports = LabReport::with(['pc', 'technician', 'pc.lab'])
            ->where('status', 'reviewed')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan berdasarkan lab
        $labReportsGrouped = $labReports->groupBy(fn($report) => $report->pc->lab->id ?? 'unknown');

        return view('jurusan.lab_reports.index', compact('labs', 'labReportsGrouped'));
}

}
