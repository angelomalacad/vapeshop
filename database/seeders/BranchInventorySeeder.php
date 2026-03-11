<?php
// database/seeders/BranchInventorySeeder.php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductFlavor;
use App\Models\BranchInventory;
use Illuminate\Database\Seeder;

class BranchInventorySeeder extends Seeder
{
    public function run()
    {
        $branches = Branch::all();
        $products = Product::with('flavors')->get();

        foreach ($branches as $branch) {
            foreach ($products as $product) {
                // If product has flavors, create inventory for each flavor
                if ($product->flavors->count() > 0) {
                    foreach ($product->flavors as $flavor) {
                        BranchInventory::create([
                            'branch_id' => $branch->id,
                            'product_id' => $product->id,
                            'flavor_id' => $flavor->id,
                            'quantity' => rand(20, 100), // Random initial stock
                            'reserved_quantity' => 0,
                            'low_stock_threshold' => 10,
                            'reorder_point' => 20,
                            'optimal_stock' => 50,
                            'last_restocked_at' => now(),
                        ]);
                    }
                } else {
                    // For products without flavors
                    BranchInventory::create([
                        'branch_id' => $branch->id,
                        'product_id' => $product->id,
                        'flavor_id' => null,
                        'quantity' => rand(20, 50),
                        'reserved_quantity' => 0,
                        'low_stock_threshold' => 5,
                        'reorder_point' => 10,
                        'optimal_stock' => 30,
                        'last_restocked_at' => now(),
                    ]);
                }
            }
        }

        $this->command->info('Branch inventories seeded successfully!');
    }
}