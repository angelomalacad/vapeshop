<?php
// app/Console/Commands/CheckLowStock.php

namespace App\Console\Commands;

use App\Models\BranchInventory;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckLowStock extends Command
{
    protected $signature = 'inventory:check-low-stock';
    protected $description = 'Check for low stock items and send notifications';

    public function handle()
    {
        $this->info('Checking for low stock items...');
        
        $lowStockItems = BranchInventory::with(['branch', 'product', 'flavor'])
            ->lowStock()
            ->get();
        
        if ($lowStockItems->isEmpty()) {
            $this->info('No low stock items found.');
            return;
        }
        
        $this->info('Found ' . $lowStockItems->count() . ' low stock items.');
        
        // Group by branch
        $itemsByBranch = $lowStockItems->groupBy('branch_id');
        
        foreach ($itemsByBranch as $branchId => $items) {
            // Get branch staff to notify
            $staff = User::where('branch_id', $branchId)
                ->whereIn('role', ['branch_admin', 'staff'])
                ->get();
            
            // Also notify super admin
            $superAdmin = User::where('role', 'super_admin')->get();
            
            $recipients = $staff->merge($superAdmin);
            
            foreach ($items as $item) {
                Notification::send($recipients, new LowStockNotification($item));
                $this->line('Notification sent for ' . $item->product->name . ' at ' . $item->branch->name);
            }
        }
        
        $this->info('Low stock notifications sent successfully.');
    }
}