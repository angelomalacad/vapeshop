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
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('flavor_id')->nullable()->constrained('product_flavors')->onDelete('cascade');
            $table->integer('previous_quantity');
            $table->integer('new_quantity');
            $table->integer('quantity_change');
            $table->enum('movement_type', [
                'purchase', 'sale', 'transfer_out', 'transfer_in', 
                'return', 'adjustment', 'damaged', 'expired'
            ]);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index(['branch_id', 'product_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};