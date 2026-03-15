<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Super Admin (owner)
        User::updateOrCreate(
            ['email' => 'carlocaranto@gmail.com'],
            [
                'name' => 'Carlo Caranto',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
                'phone' => '09603280432',
            ]
        );

        // Get all branches
        $branches = Branch::all();
        
        // Staff users based on the provided information
        $staffList = [
            [
                'name' => 'Karl Viscaino',
                'branch_code' => 'AS1',  // Asia 1 Branch
                'role' => 'branch_admin', // or 'staff' if they're just staff
            ],
            [
                'name' => 'Mhark Apoliga',
                'branch_code' => 'MCDC', // MCDC Branch
                'role' => 'branch_admin',
            ],
            [
                'name' => 'Rocky Ace',
                'branch_code' => 'MAJ',   // Majada Out Branch
                'role' => 'branch_admin',
            ],
            [
                'name' => 'Jeremy Abustan',
                'branch_code' => 'PAC',   // Paciano Branch
                'role' => 'branch_admin',
            ],
            [
                'name' => 'Rhe Ann Alqueza',
                'branch_code' => 'PAC2',  // Paciano V2 Branch
                'role' => 'branch_admin',
            ],
        ];

        // Create staff/branch admin accounts
        foreach ($staffList as $staff) {
            $branch = $branches->where('code', $staff['branch_code'])->first();
            
            if ($branch) {
                User::updateOrCreate(
                    ['email' => strtolower(str_replace(' ', '.', $staff['name'])) . '@gmail.com'],
                    [
                        'name' => $staff['name'],
                        'password' => Hash::make('password123'),
                        'role' => $staff['role'],
                        'branch_id' => $branch->id,
                        'phone' => '09603280432', // Owner's contact number
                        'email_verified_at' => now(),
                    ]
                );
            }
        }

        // Create additional staff users if needed (optional)
        $additionalStaff = [
            [
                'name' => 'Additional Staff 1',
                'branch_code' => 'AS1',
                'role' => 'staff',
            ],
            [
                'name' => 'Additional Staff 2',
                'branch_code' => 'MCDC',
                'role' => 'staff',
            ],
        ];

        foreach ($additionalStaff as $staff) {
            $branch = $branches->where('code', $staff['branch_code'])->first();
            
            if ($branch) {
                User::updateOrCreate(
                    ['email' => strtolower(str_replace(' ', '.', $staff['name'])) . '@gmail.com'],
                    [
                        'name' => $staff['name'],
                        'password' => Hash::make('password123'),
                        'role' => $staff['role'],
                        'branch_id' => $branch->id,
                        'phone' => '09603280432',
                        'email_verified_at' => now(),
                    ]
                );
            }
        }

        // Sample customers (Philippine format)
        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => 'customer' . $i . '@gmail.com'],
                [
                    'name' => 'Customer ' . $i,
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'phone' => '09' . rand(10, 99) . rand(1000000, 9999999),
                    'address' => 'Sample Address ' . $i . ', ' . 
                                 ['Calamba', 'Laguna', 'Canlubang', 'Makati'][array_rand(['Calamba', 'Laguna', 'Canlubang', 'Makati'])],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Vape Expo users seeded successfully!');
        $this->command->info('Super Admin: carlocaranto@gmail.com / password123');
        $this->command->info('Branch Staff: [name]@gmail.com / password123');
    }
}