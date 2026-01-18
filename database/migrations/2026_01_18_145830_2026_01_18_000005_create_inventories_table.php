<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->integer('optimal_stock_level')->default(20);
            $table->integer('reserved_quantity')->default(0);
            $table->decimal('last_purchase_price', 10, 2)->nullable();
            $table->date('last_restocked_at')->nullable();
            $table->timestamps();
            
            $table->unique(['branch_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};