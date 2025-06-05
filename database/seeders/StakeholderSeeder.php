<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Stakeholder;
use Illuminate\Support\Facades\DB;

class StakeholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stakeholders = [
            [
                'name' => 'Acme Suppliers Ltd',
                'email' => 'contact@acmesuppliers.com',
                'type' => 'supplier',
                'contact_person' => 'John Doe',
                'phone' => '+1-555-0123',
                'address' => '123 Supply Street, Industrial Zone',
                'is_active' => true
            ],
            [
                'name' => 'Global Distributors Inc',
                'email' => 'operations@globaldist.com',
                'type' => 'distributor',
                'contact_person' => 'Jane Smith',
                'phone' => '+1-555-0124',
                'address' => '456 Distribution Ave, Logistics Park',
                'is_active' => true
            ],
            [
                'name' => 'Regional Sales Co',
                'email' => 'sales@regionalsales.com',
                'type' => 'sales',
                'contact_person' => 'Bob Wilson',
                'phone' => '+1-555-0125',
                'address' => '789 Sales Blvd, Commercial District',
                'is_active' => true
            ]
        ];

        foreach ($stakeholders as $stakeholder) {
            Stakeholder::create($stakeholder);
        }
    }
}
