<?php

namespace App\Http\Controllers;

use App\Models\HistoryReportPC;
use App\Models\LabReport;
use Illuminate\Http\Request;

class LaboratoryReportController extends Controller
{
    public function labReports()
    {
        $labReports = LabReport::with(['pc', 'technician']) // pastikan relasi ada
            ->orderBy('created_at', 'desc')
            ->get();

        return view('kepala_lab.lab_reports.index', compact('labReports'));
    }

//     public function historyReports()
//     {
//         $historyReports = HistoryReportPC::with(['pc', 'technician']) // pastikan relasi ada
//             ->orderBy('created_at', 'desc')
//             ->get();

//         return view('teknisi.history_reports.index', compact('historyReports'));
//     }
}
