<?php

namespace App\Reports;

use App\Models\Sales;
use App\Models\Expense;
use App\Models\Invoice;

class FinancialReport extends BaseReport
{
    public function generate($stakeholder = null)
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        
        $data = [
            'monthly_sales' => Sales::whereBetween('date', [$startDate, $endDate])
                ->selectRaw('SUM(revenue) as total_revenue, SUM(volume) as total_volume')
                ->first(),
            'outstanding_invoices' => Invoice::where('status', 'pending')
                ->orderBy('due_date')
                ->get(),
            'monthly_expenses' => Expense::whereBetween('date', [$startDate, $endDate])
                ->selectRaw('SUM(amount) as total_amount, category')
                ->groupBy('category')
                ->get()
        ];
        
        if ($stakeholder) {
            return $this->formatForStakeholder($data, $stakeholder);
        }
        
        return $data;
    }
    
    public function formatForStakeholder($data, $stakeholder)
    {
        $type = $stakeholder->type;
        
        switch ($type) {
            case 'distributor':
                // Only show financial data relevant to this distributor's region
                $regionId = $stakeholder->region_id;
                
                $data['monthly_sales'] = Sales::where('region_id', $regionId)
                    ->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])
                    ->selectRaw('SUM(revenue) as total_revenue, SUM(volume) as total_volume')
                    ->first();
                    
                $data['outstanding_invoices'] = $data['outstanding_invoices']
                    ->where('region_id', $regionId);
                break;
        }
        
        return $data;
    }
} 