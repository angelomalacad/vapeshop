<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add more product details
            if (!Schema::hasColumn('products', 'weight')) {
                $table->decimal('weight', 8, 2)->nullable()->after('puff_count');
            }
            
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->string('dimensions')->nullable()->after('weight'); // e.g., "100x50x20mm"
            }
            
            if (!Schema::hasColumn('products', 'manufacturer')) {
                $table->string('manufacturer')->nullable()->after('dimensions');
            }
            
            if (!Schema::hasColumn('products', 'warranty_period')) {
                $table->string('warranty_period')->nullable()->after('manufacturer'); // e.g., "6 months"
            }
            
            if (!Schema::hasColumn('products', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('warranty_period'); // For consumables
            }
            
            if (!Schema::hasColumn('products', 'reorder_point')) {
                $table->integer('reorder_point')->default(10)->after('expiry_date');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['weight', 'dimensions', 'manufacturer', 'warranty_period', 'expiry_date', 'reorder_point'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};