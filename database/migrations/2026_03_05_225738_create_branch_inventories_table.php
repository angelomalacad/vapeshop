<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('branch_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('flavor_id')->nullable()->constrained('product_flavors')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('reserved_quantity')->default(0); // for pending orders
            $table->integer('low_stock_threshold')->default(10);
            $table->integer('reorder_point')->default(20);
            $table->integer('optimal_stock')->default(50);
            $table->decimal('last_purchase_price', 10, 2)->nullable();
            $table->timestamp('last_restocked_at')->nullable();
            $table->timestamps();
            
            $table->unique(['branch_id', 'product_id', 'flavor_id'], 'branch_product_flavor_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('branch_inventories');
    }
};