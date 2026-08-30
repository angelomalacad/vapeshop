<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, convert null values to 'pending'
        DB::table('orders')
            ->whereNull('order_status')
            ->update(['order_status' => 'pending']);
        
        // Now change the column to VARCHAR
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_status', 50)->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_status', 20)->default('pending')->change();
        });
    }
};