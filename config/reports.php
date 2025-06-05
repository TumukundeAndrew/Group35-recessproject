<?php

return [
    'reports' => [
        'inventory' => \App\Reports\InventoryReport::class,
        'logistics' => \App\Reports\LogisticsReport::class,
        'financial' => \App\Reports\FinancialReport::class,
    ],
    
    'stakeholder_types' => [
        'supplier' => [
            'reports' => ['inventory', 'logistics'],
            'customizations' => [
                'show_supplier_specific' => true,
                'include_demand_forecast' => true,
            ]
        ],
        'distributor' => [
            'reports' => ['inventory', 'financial'],
            'customizations' => [
                'show_regional_data' => true,
                'include_sales_forecast' => true,
            ]
        ],
    ]
]; 