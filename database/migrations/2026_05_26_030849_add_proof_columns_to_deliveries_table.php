<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('deliveries', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('delivery_proof');
            }
            if (!Schema::hasColumn('deliveries', 'driver_notes')) {
                $table->text('driver_notes')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('deliveries', 'driver_latitude')) {
                $table->decimal('driver_latitude', 10, 8)->nullable()->after('driver_notes');
            }
            if (!Schema::hasColumn('deliveries', 'driver_longitude')) {
                $table->decimal('driver_longitude', 11, 8)->nullable()->after('driver_latitude');
            }
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['payment_proof', 'driver_notes', 'driver_latitude', 'driver_longitude']);
        });
    }
};