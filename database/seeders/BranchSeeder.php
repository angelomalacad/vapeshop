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
                'name' => 'Majada Out Branch',
                'code' => 'MAJ',
                'address' => 'EFG Building, Majada Out Road',
                'phone' => '09603280432',           // owner's contact
                'email' => 'majada@vapeexpo.com',   // you can create emails
                'manager_name' => 'Rocky Ace',
                'opening_date' => '2024-01-01',      // approximate – owner unsure
                'is_active' => true,
            ],
            [
                'name' => 'Asia 1 Branch',
                'code' => 'AS1',
                'address' => 'Blk 67 Lot 1, Canlubang, Calamba, Laguna',
                'phone' => '09603280432',
                'email' => 'asia1@vapeexpo.com',
                'manager_name' => 'Karl Viscaino',
                'opening_date' => '2024-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'MCDC Branch',
                'code' => 'MCDC',
                'address' => 'Blk 1 Lot 10, Canlubang, Calamba, Laguna',
                'phone' => '09603280432',
                'email' => 'mcdc@vapeexpo.com',
                'manager_name' => 'Mhark Apoliga',
                'opening_date' => '2024-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'Paciano Branch',
                'code' => 'PAC',
                'address' => '215 National Road, Brgy. Paciano Rizal, Calamba City',
                'phone' => '09603280432',
                'email' => 'paciano@vapeexpo.com',
                'manager_name' => 'Jeremy Abustan',
                'opening_date' => '2024-01-01',
                'is_active' => true,
            ],
            [
                'name' => 'Paciano V2 Branch',
                'code' => 'PAC2',
                'address' => '39 Mayapa, Canlubang Cadre Road, Calamba',
                'phone' => '09603280432',
                'email' => 'paciano2@vapeexpo.com',
                'manager_name' => 'Rhe Ann Alqueza',
                'opening_date' => '2024-01-01',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['code' => $branch['code']],
                $branch
            );
        }

        $this->command->info('Branches seeded with Vape Expo data.');
    }
}