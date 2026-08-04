<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Change branch_id to nullable
            $table->unsignedBigInteger('branch_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Revert back to not nullable (WARNING: This will fail if you have existing orders with null branch_id)
            $table->unsignedBigInteger('branch_id')->nullable(false)->change();
        });
    }
};