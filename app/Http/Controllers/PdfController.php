<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PdfController extends Controller
{
    // 1. Show form to enter report name
    public function showReportForm()
    {
        return view('admin.report_form');
    }

    // 2. Handle form submission, generate PDF, save to storage and DB
    public function generateStaticPDF(Request $request)
    {
        $request->validate([
            'report_name' => 'required|string|max:255',
        ]);

        $reportName = $request->input('report_name');

        // Load PDF content
        $pdf = Pdf::loadView('admin.trial');

        // Define file path
        $fileName = $reportName . '_' . time() . '.pdf';
        $filePath = 'reports/' . $fileName;

        // Save PDF to storage
        Storage::put('public/' . $filePath, $pdf->output());

        // Save report to database
        $report = Report::create([
            'user_id' => Auth::id() ?? 1, // fallback to 1 for testing
            'report_type' => 'static',
            'file_path' => 'storage/' . $filePath,
            'scheduled_date' => Carbon::now(),
            'status' => 'sent',
        ]);

        return redirect()->route('admin.reports')
            ->with('success', "Report '{$reportName}' has been generated successfully.")
            ->with('latest_report_id', $report->id);
    }

    // 3. Download the most recently generated report
    public function download()
    {
        // $report = Report::latest()->first();

        $pdf = Pdf::loadView('admin.trial');
         return $pdf->download('Report.pdf');
        // return respsonse()->download(storage_path('app/public/' . str_replace('storage/', '', $report->file_path)));
    }

    // 4. View all sent reports
    public function viewSentReports()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('admin.reports_list', compact('reports'));
    }

    public function reportsTable()
    {
        $reports = \App\Models\Report::orderBy('created_at', 'desc')->get();
        return view('admin.partials.reports_table', compact('reports'))->render();
    }
}











// -- <?php// namespace App\Http\Controllers;

// use Barryvdh\DomPDF\Facade\Pdf;

// class PdfController extends Controller
// {
//    public function generateStaticPDF()
// {
//     $pdf = Pdf::loadView('admin.trial');
//     return $pdf->download('static_report.pdf');
// }
// }
   
    /**
     * Generate a static PDF report.
    //  *
    // //  * @return \Illuminate\Http\Response
    //  */



