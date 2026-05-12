<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->integer('reorder_point')->default(20);
            $table->decimal('last_purchase_price', 10, 2)->nullable();
            $table->date('last_restocked_at')->nullable();
            $table->timestamps();
        });
        
        // Add warehouse_request_id to stock_transfers table
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->enum('transfer_type', ['branch_to_branch', 'warehouse_to_branch'])->default('branch_to_branch')->after('status');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouse_inventories')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn(['transfer_type', 'warehouse_id']);
        });
        Schema::dropIfExists('warehouse_inventories');
    }
};