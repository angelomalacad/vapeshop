<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            // Make from_branch_id nullable (for warehouse transfers)
            $table->unsignedBigInteger('from_branch_id')->nullable()->change();
            
            // Add transfer_type column if it doesn't exist
            if (!Schema::hasColumn('stock_transfers', 'transfer_type')) {
                $table->enum('transfer_type', ['branch_to_branch', 'warehouse_to_branch'])->default('branch_to_branch')->after('status');
            }
            
            // Add warehouse_id column if it doesn't exist
            if (!Schema::hasColumn('stock_transfers', 'warehouse_id')) {
                $table->foreignId('warehouse_id')->nullable()->after('transfer_type');
            }
        });
    }

    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->unsignedBigInteger('from_branch_id')->nullable(false)->change();
            $table->dropColumn(['transfer_type', 'warehouse_id']);
        });
    }
};