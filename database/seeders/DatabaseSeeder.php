<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user first
        $this->call(AdminUserSeeder::class);

        // Create stakeholders
        $this->call(StakeholderSeeder::class);

        // Create report schedules and attach stakeholders
        $this->call(ReportScheduleSeeder::class);
    }
}



