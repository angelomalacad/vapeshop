<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;

// Authentication Routes
Auth::routes(['verify' => true]);

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Customer Routes
Route::middleware(['auth', 'verified', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('/products', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [App\Http\Controllers\Customer\ProductController::class, 'show'])->name('products.show');
    Route::get('/products/branch/{branch}', [App\Http\Controllers\Customer\ProductController::class, 'byBranch'])->name('products.byBranch');
    
    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\CartController::class, 'index'])->name('index');
        Route::post('/add', [App\Http\Controllers\Customer\CartController::class, 'add'])->name('add');
        Route::put('/update/{id}', [App\Http\Controllers\Customer\CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [App\Http\Controllers\Customer\CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [App\Http\Controllers\Customer\CartController::class, 'clear'])->name('clear');
    });
    
    // Orders
    Route::resource('orders', App\Http\Controllers\Customer\OrderController::class);
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/track', [App\Http\Controllers\Customer\OrderController::class, 'track'])->name('orders.track');
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Customer\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\Customer\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Admin Routes (Super Admin)
Route::middleware(['auth', 'verified', 'super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Branches Management
    Route::resource('branches', App\Http\Controllers\Admin\BranchController::class);
    
    // Products Management
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::post('/products/import', [App\Http\Controllers\Admin\ProductController::class, 'import'])->name('products.import');
    Route::get('/products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('products.export');
    
    // Inventory Management
    Route::resource('inventory', App\Http\Controllers\Admin\InventoryController::class)->except(['create', 'edit']);
    Route::post('/inventory/bulk-update', [App\Http\Controllers\Admin\InventoryController::class, 'bulkUpdate'])->name('inventory.bulk-update');
    Route::get('/inventory/low-stock', [App\Http\Controllers\Admin\InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/inventory/stock-history', [App\Http\Controllers\Admin\InventoryController::class, 'stockHistory'])->name('inventory.history');
    
    // Orders Management
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::post('/orders/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/assign-driver', [App\Http\Controllers\Admin\OrderController::class, 'assignDriver'])->name('orders.assign-driver');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::get('/sales', [App\Http\Controllers\Admin\ReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('inventory');
        Route::get('/branches', [App\Http\Controllers\Admin\ReportController::class, 'branches'])->name('branches');
        Route::get('/customers', [App\Http\Controllers\Admin\ReportController::class, 'customers'])->name('customers');
        Route::post('/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('export');
    });
    
    // Stock Alerts
    Route::get('/alerts', [App\Http\Controllers\Admin\NotificationController::class, 'stockAlerts'])->name('alerts.stock');
    Route::post('/alerts/{alert}/resolve', [App\Http\Controllers\Admin\NotificationController::class, 'resolveAlert'])->name('alerts.resolve');
});

// Branch Admin Routes
Route::middleware(['auth', 'verified', 'branch_admin'])->prefix('branch-admin')->name('branch-admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\BranchAdmin\DashboardController::class, 'index'])->name('dashboard');
    // ... branch-specific routes
});

// API Routes for real-time updates
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::get('/branches', [App\Http\Controllers\API\BranchController::class, 'index']);
    Route::get('/products/{branch}/available', [App\Http\Controllers\API\ProductController::class, 'availableProducts']);
    Route::get('/inventory/{branch}/levels', [App\Http\Controllers\API\ProductController::class, 'inventoryLevels']);
    Route::get('/orders/{order}/status', [App\Http\Controllers\API\OrderController::class, 'status']);
    Route::post('/notifications/subscribe', [App\Http\Controllers\API\NotificationController::class, 'subscribe']);
});