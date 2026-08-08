<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('flavor_id')->nullable()->after('product_id');
            
            // Optional: Add foreign key if you want strict database integrity
            // $table->foreign('flavor_id')->references('id')->on('product_flavors')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('flavor_id');
        });
    }
};