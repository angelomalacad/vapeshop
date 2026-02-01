<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('old_quantity');
            $table->integer('new_quantity');
            $table->integer('quantity_change'); // positive for additions, negative for deductions
            $table->enum('movement_type', [
                'initial', 
                'purchase', 
                'sale', 
                'return',
                'adjustment',
                'damaged',
                'expired',
                'transfer_in',
                'transfer_out'
            ]);
            $table->string('reference_number')->nullable(); // For linking to orders, transfers, etc.
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['branch_id', 'product_id']);
            $table->index('movement_type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};