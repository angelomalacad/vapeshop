<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignDriverToBranchSeeder extends Seeder
{
    public function run()
    {
        // First, check if drivers exist
        $drivers = User::where('role', 'driver')->get();
        
        if ($drivers->isEmpty()) {
            $this->command->error('No drivers found! Please run DriverSeeder first.');
            $this->command->info('Run: php artisan db:seed --class=DriverSeeder');
            return;
        }
        
        $this->command->info('Found ' . $drivers->count() . ' drivers.');
        
        // Assignments: branch_code => driver email
        $assignments = [
            'AS1' => 'karl.viscaino@driver.com',   // Asia 1 Branch
            'MCDC' => 'mhark.apoliga@driver.com',  // MCDC Branch
            'MAJ' => 'rocky.ace@driver.com',       // Majada Out Branch
            'PAC' => 'jeremy.abustan@driver.com',  // Paciano Branch
            'PAC2' => 'rhe.ann.alqueza@driver.com', // Paciano V2 Branch
        ];
        
        foreach ($assignments as $code => $email) {
            $branch = Branch::where('code', $code)->first();
            $driver = User::where('email', $email)->where('role', 'driver')->first();
            
            if (!$branch) {
                $this->command->error("Branch not found for code: {$code}");
                continue;
            }
            
            if (!$driver) {
                $this->command->error("Driver not found for email: {$email}");
                continue;
            }
            
            // Update branch with assigned driver
            $branch->update(['assigned_driver_id' => $driver->id]);
            $this->command->info("✓ Driver '{$driver->name}' assigned to '{$branch->name}'");
        }
        
        // Verify assignments
        $this->command->info("\nVerifying assignments...");
        $branches = Branch::whereNotNull('assigned_driver_id')->with('assignedDriver')->get();
        
        if ($branches->isEmpty()) {
            $this->command->error('No assignments were made!');
        } else {
            foreach ($branches as $branch) {
                $this->command->line("  - {$branch->name} → Driver: {$branch->assignedDriver->name} ({$branch->assignedDriver->email})");
            }
        }
    }
}