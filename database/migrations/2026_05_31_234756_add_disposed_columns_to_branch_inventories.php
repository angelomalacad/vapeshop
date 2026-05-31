<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('branch_inventories', function (Blueprint $table) {
        $table->boolean('is_disposed')->default(false);
        $table->string('dispose_reason')->nullable();
        $table->timestamp('disposed_at')->nullable();
    });
}

public function down()
{
    Schema::table('branch_inventories', function (Blueprint $table) {
        $table->dropColumn(['is_disposed', 'dispose_reason', 'disposed_at']);
    });
}
};
