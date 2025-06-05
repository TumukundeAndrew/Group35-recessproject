<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupplyChainReport;
use App\Models\Stakeholder;

class SupplyChainReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stakeholder = Stakeholder::first();

        if ($stakeholder) {
            SupplyChainReport::create([
                'stakeholder_id' => $stakeholder->id,
                'title' => 'Monthly Supply Chain Overview',
                'content' => 'This is a sample supply chain report content.',
                'status' => 'draft'
            ]);

            SupplyChainReport::create([
                'stakeholder_id' => $stakeholder->id,
                'title' => 'Quarterly Performance Report',
                'content' => 'This is another sample supply chain report content.',
                'status' => 'draft'
            ]);
        }
    }
} 