<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'sku')) {
            return;
        }

        // SQLite refuses to drop a column that still has an index on it.
        // MySQL auto-drops the index when the column goes away.
        // So on SQLite we drop the unique index first, then the column.
        if (DB::getDriverName() === 'sqlite') {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->dropUnique(['sku']);
                });
            } catch (\Throwable $e) {
                // Index was already dropped or never existed — safe to ignore.
            }
        }

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->unique()->nullable();
        });
    }
};