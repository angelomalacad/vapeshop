<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // If it's currently ENUM, change to string
            $table->string('movement_type', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Revert back if needed
            $table->enum('movement_type', ['sale', 'return'])->default('sale')->change();
        });
    }
};
