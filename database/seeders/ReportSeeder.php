<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Report;
use App\Models\Stakeholder;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::all();
        $stakeholders = Stakeholder::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please seed users first.');
            return;
        }

        // Report 1 - Sales report assigned to a random user and stakeholder
        Report::create([
            'user_id' => $users->random()->id,
            'stakeholder_id' => $stakeholders->isNotEmpty() ? $stakeholders->random()->id : null,
            'report_type' => 'sales',
            'file_path' => 'reports/sales_q1.pdf', // Simulated file path
            'scheduled_date' => Carbon::now()->addDays(3)->toDateString(),
            'status' => 'pending',
        ]);

        // Report 2 - Inventory report assigned to another random user and no stakeholder
        Report::create([
            'user_id' => $users->random()->id,
            'stakeholder_id' => null,
            'report_type' => 'inventory',
            'file_path' => 'reports/inventory_march.pdf',
            'scheduled_date' => Carbon::now()->addDays(7)->toDateString(),
            'status' => 'sent',
        ]);
    }
}
