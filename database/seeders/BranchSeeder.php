<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $branches = [
            [
                'name' => 'VapeHub Main Branch',
                'code' => 'VH001',
                'address' => '123 Vape Street, Makati City',
                'phone' => '0912 345 6789',
                'email' => 'main@vapehub.com',
                'manager_name' => 'John Smith',
                'latitude' => 14.554729,
                'longitude' => 121.024445,
                'is_active' => true,
            ],
            [
                'name' => 'VapeHub North Branch',
                'code' => 'VH002',
                'address' => '456 North Ave, Quezon City',
                'phone' => '0913 456 7890',
                'email' => 'north@vapehub.com',
                'manager_name' => 'Maria Garcia',
                'latitude' => 14.676041,
                'longitude' => 121.043701,
                'is_active' => true,
            ],
            [
                'name' => 'VapeHub South Branch',
                'code' => 'VH003',
                'address' => '789 South Road, Taguig City',
                'phone' => '0914 567 8901',
                'email' => 'south@vapehub.com',
                'manager_name' => 'Robert Lim',
                'latitude' => 14.517618,
                'longitude' => 121.050864,
                'is_active' => true,
            ],
            [
                'name' => 'VapeHub East Branch',
                'code' => 'VH004',
                'address' => '321 East Drive, Pasig City',
                'phone' => '0915 678 9012',
                'email' => 'east@vapehub.com',
                'manager_name' => 'Lisa Tan',
                'latitude' => 14.576377,
                'longitude' => 121.085110,
                'is_active' => true,
            ],
            [
                'name' => 'VapeHub West Branch',
                'code' => 'VH005',
                'address' => '654 West Blvd, Manila City',
                'phone' => '0916 789 0123',
                'email' => 'west@vapehub.com',
                'manager_name' => 'David Chen',
                'latitude' => 14.599512,
                'longitude' => 120.984222,
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}