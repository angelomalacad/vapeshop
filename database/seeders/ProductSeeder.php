<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categoryDisposable = Category::where('slug', 'disposable-vapes')->first();
        $categoryPod = Category::where('slug', 'pod-systems')->first();

        $products = [
            [
                'name' => 'X Ultra',
                'sku' => 'X-ULTRA-001',
                'description' => 'High-performance disposable vape.',
                'category_id' => $categoryDisposable->id ?? 1,
                'price' => 250.00,
                'type' => 'disposable',
                'flavor' => 'Mixed',
                'puff_count' => 800,
            ],
            [
                'name' => 'Slimbar',
                'sku' => 'SLIM-001',
                'description' => 'Sleek pod system.',
                'category_id' => $categoryPod->id ?? 2,
                'price' => 350.00,
                'type' => 'pod',
                'flavor' => 'Various',
            ],
            [
                'name' => 'Relx',
                'sku' => 'RELX-001',
                'description' => 'Popular pod system.',
                'category_id' => $categoryPod->id ?? 2,
                'price' => 400.00,
                'type' => 'pod',
                'flavor' => 'Various',
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData + ['is_active' => true]
            );
        }

        $this->command->info('Products seeded.');
    }
}