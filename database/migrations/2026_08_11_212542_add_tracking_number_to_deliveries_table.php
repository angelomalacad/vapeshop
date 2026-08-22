<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            // If you haven't added 'delivery_proof' yet, add it too:
            if (!Schema::hasColumn('deliveries', 'tracking_number')) {
                $table->string('tracking_number')->nullable()->after('driver_id');
            }
            if (!Schema::hasColumn('deliveries', 'delivery_proof')) {
                $table->string('delivery_proof')->nullable()->after('tracking_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'delivery_proof']);
        });
    }
};