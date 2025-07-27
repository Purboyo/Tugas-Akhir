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
            ->orderByRaw('GREATEST(COALESCE(updated_at, 0), COALESCE(created_at, 0)) DESC')
            ->get();

        return view('kepala_lab.lab_reports.index', compact('labs', 'labReports'));
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
            ->orderByRaw('GREATEST(COALESCE(updated_at, 0), COALESCE(created_at, 0)) DESC')
            ->get();

        return view('jurusan.lab_reports.index', compact('labs', 'labReports'));
    }

    public function updatejurusan(Request $request, $id)
    {
        $report = LabReport::findOrFail($id);
        $report->status = $request->status;
        $report->handling_notes = $request->handling_notes;
        $report->save();

        return redirect()->back()->with('success', 'Report updated successfully.');
    }

    public function labReportsTeknisi()
    {
        $labs = Laboratory::with('pcs')->get();

        $labReports = LabReport::with(['pc', 'technician', 'pc.lab'])
            ->orderByRaw('GREATEST(COALESCE(updated_at, 0), COALESCE(created_at, 0)) DESC')
            ->get();

        return view('teknisi.lab_reports.index', compact('labs', 'labReports'));
    }

        public function updateteknisi(Request $request, $id)
    {
        $report = LabReport::findOrFail($id);
        $report->status = $request->status;
        $report->description = $request->description;
        $report->save();

        return redirect()->back()->with('success', 'Report updated successfully.');
    }
}
