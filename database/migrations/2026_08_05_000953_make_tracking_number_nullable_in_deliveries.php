<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Change tracking_number to nullable
            $table->string('tracking_number')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // Revert back to not nullable
            $table->string('tracking_number')->nullable(false)->change();
        });
    }
};