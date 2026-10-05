<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN or ENUM.
        // It stores ENUM columns as plain text, so there's nothing to change.
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // MySQL / MariaDB: extend the ENUM to include 'cod'.
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'card', 'bank_transfer', 'cod') NOT NULL DEFAULT 'cash'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        // Revert to original ENUM values (without 'cod').
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'card', 'bank_transfer') NOT NULL DEFAULT 'cash'");
    }
};