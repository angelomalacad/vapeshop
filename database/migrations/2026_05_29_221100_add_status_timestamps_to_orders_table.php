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
            // Add timestamp columns for each order status
            $table->timestamp('confirmed_at')->nullable()->after('order_status');
            $table->timestamp('processing_at')->nullable()->after('confirmed_at');
            $table->timestamp('ready_at')->nullable()->after('processing_at');
            $table->timestamp('out_for_delivery_at')->nullable()->after('ready_at');
            $table->timestamp('delivered_at')->nullable()->after('out_for_delivery_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'confirmed_at',
                'processing_at',
                'ready_at',
                'out_for_delivery_at',
                'delivered_at'
            ]);
        });
    }
};
