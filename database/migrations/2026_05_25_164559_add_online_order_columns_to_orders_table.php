<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Customer information (only if not exists)
            if (!Schema::hasColumn('orders', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('orders', 'customer_phone')) {
                $table->string('customer_phone')->nullable()->after('customer_email');
            }
            if (!Schema::hasColumn('orders', 'delivery_address')) {
                $table->text('delivery_address')->nullable()->after('customer_phone');
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable()->after('delivery_address');
            }
            if (!Schema::hasColumn('orders', 'barangay')) {
                $table->string('barangay')->nullable()->after('city');
            }
            if (!Schema::hasColumn('orders', 'landmark')) {
                $table->string('landmark')->nullable()->after('barangay');
            }
            
            // Delivery type and payment (if not exists)
            if (!Schema::hasColumn('orders', 'delivery_type')) {
                $table->enum('delivery_type', ['pickup', 'delivery'])->default('pickup')->after('landmark');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['cod', 'gcash'])->default('cod')->after('delivery_type');
            }
            if (!Schema::hasColumn('orders', 'gcash_reference')) {
                $table->string('gcash_reference')->nullable()->after('payment_method');
            }
            
            // Add order_status column (do not alter existing status column)
            if (!Schema::hasColumn('orders', 'order_status')) {
                $table->enum('order_status', [
                    'pending', 'confirmed', 'processing', 'ready', 'out_for_delivery', 'delivered', 'cancelled'
                ])->default('pending')->after('status');
            }
            
            // Additional fields
            if (!Schema::hasColumn('orders', 'estimated_delivery_time')) {
                $table->timestamp('estimated_delivery_time')->nullable()->after('order_status');
            }
            if (!Schema::hasColumn('orders', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('estimated_delivery_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop only the columns that were added by this migration
            $columns = [
                'customer_name', 'customer_email', 'customer_phone', 'delivery_address',
                'city', 'barangay', 'landmark', 'delivery_type', 'payment_method',
                'gcash_reference', 'order_status', 'estimated_delivery_time', 'admin_notes'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};