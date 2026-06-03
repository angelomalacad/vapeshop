<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make customer_phone nullable
            $table->string('customer_phone', 20)->nullable()->change();
            
            // Also make customer_name nullable with default
            $table->string('customer_name', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_phone', 20)->nullable(false)->change();
            $table->string('customer_name', 255)->nullable(false)->change();
        });
    }
};