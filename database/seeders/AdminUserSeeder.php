<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin (no branch assigned)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@vapehub.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Get all branches
        $branches = \App\Models\Branch::all();
        
        // Create branch admin for each branch
        foreach ($branches as $branch) {
            User::create([
                'name' => $branch->manager_name,
                'email' => 'admin' . $branch->code . '@vapehub.com',
                'password' => Hash::make('password123'),
                'role' => 'branch_admin',
                'branch_id' => $branch->id,
                'phone' => $branch->phone,
                'email_verified_at' => now(),
            ]);
        }

        // Sample staff users
        for ($i = 1; $i <= 10; $i++) {
            $branch = $branches->random();
            User::create([
                'name' => 'Staff User ' . $i,
                'email' => 'staff' . $i . '@vapehub.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'branch_id' => $branch->id,
                'phone' => '0917' . rand(1000000, 9999999),
                'email_verified_at' => now(),
            ]);
        }

        // Sample customers
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Customer ' . $i,
                'email' => 'customer' . $i . '@example.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'phone' => '0918' . rand(1000000, 9999999),
                'address' => 'Sample Address ' . $i . ', City',
                'email_verified_at' => now(),
            ]);
        }
    }
}