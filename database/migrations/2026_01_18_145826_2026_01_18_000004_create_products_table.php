<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('brand')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->enum('type', ['disposable', 'pod', 'mod', 'liquid', 'coil', 'accessory']);
            $table->string('flavor')->nullable();
            $table->integer('nicotine_strength')->nullable();
            $table->integer('puff_count')->nullable();
            $table->string('battery_capacity')->nullable();
            $table->text('specifications')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};