<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update all warehouse transfers (from_branch_id is NULL) to have the correct transfer_type
        DB::table('stock_transfers')
            ->whereNull('from_branch_id')
            ->whereNotNull('to_branch_id')
            ->update(['transfer_type' => 'warehouse_to_branch']);
    }

    public function down(): void
    {
        // Revert - set transfer_type back to NULL for warehouse transfers
        DB::table('stock_transfers')
            ->where('transfer_type', 'warehouse_to_branch')
            ->whereNull('from_branch_id')
            ->update(['transfer_type' => null]);
    }
};