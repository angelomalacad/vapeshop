<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'province')) {
                $table->string('province')->nullable()->after('barangay');
            }
            if (!Schema::hasColumn('orders', 'other_barangay')) {
                $table->string('other_barangay')->nullable()->after('barangay');
            }
            if (!Schema::hasColumn('orders', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('province');
            }
            if (!Schema::hasColumn('orders', 'landmark')) {
                $table->string('landmark')->nullable()->after('zip_code');
            }
            if (!Schema::hasColumn('orders', 'delivery_date')) {
                $table->date('delivery_date')->nullable()->after('delivery_address');
            }
            if (!Schema::hasColumn('orders', 'gcash_reference')) {
                $table->string('gcash_reference')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('gcash_reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'province',
                'other_barangay',
                'zip_code',
                'landmark',
                'delivery_date',
                'gcash_reference',
                'notes',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
