<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite (used by tests) doesn't support MODIFY COLUMN or ENUM.
        // The base users migration already created `role` as a string,
        // and other migrations guard their role additions with hasColumn().
        // So for SQLite we just ensure the column exists and move on.
        if (DB::getDriverName() === 'sqlite') {
            if (! Schema::hasColumn('users', 'role')) {
                Schema::table('users', function ($table) {
                    $table->string('role')->default('customer');
                });
            }
            return;
        }

        // MySQL / MariaDB (used by production / Hostinger):
        // extend the ENUM to include 'driver'.
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'branch_admin', 'staff', 'customer', 'driver') NOT NULL DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Revert to original ENUM values (without 'driver').
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'branch_admin', 'staff', 'customer') NOT NULL DEFAULT 'customer'");
    }
};