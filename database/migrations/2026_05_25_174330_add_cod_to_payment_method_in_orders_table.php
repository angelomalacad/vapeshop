<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // MySQL syntax to modify ENUM
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'card', 'bank_transfer', 'cod') NOT NULL DEFAULT 'cash'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'card', 'bank_transfer') NOT NULL DEFAULT 'cash'");
    }
};