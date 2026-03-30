<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RevenueExport;
use App\Exports\ExpensesExport;
use App\Exports\RevenueWithVatExport;
use App\Exports\RevenueWithoutVatExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{


    public function index(){

        return view('reports.index');
    }


    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $reportType = $request->report_type;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if ($reportType === 'expenses') {
            return Excel::download(new ExpensesExport($fromDate, $toDate), "Expenses_Report_{$fromDate}_to_{$toDate}.xlsx");
        } 
        elseif ($reportType === 'revenue') {
            return Excel::download(new RevenueExport($fromDate, $toDate), "Revenue_Report_{$fromDate}_to_{$toDate}.xlsx");
        }elseif ($reportType === 'revenue_vat') {
            return Excel::download(new RevenueWithVatExport($fromDate, $toDate), "Revenue_Report_{$fromDate}_to_{$toDate}.xlsx");
        }elseif ($reportType === 'revenue_no_vat') {
            return Excel::download(new RevenueWithoutVatExport($fromDate, $toDate), "Revenue_Report_{$fromDate}_to_{$toDate}.xlsx");
        }

        return back()->with('error', 'Invalid report type selected.');
    }
}
