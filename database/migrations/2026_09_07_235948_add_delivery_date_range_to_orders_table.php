<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add delivery_date_from and delivery_date_to columns
            $table->date('delivery_date_from')->nullable()->after('delivery_date');
            $table->date('delivery_date_to')->nullable()->after('delivery_date_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the columns if migration is rolled back
            $table->dropColumn(['delivery_date_from', 'delivery_date_to']);
        });
    }
};