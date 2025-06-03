<?php

namespace App\Reports;

use App\Models\Inventory;
use App\Models\DemandForecast;

class InventoryReport extends BaseReport
{
    public function generate($stakeholder = null)
    {
        $data = [
            'current_inventory' => Inventory::latest()->get(),
            'projected_demand' => DemandForecast::where('date', '>', now())
                ->where('date', '<=', now()->addDays(30))
                ->get(),
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
            case 'supplier':
                // Only show inventory levels relevant to this supplier
                $data['current_inventory'] = $data['current_inventory']
                    ->where('supplier_id', $stakeholder->supplier_id);
                break;
                
            case 'distributor':
                // Add regional sales data if available
                if (method_exists('App\Models\Sales', 'byRegion')) {
                    $data['regional_sales'] = \App\Models\Sales::byRegion($stakeholder->region)->get();
                }
                break;
        }
        
        return $data;
    }
} 