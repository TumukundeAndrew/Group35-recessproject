<?php

namespace App\Http\Controllers;

use App\Models\Stakeholder;
use App\Models\Report;
use App\Mail\ReportToStakeholders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Mail\StakeholderReportMail;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Support\Facades\Validator;
use App\Services\ReportService;
use App\Mail\ReportMail;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->reportService = $reportService;
    }

    public function showSendForm()
    {
        if (request()->ajax()) {
            return view('admin.send-report-form');
        }
        return view('admin.send-report');
    }

    public function getStakeholdersByType($type)
    {
        $stakeholders = Stakeholder::where('type', $type)->get(['id', 'name', 'email']);
        return response()->json($stakeholders);
    }

    public function sendReport(Request $request)
    {
        $request->validate([
            'stakeholder_type' => 'required|string',
            'stakeholders' => 'required|array',
            'stakeholders.*' => 'exists:stakeholders,id',
            'message' => 'nullable|string',
            'include_analytics' => 'boolean',
            'include_summary' => 'boolean'
        ]);

        try {
            $stakeholders = Stakeholder::whereIn('id', $request->stakeholders)->get();
            $successCount = 0;
            $failCount = 0;

            foreach ($stakeholders as $stakeholder) {
                try {
                    // Generate report data
                    $reportData = [
                        'title' => 'Supply Chain Report for ' . $stakeholder->name,
                        'content' => $request->message ?? 'Please find attached your supply chain report.',
                        'analytics' => $request->include_analytics,
                        'summary' => $request->include_summary
                    ];

                    // Generate PDF
                    $pdf = PDF::loadView('reports.stakeholder', [
                        'stakeholder' => $stakeholder,
                        'report' => $reportData
                    ]);

                    // Save report record
                    $report = Report::create([
                        'user_id' => Auth::id(),
                        'stakeholder_id' => $stakeholder->id,
                        'report_type' => 'stakeholder_report',
                        'file_path' => 'reports/stakeholder_' . $stakeholder->id . '_' . time() . '.pdf',
                        'scheduled_date' => now(),
                        'status' => 'sent'
                    ]);

                    // Save PDF file
                    Storage::put('public/' . $report->file_path, $pdf->output());

                    // Send email
                    Mail::to($stakeholder->email)
                        ->send(new ReportToStakeholders($report));

                    $successCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send report to stakeholder: ' . $stakeholder->id . ' - ' . $e->getMessage());
                    $failCount++;
                }
            }

            $message = "Successfully sent reports to {$successCount} stakeholder(s)";
            if ($failCount > 0) {
                $message .= ". Failed to send to {$failCount} stakeholder(s).";
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Report sending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reports. Please try again.'
            ], 500);
        }
    }

    public function sendStakeholderReport($stakeholder_id)
    {
        $stakeholder = Stakeholder::with(['supplyChainReports'])->findOrFail($stakeholder_id);

        $pdfs = [];
        foreach ($stakeholder->supplyChainReports as $report) {
            $pdfs[] = $this->generateReport($stakeholder, $report);
        }

        $mergedPdf = $this->mergePdfs($pdfs);

        $data = [
            'subject' => 'Your Sun Seed Supply Chain Report',
            'stakeholder' => $stakeholder,
        ];

        Mail::to($stakeholder->email)->send(
            new StakeholderReportMail($data, $mergedPdf, 'sun_seed_supply_chain_report.pdf')
        );

        return redirect()->back()->with('success', 'Report sent to stakeholder successfully.');
    }

    public function downloadStakeholderReport($stakeholder_id = null)
    {
        try {
            $stakeholder = $stakeholder_id ? 
                Stakeholder::findOrFail($stakeholder_id) : 
                Stakeholder::first();

            $reportData = [
                'title' => 'Supply Chain Report',
                'content' => 'This is a generated report for ' . $stakeholder->name
            ];

            $pdf = PDF::loadView('reports.stakeholder', [
                'stakeholder' => $stakeholder,
                'report' => $reportData
            ]);

            return $pdf->download('stakeholder_report_' . $stakeholder->id . '.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to download report.');
        }
    }

    private function generateReport($stakeholder, $options = [])
    {
        $data = [
            'stakeholder' => $stakeholder,
            'report' => [
                'title' => 'Supply Chain Report for ' . $stakeholder->name,
                'content' => 'This is a detailed supply chain report for ' . $stakeholder->name . ' (' . $stakeholder->type . ').',
                'include_analytics' => $options['include_analytics'] ?? false,
                'include_summary' => $options['include_summary'] ?? true,
                'custom_message' => $options['custom_message'] ?? null
            ]
        ];

        $pdf = Pdf::loadView('reports.stakeholder', $data);
        return $pdf->output();
    }

    private function mergePdfs($pdfs)
    {
        $merger = new Merger;

        foreach ($pdfs as $pdf) {
            $merger->addRaw($pdf);
        }

        return $merger->merge();
    }

    public function sendReportEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'report_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);

        try {
            $file = $request->file('report_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('reports', $fileName, 'public');

            // Send email with attachment
            Mail::to($request->email)->send(new ReportMail(
                Storage::disk('public')->path('reports/' . $fileName)
            ));

            return response()->json([
                'success' => true,
                'message' => 'Report sent successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send report'
            ], 500);
        }
    }
}
