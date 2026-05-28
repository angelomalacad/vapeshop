<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (!Schema::hasColumn('deliveries', 'driver_shift_id')) {
                $table->foreignId('driver_shift_id')->nullable()->after('driver_id')->constrained('driver_shifts')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            if (Schema::hasColumn('deliveries', 'driver_shift_id')) {
                $table->dropForeign(['driver_shift_id']);
                $table->dropColumn('driver_shift_id');
            }
        });
    }
};