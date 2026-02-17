<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role and Branch Assignment
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
            
            // Contact Information
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('branch_id');
            }
            
            if (!Schema::hasColumn('users', 'alternate_phone')) {
                $table->string('alternate_phone')->nullable()->after('phone');
            }
            
            // Address Information
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('alternate_phone');
            }
            
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('users', 'province')) {
                $table->string('province')->nullable()->after('city');
            }
            
            if (!Schema::hasColumn('users', 'zip_code')) {
                $table->string('zip_code')->nullable()->after('province');
            }
            
            // Personal Information
            if (!Schema::hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->nullable()->after('zip_code');
            }
            
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])
                      ->nullable()
                      ->after('birthdate');
            }
            
            // Notification Preferences
            if (!Schema::hasColumn('users', 'receive_notifications')) {
                $table->boolean('receive_notifications')->default(true)->after('gender');
            }
            
            if (!Schema::hasColumn('users', 'receive_promotions')) {
                $table->boolean('receive_promotions')->default(true)->after('receive_notifications');
            }
            
            // Account Status
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('receive_promotions');
            }
            
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable()->after('last_login_at');
            }
            
            // For push notifications (future use)
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->string('fcm_token')->nullable()->after('last_login_ip');
            }
            
            // For email verification
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                // Already exists in default migration
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'role',
                'branch_id',
                'phone',
                'alternate_phone',
                'address',
                'city',
                'province',
                'zip_code',
                'birthdate',
                'gender',
                'receive_notifications',
                'receive_promotions',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'fcm_token'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    if ($column === 'branch_id') {
                        $table->dropForeign(['branch_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};