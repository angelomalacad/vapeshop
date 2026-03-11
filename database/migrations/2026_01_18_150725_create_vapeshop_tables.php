<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Create branches table FIRST
        if (!Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('address');
                $table->string('phone');
                $table->string('email');
                $table->string('manager_name');
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            echo "Created branches table\n";
        }

        // 2. Add columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['super_admin', 'branch_admin', 'staff', 'customer'])
                      ->default('customer')
                      ->after('password');
                echo "Added role column to users\n";
            }
            
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->foreignId('branch_id')
                      ->nullable()
                      ->after('role')
                      ->constrained('branches')
                      ->onDelete('set null');
                echo "Added branch_id column to users\n";
            }
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('branch_id');
                echo "Added phone column to users\n";
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
                echo "Added address column to users\n";
            }
            
            if (!Schema::hasColumn('users', 'receive_notifications')) {
                $table->boolean('receive_notifications')->default(true)->after('address');
                echo "Added receive_notifications column to users\n";
            }
            
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->string('fcm_token')->nullable()->after('receive_notifications');
                echo "Added fcm_token column to users\n";
            }
        });

        // 3. Create categories table
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade');
                $table->integer('order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            echo "Created categories table\n";
        }

        // 4. Create products table
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('sku')->unique();
                $table->text('description')->nullable();
                $table->foreignId('category_id')->constrained()->onDelete('cascade');
                $table->string('brand')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('cost_price', 10, 2)->nullable();
                $table->string('image')->nullable();
                $table->json('images')->nullable();
                $table->enum('type', ['disposable', 'pod', 'mod', 'liquid', 'coil', 'accessory']);
                $table->string('flavor')->nullable();
                $table->integer('nicotine_strength')->nullable();
                $table->integer('puff_count')->nullable();
                $table->string('battery_capacity')->nullable();
                $table->text('specifications')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
            echo "Created products table\n";
        }

        // 5. Create inventories table
        if (!Schema::hasTable('inventories')) {
            Schema::create('inventories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity')->default(0);
                $table->integer('low_stock_threshold')->default(5);
                $table->integer('optimal_stock_level')->default(20);
                $table->integer('reserved_quantity')->default(0);
                $table->decimal('last_purchase_price', 10, 2)->nullable();
                $table->date('last_restocked_at')->nullable();
                $table->timestamps();
                
                $table->unique(['branch_id', 'product_id']);
            });
            echo "Created inventories table\n";
        }

        // 6. Create orders table
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->decimal('subtotal', 10, 2);
                $table->decimal('tax', 10, 2)->default(0);
                $table->decimal('delivery_fee', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);
                $table->enum('status', [
                    'pending', 
                    'confirmed', 
                    'processing', 
                    'ready_for_pickup',
                    'out_for_delivery',
                    'delivered', 
                    'cancelled'
                ])->default('pending');
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
                $table->enum('payment_method', ['cash', 'gcash', 'paymaya', 'card', 'bank_transfer'])->default('cash');
                $table->enum('delivery_type', ['pickup', 'delivery'])->default('pickup');
                $table->text('delivery_address')->nullable();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->text('notes')->nullable();
                $table->timestamp('estimated_delivery_time')->nullable();
                $table->timestamps();
            });
            echo "Created orders table\n";
        }

        // 7. Create order_items table
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity');
                $table->decimal('price', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->timestamps();
            });
            echo "Created order_items table\n";
        }

        // 8. Create deliveries table
        if (!Schema::hasTable('deliveries')) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('tracking_number')->unique();
                $table->enum('status', ['pending', 'assigned', 'picked_up', 'in_transit', 'delivered', 'failed'])->default('pending');
                $table->text('delivery_address');
                $table->string('recipient_name');
                $table->string('recipient_phone');
                $table->timestamp('estimated_arrival')->nullable();
                $table->timestamp('delivered_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
            echo "Created deliveries table\n";
        }

        // 9. Create stock_alerts table
        if (!Schema::hasTable('stock_alerts')) {
            Schema::create('stock_alerts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inventory_id')->constrained()->onDelete('cascade');
                $table->foreignId('branch_id')->constrained()->onDelete('cascade');
                $table->enum('alert_type', ['low_stock', 'out_of_stock', 'restock']);
                $table->integer('current_quantity');
                $table->integer('threshold_quantity');
                $table->boolean('is_resolved')->default(false);
                $table->timestamp('resolved_at')->nullable();
                $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
            echo "Created stock_alerts table\n";
        }
    }

    public function down()
    {
        // Drop tables in reverse order
        Schema::dropIfExists('stock_alerts');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        
        // Remove columns from users table
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = ['fcm_token', 'receive_notifications', 'address', 'phone', 'branch_id', 'role'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    if ($column === 'branch_id') {
                        $table->dropForeign(['branch_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
        
        Schema::dropIfExists('branches');
    }
};