<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First convert to VARCHAR to preserve data
        DB::statement("ALTER TABLE stock_movements MODIFY movement_type VARCHAR(50) DEFAULT 'adjustment'");
        
        // Then convert back to ENUM with all values including warehouse types
        DB::statement("ALTER TABLE stock_movements MODIFY movement_type ENUM(
            'purchase',
            'sale',
            'transfer_out',
            'transfer_in',
            'adjustment',
            'initial',
            'damaged',
            'expired',
            'return',
            'warehouse_transfer_in',
            'warehouse_transfer_out',
            'warehouse_receive',
            'warehouse_send'
        ) DEFAULT 'adjustment' NOT NULL");
    }

    public function down()
    {
        // Convert to VARCHAR first
        DB::statement("ALTER TABLE stock_movements MODIFY movement_type VARCHAR(50) DEFAULT 'adjustment'");
        
        // Revert to original ENUM without warehouse types
        DB::statement("ALTER TABLE stock_movements MODIFY movement_type ENUM(
            'purchase',
            'sale',
            'transfer_out',
            'transfer_in',
            'adjustment',
            'initial',
            'damaged',
            'expired',
            'return'
        ) DEFAULT 'adjustment' NOT NULL");
    }
};