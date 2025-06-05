<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Sunflower Oil',
                'description' => 'High-quality refined sunflower oil',
                'price' => 15000, // Price in UGX
                'stock_quantity' => 1000,
                'sku' => 'SFO-001',
                'category' => 'finished_product'
            ],
            [
                'name' => 'Sunflower Seeds',
                'description' => 'Raw sunflower seeds for oil production',
                'price' => 8000, // Price in UGX
                'stock_quantity' => 2000,
                'sku' => 'SFS-001',
                'category' => 'raw_material'
            ]
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']], // Find by SKU
                $product // Update or create with these values
            );
        }
    }
} 