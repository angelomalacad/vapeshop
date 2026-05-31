<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add missing columns to stock_transfers
        Schema::table('stock_transfers', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_transfers', 'received_by')) {
                $table->foreignId('received_by')
                    ->nullable()
                    ->after('approved_at')
                    ->constrained('users')
                    ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('stock_transfers', 'received_at')) {
                $table->timestamp('received_at')
                    ->nullable()
                    ->after('received_by');
            }
            
            if (!Schema::hasColumn('stock_transfers', 'expiration_date')) {
                $table->date('expiration_date')
                    ->nullable()
                    ->after('notes');
            }
        });
        
        // 2. Update stock_movements ENUM to include 'receive'
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN movement_type ENUM('purchase', 'sale', 'transfer_out', 'transfer_in', 'receive', 'return', 'adjustment', 'damaged', 'expired') NOT NULL");
        
        // 3. Add columns to branch_inventories
        Schema::table('branch_inventories', function (Blueprint $table) {
            if (!Schema::hasColumn('branch_inventories', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('quantity');
            }
            
            if (!Schema::hasColumn('branch_inventories', 'flavor_id')) {
                $table->foreignId('flavor_id')
                    ->nullable()
                    ->after('product_id')
                    ->constrained('product_flavors')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        // 1. Drop columns from stock_transfers
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropForeign(['received_by']);
            $table->dropColumn(['received_by', 'received_at', 'expiration_date']);
        });
        
        // 2. Revert stock_movements ENUM (remove 'receive')
        DB::statement("ALTER TABLE stock_movements MODIFY COLUMN movement_type ENUM('purchase', 'sale', 'transfer_out', 'transfer_in', 'return', 'adjustment', 'damaged', 'expired') NOT NULL");
        
        // 3. Drop columns from branch_inventories
        Schema::table('branch_inventories', function (Blueprint $table) {
            $table->dropColumn(['expiration_date', 'flavor_id']);
        });
    }
};