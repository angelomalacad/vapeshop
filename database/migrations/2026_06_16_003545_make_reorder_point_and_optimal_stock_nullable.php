<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('branch_inventories', function (Blueprint $table) {
        $table->integer('reorder_point')->nullable()->change();
        $table->integer('optimal_stock')->nullable()->change();
    });
}
};
