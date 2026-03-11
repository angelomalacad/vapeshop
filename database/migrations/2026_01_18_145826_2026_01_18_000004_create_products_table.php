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
            $table->text('description');
            $table->string('brand')->default('X-Vape');
            $table->string('category'); // Ultra, Slimbar, Relx
            $table->string('type'); // pod-system, disposable, etc.
            $table->decimal('price', 10, 2);
            $table->decimal('cost', 10, 2)->nullable();
            $table->integer('puff_count')->nullable(); // for Ultra
            $table->integer('battery_capacity')->nullable(); // in mAh
            $table->string('charging_type')->nullable(); // Type-C, etc.
            $table->integer('liquid_capacity')->nullable(); // in ml
            $table->string('nicotine_strength')->nullable(); // 10mg, etc.
            $table->boolean('adjustable_airflow')->default(false);
            $table->boolean('smart_display')->default(false);
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};