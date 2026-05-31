<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            // Add received_at column if it doesn't exist
            if (!Schema::hasColumn('stock_transfers', 'received_at')) {
                $table->timestamp('received_at')->nullable()->after('approved_at');
            }
            
            // Add received_by column if it doesn't exist
            if (!Schema::hasColumn('stock_transfers', 'received_by')) {
                $table->foreignId('received_by')->nullable()->after('received_at')->constrained('users');
            }
            
            // Add expiration_date column if it doesn't exist
            if (!Schema::hasColumn('stock_transfers', 'expiration_date')) {
                $table->date('expiration_date')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropColumn(['received_at', 'received_by', 'expiration_date']);
        });
    }
};