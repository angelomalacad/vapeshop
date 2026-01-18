<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // First, check if the default users table exists
        if (!Schema::hasTable('users')) {
            // Create users table if it doesn't exist
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Now add the additional columns
        Schema::table('users', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['super_admin', 'branch_admin', 'staff', 'customer'])
                      ->default('customer')
                      ->after('password');
            }
            
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')
                      ->nullable()
                      ->after('role')
                      ->constrained('branches')
                      ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('branch_id');
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'receive_notifications')) {
                $table->boolean('receive_notifications')->default(true)->after('address');
            }
            
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->string('fcm_token')->nullable()->after('receive_notifications');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove columns if they exist
            if (Schema::hasColumn('users', 'branch_id')) {
                $table->dropForeign(['branch_id']);
            }
            
            $columnsToDrop = ['role', 'branch_id', 'phone', 'address', 'receive_notifications', 'fcm_token'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};