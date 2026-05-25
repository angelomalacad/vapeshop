<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run()
    {
        // Drivers based on branch admin/staff from AdminUserSeeder
        $drivers = [
            [
                'name' => 'Karl Viscaino',
                'email' => 'karl.viscaino@driver.com',
                'phone' => '09603280432',
            ],
            [
                'name' => 'Mhark Apoliga',
                'email' => 'mhark.apoliga@driver.com',
                'phone' => '09603280432',
            ],
            [
                'name' => 'Rocky Ace',
                'email' => 'rocky.ace@driver.com',
                'phone' => '09603280432',
            ],
            [
                'name' => 'Jeremy Abustan',
                'email' => 'jeremy.abustan@driver.com',
                'phone' => '09603280432',
            ],
            [
                'name' => 'Rhe Ann Alqueza',
                'email' => 'rhe.ann.alqueza@driver.com',
                'phone' => '09603280432',
            ],
            // Additional staff as drivers
            [
                'name' => 'Additional Staff 1',
                'email' => 'additional.staff1@driver.com',
                'phone' => '09603280432',
            ],
            [
                'name' => 'Additional Staff 2',
                'email' => 'additional.staff2@driver.com',
                'phone' => '09603280432',
            ],
        ];

        foreach ($drivers as $driver) {
            User::updateOrCreate(
                ['email' => $driver['email']],
                [
                    'name' => $driver['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'driver',
                    'phone' => $driver['phone'],
                    'branch_id' => null, // Drivers are independent
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Drivers seeded successfully!');
        $this->command->info('Driver accounts: [firstname.lastname]@driver.com / password123');
    }
}