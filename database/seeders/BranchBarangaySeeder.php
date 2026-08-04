<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchBarangaySeeder extends Seeder
{
    public function run(): void
    {
        // Branch ID map: 1=Asia1, 2=MCDC, 3=Paciano, 4=PacianoV2, 5=MajadaOut
        $assignments = [
            1 => ['Canlubang', 'Majada In', 'Sirang Lupa'],
            2 => ['Burol', 'Palo alto', 'Laguerta'],
            3 => ['Paciano Rizal', 'Real', 'Halang', 'Banadero', 'Lingga', 'Parian', 'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5', 'Barangay 6', 'Banlic', 'Barangay 7', 'Bucal', 'Pansol', 'Lecheria', 'Looc', 'Uwisan'],
            4 => ['Mayapa', 'Turbina', 'Batino', 'Lawa', 'Bubuyan', 'Hornalan', 'Sampiruhan', 'Milagrosa', 'Palingon', 'Saimsim', 'San Cristobal', 'Barandal', 'Makiling', 'La Mesa', 'Maunong', 'Pittland', 'Masili', 'Sucol', 'Ulango'],
            5 => ['Majada Labas', 'Kay-Anlog', 'Punta', 'Bagong Kalsada', 'Prinza', 'Mabato', 'Puting Lupa', 'Bunggo', 'Camaligan', 'Mabacan', 'San Jose', 'Majada Out']
        ];

        $data = [];
        foreach ($assignments as $branchId => $barangays) {
            foreach ($barangays as $barangay) {
                $data[] = [
                    'branch_id' => $branchId,
                    'barangay_name' => trim($barangay),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('branch_barangay')->insert($data);
    }
}