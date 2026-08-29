<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_reservations', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->unsignedBigInteger('branch_inventory_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('stock_transfer_id')->nullable();
            $table->unsignedBigInteger('user_id');
            
            // Reservation details
            $table->integer('quantity');
            $table->enum('reservation_type', ['online_order', 'stock_transfer', 'pickup']);
            $table->enum('status', ['active', 'released', 'converted', 'expired', 'cancelled'])->default('active');
            
            // Timestamps for tracking
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            
            // Additional information
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('branch_inventory_id')
                  ->references('id')
                  ->on('branch_inventories')
                  ->onDelete('cascade');
                  
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('set null');
                  
            $table->foreign('stock_transfer_id')
                  ->references('id')
                  ->on('stock_transfers')
                  ->onDelete('set null');
                  
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            // Indexes for better performance
            $table->index(['status', 'expires_at'], 'idx_status_expires');
            $table->index(['branch_inventory_id', 'status'], 'idx_inventory_status');
            $table->index(['order_id', 'status'], 'idx_order_status');
            $table->index(['stock_transfer_id', 'status'], 'idx_transfer_status');
            $table->index(['reservation_type', 'status'], 'idx_type_status');
            $table->index('user_id', 'idx_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_reservations');
    }
};