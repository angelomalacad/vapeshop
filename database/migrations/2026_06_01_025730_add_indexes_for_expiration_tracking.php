<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add index for faster expiration date queries
        Schema::table('branch_inventories', function (Blueprint $table) {
            $table->index('expiration_date');
        });
        
        Schema::table('warehouse_inventories', function (Blueprint $table) {
            $table->index('expiration_date');
        });
        
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->index('expiration_date');
        });
    }

    public function down(): void
    {
        Schema::table('branch_inventories', function (Blueprint $table) {
            $table->dropIndex(['expiration_date']);
        });
        
        Schema::table('warehouse_inventories', function (Blueprint $table) {
            $table->dropIndex(['expiration_date']);
        });
        
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropIndex(['expiration_date']);
        });
    }
};