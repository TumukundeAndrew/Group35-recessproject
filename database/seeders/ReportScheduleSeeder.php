<?php

namespace Database\Seeders;

use App\Models\ReportSchedule;
use App\Models\Stakeholder;
use Illuminate\Database\Seeder;

class ReportScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'name' => 'Daily Inventory Report',
                'description' => 'Daily summary of inventory levels and movements',
                'frequency' => 'daily',
                'scheduled_time' => '08:00',
                'type' => 'inventory',
                'is_active' => true,
                'stakeholder_types' => ['supplier', 'distributor']
            ],
            [
                'name' => 'Weekly Logistics Report',
                'description' => 'Weekly overview of shipments and deliveries',
                'frequency' => 'weekly',
                'scheduled_time' => '09:00',
                'type' => 'logistics',
                'is_active' => true,
                'stakeholder_types' => ['distributor']
            ],
            [
                'name' => 'Monthly Financial Report',
                'description' => 'Monthly financial summary and analysis',
                'frequency' => 'monthly',
                'scheduled_time' => '07:00',
                'type' => 'financial',
                'is_active' => true,
                'stakeholder_types' => ['sales']
            ]
        ];

        foreach ($schedules as $schedule) {
            $stakeholderTypes = $schedule['stakeholder_types'];
            unset($schedule['stakeholder_types']);
            
            $reportSchedule = ReportSchedule::create($schedule);
            
            // Attach stakeholders based on their type
            $stakeholders = Stakeholder::whereIn('type', $stakeholderTypes)->get();
            foreach ($stakeholders as $stakeholder) {
                $customizations = $this->getCustomizations($schedule['type'], $stakeholder->type);
                $reportSchedule->stakeholders()->attach($stakeholder->id, [
                    'customizations' => json_encode($customizations)
                ]);
            }
        }
    }

    private function getCustomizations(string $reportType, string $stakeholderType): array
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