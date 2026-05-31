<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('branch_inventories', function (Blueprint $table) {
            // Add flavor_id column if it doesn't exist
            if (!Schema::hasColumn('branch_inventories', 'flavor_id')) {
                $table->foreignId('flavor_id')->nullable()->after('product_id')->constrained('product_flavors');
            }
            
            // Add expiration_date column if it doesn't exist
            if (!Schema::hasColumn('branch_inventories', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('branch_inventories', function (Blueprint $table) {
            $table->dropColumn(['flavor_id', 'expiration_date']);
        });
    }
};