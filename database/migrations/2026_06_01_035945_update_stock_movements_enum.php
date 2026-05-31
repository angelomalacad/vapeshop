<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add 'receive' to the ENUM if not already there
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN movement_type ENUM('purchase', 'sale', 'transfer_out', 'transfer_in', 'receive', 'return', 'adjustment', 'damaged', 'expired') NOT NULL");
    }

    public function down(): void
    {
        // Revert to original ENUM
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN movement_type ENUM('purchase', 'sale', 'transfer_out', 'transfer_in', 'return', 'adjustment', 'damaged', 'expired') NOT NULL");
    }
};