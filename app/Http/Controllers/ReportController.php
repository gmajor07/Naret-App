<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\RevenueExport;
use App\Exports\ExpensesExport;
use App\Exports\RevenueWithVatExport;
use App\Exports\RevenueWithoutVatExport;
use App\Exports\RevenueNonVatExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{


    public function index(){

        return view('reports.index', [
            'reportTypes' => $this->availableReportTypes(),
        ]);
    }


    public function generateReport(Request $request)
    {
        // Increase memory limit for large reports
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $request->validate([
            'report_type' => 'required',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $reportType = $request->report_type;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        if (! array_key_exists($reportType, $this->availableReportTypes())) {
            return back()->with('error', 'You are not allowed to generate this report type.');
        }

        try {
            if ($reportType === 'expenses') {
                return Excel::download(new ExpensesExport($fromDate, $toDate), "Expenses_Report_{$fromDate}_to_{$toDate}.xlsx");
            } 
            elseif ($reportType === 'revenue') {
                return Excel::download(new RevenueExport($fromDate, $toDate), "Revenue_Report_{$fromDate}_to_{$toDate}.xlsx");
            }elseif ($reportType === 'revenue_vat') {
                return Excel::download(new RevenueWithVatExport($fromDate, $toDate), "Revenue_Report_{$fromDate}_to_{$toDate}.xlsx");
            }elseif ($reportType === 'revenue_no_vat') {
                return Excel::download(new RevenueWithoutVatExport($fromDate, $toDate), "Revenue_Report_{$fromDate}_to_{$toDate}.xlsx");
            }elseif ($reportType === 'revenue_non_vat') {
                return Excel::download(new RevenueNonVatExport($fromDate, $toDate), "Revenue_Non_VAT_Report_{$fromDate}_to_{$toDate}.xlsx");
            }
        } catch (\Exception $e) {
            \Log::error('Report generation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate report. Please try again with a smaller date range.');
        }

        return back()->with('error', 'Invalid report type selected.');
    }

    private function availableReportTypes(): array
    {
        $types = [
            'expenses' => 'Expenses',
        ];

        if (auth()->check() && (int) auth()->user()->role_id === 1) {
            $types += [
                'revenue_non_vat' => 'Revenue with Non VAT (NARET Company)',
                'revenue_no_vat' => 'Revenue Without VAT',
                'revenue_vat' => 'Revenue with VAT',
                'revenue' => 'All Revenues',
            ];
        }

        return $types;
    }
}
