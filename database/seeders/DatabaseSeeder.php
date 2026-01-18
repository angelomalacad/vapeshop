<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            BranchSeeder::class,
            CategorySeeder::class,
            // ProductSeeder::class, // We'll create this next
            AdminUserSeeder::class,
        ]);
    }
}