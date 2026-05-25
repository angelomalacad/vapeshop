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
        Schema::table('deliveries', function (Blueprint $table) {
            // Add foreign key to orders (only if not exists)
            if (!Schema::hasColumn('deliveries', 'order_id')) {
                $table->foreignId('order_id')->after('id')->constrained()->onDelete('cascade');
            } else {
                // Ensure foreign key constraint exists (if needed)
                // You may skip or add a check
            }
            
            // Driver relationship
            if (!Schema::hasColumn('deliveries', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->after('order_id')->constrained('users')->onDelete('set null');
            }
            
            // Tracking information
            if (!Schema::hasColumn('deliveries', 'tracking_number')) {
                $table->string('tracking_number')->unique()->after('driver_id');
            } else {
                // Ensure unique constraint?
            }
            
            // Delivery status
            if (!Schema::hasColumn('deliveries', 'status')) {
                $table->enum('status', ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed'])
                      ->default('pending')->after('tracking_number');
            }
            
            // Address and recipient details
            if (!Schema::hasColumn('deliveries', 'delivery_address')) {
                $table->text('delivery_address')->after('status');
            }
            if (!Schema::hasColumn('deliveries', 'recipient_name')) {
                $table->string('recipient_name')->after('delivery_address');
            }
            if (!Schema::hasColumn('deliveries', 'recipient_phone')) {
                $table->string('recipient_phone')->after('recipient_name');
            }
            
            // GPS coordinates
            if (!Schema::hasColumn('deliveries', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('recipient_phone');
            }
            if (!Schema::hasColumn('deliveries', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            
            // Timestamps for key events
            if (!Schema::hasColumn('deliveries', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('longitude');
            }
            if (!Schema::hasColumn('deliveries', 'picked_up_at')) {
                $table->timestamp('picked_up_at')->nullable()->after('assigned_at');
            }
            if (!Schema::hasColumn('deliveries', 'delivered_at')) {
                $table->timestamp('delivered_at')->nullable()->after('picked_up_at');
            }
            
            // Proof of delivery
            if (!Schema::hasColumn('deliveries', 'delivery_proof')) {
                $table->text('delivery_proof')->nullable()->after('delivered_at');
            }
            
            // Additional notes
            if (!Schema::hasColumn('deliveries', 'notes')) {
                $table->text('notes')->nullable()->after('delivery_proof');
            }
        });
        
        // If order_id column already exists but foreign key missing, we can add it conditionally
        // (optional) For MySQL: 
        // DB::statement('ALTER TABLE deliveries ADD CONSTRAINT deliveries_order_id_foreign FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['order_id']);
            $table->dropForeign(['driver_id']);
            
            // Drop added columns (only if they exist)
            $columns = [
                'order_id', 'driver_id', 'tracking_number', 'status',
                'delivery_address', 'recipient_name', 'recipient_phone',
                'latitude', 'longitude', 'assigned_at', 'picked_up_at',
                'delivered_at', 'delivery_proof', 'notes'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('deliveries', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};