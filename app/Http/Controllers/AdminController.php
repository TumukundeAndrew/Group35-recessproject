<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Stakeholder;
use App\Models\ReportSchedule;
use App\Models\ReportHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function workforce()
    {
        $workers = Worker::latest()->paginate(10);
        return view('admin.workforce', compact('workers'));
    }

    public function download()
    {
        return view('admin.download');
    }

    public function reports()
    {
        $recentReports = ReportHistory::with('schedule')
            ->latest()
            ->take(5)
            ->get();

        $stats = (object)[
            'total' => ReportHistory::count(),
            'success_rate' => ReportHistory::where('status', 'sent')->count() * 100 / max(ReportHistory::count(), 1) . '%',
            'active_schedules' => ReportSchedule::where('is_active', true)->count()
        ];

        return view('admin.reports', compact('recentReports', 'stats'));
    }

    public function reportSchedules()
    {
        $reportSchedules = ReportSchedule::with('stakeholders')->get();
        $stakeholders = Stakeholder::active()->get();
        return view('admin.reports.index', compact('reportSchedules', 'stakeholders'));
    }

    public function storeReport(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:inventory,logistics,financial',
            'frequency' => 'required|in:daily,weekly,monthly',
            'scheduled_time' => 'required|date_format:H:i',
            'stakeholders' => 'required|array|min:1',
            'stakeholders.*' => 'exists:stakeholders,id'
        ]);

        DB::beginTransaction();
        try {
            $reportSchedule = ReportSchedule::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'type' => $validated['type'],
                'frequency' => $validated['frequency'],
                'scheduled_time' => $validated['scheduled_time'],
                'is_active' => true
            ]);

            // Attach stakeholders with default customizations
            foreach ($validated['stakeholders'] as $stakeholderId) {
                $stakeholder = Stakeholder::find($stakeholderId);
                $customizations = $this->getDefaultCustomizations($validated['type'], $stakeholder->type);
                $reportSchedule->stakeholders()->attach($stakeholderId, [
                    'customizations' => json_encode($customizations)
                ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleReportStatus(Request $request, ReportSchedule $report)
    {
        $report->update(['is_active' => $request->is_active]);
        return response()->json(['success' => true]);
    }

    public function deleteReport(ReportSchedule $report)
    {
        $report->delete();
        return response()->json(['success' => true]);
    }

    public function reportHistory()
    {
        $reports = ReportHistory::with(['schedule', 'stakeholder'])
            ->latest()
            ->paginate(15);
        
        return view('admin.reports.history', compact('reports'));
    }

    public function reportTemplates()
    {
        $templates = [
            'inventory' => [
                'name' => 'Inventory Report',
                'description' => 'Standard inventory status and movement report',
                'formats' => ['PDF', 'Excel', 'HTML']
            ],
            'logistics' => [
                'name' => 'Logistics Report',
                'description' => 'Delivery and shipment tracking report',
                'formats' => ['PDF', 'Excel']
            ],
            'financial' => [
                'name' => 'Financial Report',
                'description' => 'Financial performance and metrics report',
                'formats' => ['PDF', 'Excel']
            ]
        ];
        
        return view('admin.reports.templates', compact('templates'));
    }

    private function getDefaultCustomizations(string $reportType, string $stakeholderType): array
    {
        $customizations = [
            'inventory' => [
                'supplier' => [
                    'show_supplier_specific' => true,
                    'include_demand_forecast' => true
                ],
                'distributor' => [
                    'show_regional_data' => true,
                    'include_stock_alerts' => true
                ]
            ],
            'logistics' => [
                'distributor' => [
                    'show_delivery_schedule' => true,
                    'include_route_optimization' => true
                ]
            ],
            'financial' => [
                'sales' => [
                    'show_revenue_breakdown' => true,
                    'include_sales_targets' => true
                ]
            ]
        ];

        return $customizations[$reportType][$stakeholderType] ?? [];
    }
} 