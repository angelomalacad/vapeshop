<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: First, temporarily allow NULL values for both columns
        DB::statement('ALTER TABLE orders MODIFY status VARCHAR(50) NULL');
        DB::statement('ALTER TABLE orders MODIFY order_status VARCHAR(50) NULL');
        
        // Step 2: Fix POS orders (order_number starts with 'POS')
        DB::table('orders')
            ->where('order_number', 'LIKE', 'POS-%')
            ->update([
                'order_status' => null,
                'confirmed_at' => null,
                'processing_at' => null,
                'ready_at' => null,
                'out_for_delivery_at' => null,
                'delivered_at' => null,
            ]);
        
        // Step 3: Fix Online orders (order_number starts with 'ORD')
        DB::table('orders')
            ->where('order_number', 'LIKE', 'ORD-%')
            ->update(['status' => null]);
        
        // Step 4: Update POS status 'delivered' to 'completed' for consistency
        DB::table('orders')
            ->where('order_number', 'LIKE', 'POS-%')
            ->where('status', 'delivered')
            ->update(['status' => 'completed']);
        
        // Step 5: Ensure any POS orders with NULL status get default 'pending'
        DB::table('orders')
            ->where('order_number', 'LIKE', 'POS-%')
            ->whereNull('status')
            ->update(['status' => 'pending']);
        
        // Step 6: Ensure any Online orders with NULL order_status get default 'pending'
        DB::table('orders')
            ->where('order_number', 'LIKE', 'ORD-%')
            ->whereNull('order_status')
            ->update(['order_status' => 'pending']);
        
        // Step 7: Modify status column with proper enum (allow NULL for online orders)
        DB::statement("ALTER TABLE orders MODIFY status ENUM('pending', 'processing', 'completed', 'cancelled', 'refunded') NULL DEFAULT NULL");
        
        // Step 8: Modify order_status column with proper enum (allow NULL for POS orders)
        DB::statement("ALTER TABLE orders MODIFY order_status ENUM('pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') NULL DEFAULT NULL");
        
        // Step 9: Add comments to columns for clarity
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'cancelled', 'refunded') NULL DEFAULT NULL COMMENT 'POS/Walk-in order status only. NULL for online orders'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled') NULL DEFAULT NULL COMMENT 'Online order workflow status only. NULL for POS orders'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original structure (VARCHAR, NOT NULL)
        DB::statement('ALTER TABLE orders MODIFY status VARCHAR(50) NOT NULL DEFAULT "pending"');
        DB::statement('ALTER TABLE orders MODIFY order_status VARCHAR(50) NOT NULL DEFAULT "pending"');
    }
};