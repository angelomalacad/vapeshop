<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            CategorySeeder::class,    // Add this line
            AdminUserSeeder::class,
            ProductSeeder::class,
            InventorySeeder::class,
            DriverSeeder::class,
            WarehouseInventorySeeder::class,  // You can add this later
        ]);
    }
}
