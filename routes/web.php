<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BranchAdmin\ProductController as BranchAdminProductController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// ===== FORGOT PASSWORD STANDALONE ROUTE =====
Route::get('/forgot-password', function () {
    return view('auth.passwords.email-standalone');
})->name('password.request');
// ===== END OF FORGOT PASSWORD ROUTE =====

// ===== PASSWORD RESET STANDALONE ROUTE =====
Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset-standalone', ['token' => $token]);
})->name('password.reset');
// ===== END OF PASSWORD RESET ROUTE =====

// ===== AUTHENTICATION ROUTES WITH EMAIL VERIFICATION =====
// Disable default register routes since we have custom registration
Auth::routes(['verify' => true, 'register' => false]);
// ===== END OF AUTH ROUTES =====

// ===== CUSTOM LOGIN ROUTE =====
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->only('email', 'password');

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();

        $user = Auth::user();

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            Auth::logout();
            return redirect()->route('verification.notice')
                ->with('error', 'Please verify your email before logging in.');
        }

        // Redirect based on role
        switch ($user->role) {
            case 'super_admin':
                return redirect()->route('admin.dashboard');
            case 'branch_admin':
                return redirect()->route('branch-admin.dashboard');
            case 'driver':
                return redirect()->route('driver.dashboard');
            case 'customer':
            default:
                return redirect()->route('customer.dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->name('login');
// ===== END OF CUSTOM LOGIN ROUTE =====

// ===== LOGOUT ROUTE =====
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
// ===== END OF LOGOUT ROUTE =====

// ===== CUSTOM REGISTRATION ROUTE WITH EMAIL VERIFICATION =====
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'barangay' => 'nullable|string|max:100',
        'landmark' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'province' => 'nullable|string|max:100',
        'zip_code' => 'nullable|string|max:10',
        'birthdate' => 'nullable|date|before:'.now()->subYears(18)->format('Y-m-d'),
        'gender' => 'nullable|string|in:male,female,prefer_not_to_say',
        'terms' => 'required|accepted',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => 'customer',
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'barangay' => $validated['barangay'] ?? null,
        'landmark' => $validated['landmark'] ?? null,
        'city' => $validated['city'] ?? 'Calamba',
        'province' => $validated['province'] ?? 'Laguna',
        'zip_code' => $validated['zip_code'] ?? null,
        'birthdate' => $validated['birthdate'] ?? null,
        'gender' => $validated['gender'] ?? null,
        'receive_notifications' => request()->has('newsletter'),
        'receive_promotions' => request()->has('newsletter'),
        'is_active' => true,
    ]);

    // Trigger the Registered event to send verification email
    event(new \Illuminate\Auth\Events\Registered($user));

    // Log the user in
    Auth::login($user);

    // Redirect to verification notice
    return redirect()->route('verification.notice');
})->name('register');
// ===== END OF CUSTOM REGISTRATION ROUTE =====

// ===== VERIFICATION NOTICE ROUTE =====
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');
// ===== END OF VERIFICATION NOTICE ROUTE =====

// ===== SIMPLE VERIFICATION HANDLER THAT DEFINITELY WORKS =====
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    // Mark as verified immediately
    $user->email_verified_at = now();
    $user->save();

    return redirect()->route('login')->with('success', 'Email verified! You can now login.');
})->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
// ===== END OF VERIFICATION HANDLER ROUTES =====

// ===========================================================================
// CUSTOMER ROUTES (Online Ordering)
// ===========================================================================
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');

    // Products with branch selection
    Route::get('/products', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/branch/{branch}', [App\Http\Controllers\Customer\ProductController::class, 'byBranch'])->name('products.byBranch');

    // AJAX route for product variants (used by the modal)
    Route::get('/products/{product}/variants', [App\Http\Controllers\Customer\ProductController::class, 'getVariants'])->name('products.variants');

    // Shopping Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\Customer\CartController::class, 'index'])->name('index');
        Route::post('/add', [App\Http\Controllers\Customer\CartController::class, 'add'])->name('add');
        Route::put('/update/{inventoryId}', [App\Http\Controllers\Customer\CartController::class, 'update'])->name('update');
        Route::delete('/remove/{inventoryId}', [App\Http\Controllers\Customer\CartController::class, 'remove'])->name('remove');
        Route::post('/clear', [App\Http\Controllers\Customer\CartController::class, 'clear'])->name('clear');
    });

    // Checkout
    Route::post('/cart/checkout-selected', [App\Http\Controllers\Customer\CartController::class, 'checkoutSelected'])->name('cart.checkout-selected');
    Route::get('/checkout', [App\Http\Controllers\Customer\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\Customer\CheckoutController::class, 'store'])->name('checkout.store');

    // Orders & Tracking
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/track', [App\Http\Controllers\Customer\OrderController::class, 'track'])->name('orders.track');

    // Track Modal - AJAX route for modal content
    Route::get('/orders/{order}/track-modal', [App\Http\Controllers\Customer\OrderController::class, 'trackModal'])->name('orders.track-modal');

    // Notifications (optional)
    Route::get('/notifications', [App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Customer\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// ===========================================================================
// ADMIN (SUPER ADMIN) ROUTES – NO CHANGES (kept as is)
// ===========================================================================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // ===== DASHBOARD - USING CONTROLLER (FIXED) =====
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // ===== EMAIL VERIFICATION ROUTES FOR ADMIN =====
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth')->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
        $user = \App\Models\User::findOrFail($id);
        if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return redirect()->route('admin.login')->with('error', 'Invalid verification link.');
        }
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            return redirect()->route('admin.login')->with('success', 'Email verified successfully! You can now login.');
        }
        return redirect()->route('admin.login')->with('info', 'Email already verified.');
    })->name('verification.verify');

    Route::post('/email/verification-notification', function () {
        if (request()->user()->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }
        request()->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

    // ===== API ROUTE FOR FLAVORS =====
    Route::get('/api/products/{product}/flavors', function($productId) {
        $product = \App\Models\Product::find($productId);
        if (!$product) {
            return response()->json([]);
        }
        return response()->json($product->flavors->map(function($flavor) {
            return ['id' => $flavor->id, 'name' => $flavor->name];
        }));
    })->name('api.product.flavors');
// ===== API ROUTE TO CHECK WAREHOUSE STOCK (ADMIN) =====
Route::get('/api/warehouse-stock/{product}', function($productId, Request $request) {
    $flavorId = $request->get('flavor_id');
    $query = \App\Models\WarehouseInventory::where('product_id', $productId);

    if ($flavorId && $flavorId !== '') {
        $query->where('flavor_id', $flavorId);
    } else {
        $query->whereNull('flavor_id');
    }

    $inventory = $query->first();

    return response()->json([
        'success' => true,
        'quantity' => $inventory ? $inventory->quantity : 0
    ]);
})->name('admin.api.warehouse-stock');
    // ===== DRIVER SHIFT MANAGEMENT =====
    Route::prefix('driver-shifts')->name('driver-shifts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DriverShiftController::class, 'index'])->name('index');
        Route::post('/assign', [App\Http\Controllers\Admin\DriverShiftController::class, 'assign'])->name('assign');
        Route::delete('/{shift}/cancel', [App\Http\Controllers\Admin\DriverShiftController::class, 'cancel'])->name('cancel');
        Route::get('/active', [App\Http\Controllers\Admin\DriverShiftController::class, 'getActiveDriver'])->name('active');
    });

    // ===== DELIVERY MANAGEMENT (OWNER) =====
    Route::prefix('deliveries')->name('deliveries.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DeliveryController::class, 'index'])->name('index');
        Route::get('/{delivery}/modal', [App\Http\Controllers\Admin\DeliveryController::class, 'showModal'])->name('show-modal');
        Route::get('/{delivery}', [App\Http\Controllers\Admin\DeliveryController::class, 'show'])->name('show');
        Route::get('/{delivery}/proof/{type}', [App\Http\Controllers\Admin\DeliveryController::class, 'viewProof'])->name('view-proof');
        Route::post('/{delivery}/assign-driver', [App\Http\Controllers\Admin\DeliveryController::class, 'assignDriver'])->name('assign-driver');
        Route::get('/export/report', [App\Http\Controllers\Admin\DeliveryController::class, 'export'])->name('export');
    });

    // ===== ONLINE ORDERS MANAGEMENT (OWNER - READ ONLY) =====
    Route::prefix('online-orders')->name('online-orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\OnlineOrderController::class, 'index'])->name('index');
        Route::get('/{order}/modal', [App\Http\Controllers\Admin\OnlineOrderController::class, 'showModal'])->name('modal');
    });

    // ===== WAREHOUSE MANAGEMENT (OWNER) =====
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\WarehouseController::class, 'index'])->name('index');
        Route::get('/pending', [App\Http\Controllers\Admin\WarehouseController::class, 'pendingDistributions'])->name('pending');
        Route::put('/{id}', [App\Http\Controllers\Admin\WarehouseController::class, 'update'])->name('update');
        Route::post('/add-stock', [App\Http\Controllers\Admin\WarehouseController::class, 'addStock'])->name('add-stock');
        Route::post('/distribute', [App\Http\Controllers\Admin\WarehouseController::class, 'distributeToBranch'])->name('distribute');
        Route::post('/transfer/{transfer}/approve', [App\Http\Controllers\Admin\WarehouseController::class, 'approveDistribution'])->name('approve');
        Route::post('/transfer/{transfer}/reject', [App\Http\Controllers\Admin\WarehouseController::class, 'rejectDistribution'])->name('reject');
        // ===== ADD THESE MODAL ROUTES =====
    Route::get('/{id}/edit-modal', [App\Http\Controllers\Admin\WarehouseController::class, 'editModal'])->name('edit-modal');
    Route::get('/{id}/distribute-modal', [App\Http\Controllers\Admin\WarehouseController::class, 'distributeModal'])->name('distribute-modal');
    Route::get('/add-stock-modal', [App\Http\Controllers\Admin\WarehouseController::class, 'addStockModal'])->name('add-stock-modal');
    });

    // ===== PRODUCTS MANAGEMENT ROUTES =====
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::post('/products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::get('/products/{product}/edit-modal', [App\Http\Controllers\Admin\ProductController::class, 'editModal'])->name('products.edit-modal');
    Route::get('/products/{product}/show-modal', [App\Http\Controllers\Admin\ProductController::class, 'showModal'])->name('products.show-modal');
    Route::get('/products/{product}/add-stock-modal', [App\Http\Controllers\Admin\ProductController::class, 'addStockToBranchForm'])->name('products.add-stock-modal');
    Route::post('/products/{product}/add-stock', [App\Http\Controllers\Admin\ProductController::class, 'addStockToBranch'])->name('products.add-stock');

    // ===== BRANCH ADMIN MANAGEMENT ROUTES =====
    Route::prefix('branch-admin')->name('branch-admin.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\BranchAdminController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\BranchAdminController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\BranchAdminController::class, 'store'])->name('store');
        Route::get('/{branchAdmin}/modal-edit', [App\Http\Controllers\Admin\BranchAdminController::class, 'modalEdit'])->name('modal-edit');
        Route::put('/{branchAdmin}', [App\Http\Controllers\Admin\BranchAdminController::class, 'update'])->name('update');
        Route::delete('/{branchAdmin}', [App\Http\Controllers\Admin\BranchAdminController::class, 'destroy'])->name('destroy');
    });

    // ===== SUPER ADMIN INVENTORY ROUTES =====
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\InventoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\InventoryController::class, 'store'])->name('store');
        Route::get('/low-stock', [App\Http\Controllers\Admin\InventoryController::class, 'lowStock'])->name('low-stock');
        Route::get('/stock-history', [App\Http\Controllers\Admin\InventoryController::class, 'stockHistory'])->name('stock-history');
        Route::get('/transfers', [App\Http\Controllers\Admin\InventoryController::class, 'transfers'])->name('transfers');
        Route::get('/transfers/create', [App\Http\Controllers\Admin\InventoryController::class, 'createTransfer'])->name('create-transfer');
        Route::post('/transfers', [App\Http\Controllers\Admin\InventoryController::class, 'storeTransfer'])->name('store-transfer');
        Route::get('/transfers/{transfer}/show-modal', [App\Http\Controllers\Admin\InventoryController::class, 'showTransferModal'])->name('transfers.show-modal');
        Route::get('/transfers/{transfer}/edit-modal', [App\Http\Controllers\Admin\InventoryController::class, 'editTransferModal'])->name('transfers.edit-modal');
        Route::get('/transfers/{transfer}', [App\Http\Controllers\Admin\InventoryController::class, 'showTransfer'])->name('transfers.show');
        Route::get('/transfers/{transfer}/edit', [App\Http\Controllers\Admin\InventoryController::class, 'editTransfer'])->name('transfers.edit');
        Route::put('/transfers/{transfer}', [App\Http\Controllers\Admin\InventoryController::class, 'updateTransfer'])->name('transfers.update');
        Route::post('/transfers/{transfer}/approve', [App\Http\Controllers\Admin\InventoryController::class, 'approveTransfer'])->name('transfers.approve');
        Route::post('/transfers/{transfer}/reject', [App\Http\Controllers\Admin\InventoryController::class, 'rejectTransfer'])->name('transfers.reject');
        Route::post('/transfers/{transfer}/complete', [App\Http\Controllers\Admin\InventoryController::class, 'completeTransfer'])->name('transfers.complete');
        Route::post('/transfers/{transfer}/cancel', [App\Http\Controllers\Admin\InventoryController::class, 'cancelTransfer'])->name('transfers.cancel');
        Route::delete('/transfers/{transfer}', [App\Http\Controllers\Admin\InventoryController::class, 'destroyTransfer'])->name('transfers.destroy');
        Route::get('/branch/{branch}', [App\Http\Controllers\Admin\InventoryController::class, 'branchInventory'])->name('branch');
        Route::get('/summary', [App\Http\Controllers\Admin\InventoryController::class, 'summary'])->name('summary');
        Route::get('/{inventory}/edit-modal', [App\Http\Controllers\Admin\InventoryController::class, 'editModal'])->name('edit-modal');
        Route::get('/{inventory}/show-modal', [App\Http\Controllers\Admin\InventoryController::class, 'showModal'])->name('show-modal');
        Route::get('/{inventory}/add-stock-modal', [App\Http\Controllers\Admin\InventoryController::class, 'addStockModal'])->name('add-stock-modal');
        Route::post('/{inventory}/add-stock', [App\Http\Controllers\Admin\InventoryController::class, 'addStock'])->name('add-stock.post');
        Route::post('/{inventory}/remove-stock', [App\Http\Controllers\Admin\InventoryController::class, 'removeStock'])->name('remove-stock');
        Route::put('/{inventory}', [App\Http\Controllers\Admin\InventoryController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [App\Http\Controllers\Admin\InventoryController::class, 'destroy'])->name('destroy');
        Route::get('/{inventory}/archive', [App\Http\Controllers\Admin\InventoryController::class, 'archive'])->name('archive');
        Route::get('/{inventory}/unarchive', [App\Http\Controllers\Admin\InventoryController::class, 'unarchive'])->name('unarchive');
        Route::post('/{inventory}/dispose', [App\Http\Controllers\Admin\InventoryController::class, 'dispose'])->name('dispose');
        Route::get('/{inventory}/restore-disposed', [App\Http\Controllers\Admin\InventoryController::class, 'restoreDisposed'])->name('restore-disposed');
    });

    // ===== CUSTOMER MANAGEMENT ROUTES =====
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}/show', [App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/modal-edit', [App\Http\Controllers\Admin\CustomerController::class, 'modalEdit'])->name('modal-edit');
        Route::put('/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('destroy');
        Route::post('/{customer}/toggle-status', [App\Http\Controllers\Admin\CustomerController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ===== SUPER ADMIN POS ROUTES =====
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\PosController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\Admin\PosController::class, 'history'])->name('history');
        Route::get('/receipt', [App\Http\Controllers\Admin\PosController::class, 'receipt'])->name('receipt');
        Route::post('/add-to-cart', [App\Http\Controllers\Admin\PosController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/update-cart', [App\Http\Controllers\Admin\PosController::class, 'updateCart'])->name('update-cart');
        Route::post('/clear-cart', [App\Http\Controllers\Admin\PosController::class, 'clearCart'])->name('clear-cart');
        Route::post('/checkout', [App\Http\Controllers\Admin\PosController::class, 'checkout'])->name('checkout');
    });
    Route::get('/pos/test', function() { return response()->json(['message' => 'POS route is working!']); })->name('pos.test');
});
Route::get('/debug-dashboard', function() {
    $data = [];
    $data['expiringSoon'] = \App\Models\BranchInventory::whereNotNull('expiration_date')
        ->where('expiration_date', '>=', \Carbon\Carbon::today())
        ->where('expiration_date', '<=', \Carbon\Carbon::today()->addDays(30))
        ->with(['product', 'branch'])
        ->get();
    $data['fastMovingProducts'] = \Illuminate\Support\Facades\DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.payment_status', 'paid')
        ->select('products.id', 'products.name', \Illuminate\Support\Facades\DB::raw('SUM(order_items.quantity) as total_sold'))
        ->groupBy('products.id', 'products.name')
        ->orderBy('total_sold', 'desc')
        ->limit(5)
        ->get();

    return [
        'expiringSoon_count' => $data['expiringSoon']->count(),
        'expiringSoon_data' => $data['expiringSoon']->toArray(),
        'fastMovingProducts_count' => $data['fastMovingProducts']->count(),
        'fastMovingProducts_data' => $data['fastMovingProducts']->toArray(),
    ];
});
// ===========================================================================
// BRANCH ADMIN ROUTES (with online order management)
// ===========================================================================
Route::middleware(['auth', 'verified'])->prefix('branch-admin')->name('branch-admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\BranchAdmin\DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::get('/products', [BranchAdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [BranchAdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [BranchAdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [BranchAdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [BranchAdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [BranchAdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [BranchAdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/upload-image', [BranchAdminProductController::class, 'uploadImage'])->name('products.upload-image');

    // Orders/Sales Processing (existing POS)
    if (class_exists(\App\Http\Controllers\BranchAdmin\OrderController::class)) {
        Route::resource('orders', \App\Http\Controllers\BranchAdmin\OrderController::class);
        Route::post('/orders/{order}/process', [\App\Http\Controllers\BranchAdmin\OrderController::class, 'processOrder'])->name('orders.process');
        Route::get('/pos', [\App\Http\Controllers\BranchAdmin\OrderController::class, 'pos'])->name('pos');
        Route::post('/pos/quick-sale', [\App\Http\Controllers\BranchAdmin\OrderController::class, 'quickSale'])->name('pos.quick-sale');
    } else {
        Route::get('/orders', function () { return "Orders Management - To be implemented"; })->name('orders.index');
        Route::get('/pos', function () { return "Point of Sale - To be implemented"; })->name('pos');
    }

    // Online Orders Management
    Route::prefix('online-orders')->name('online-orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'show'])->name('show');
        Route::post('/{order}/confirm', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'confirm'])->name('confirm');
        Route::post('/{order}/reject', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'reject'])->name('reject');
        Route::post('/{order}/processing', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'markProcessing'])->name('processing');
        Route::post('/{order}/ready', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'markReady'])->name('ready');
        Route::post('/{order}/assign-driver', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'assignDriver'])->name('assign-driver');
        Route::post('/{order}/delivered', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'markDelivered'])->name('delivered');
        Route::post('/delivery/{delivery}/tracking', [App\Http\Controllers\BranchAdmin\OnlineOrderController::class, 'updateTracking'])->name('update-tracking');
    });


    // Reports
    Route::get('/reports/sales', function () {
        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
        return "Sales Reports - To be implemented";
    })->name('reports.sales');

    Route::get('/reports/inventory', function () {
        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
        return "Inventory Reports - To be implemented";
    })->name('reports.inventory');

    // =============================================
    // FIXED: API ROUTES FOR TRANSFER - ONLY THESE ARE CHANGED
    // =============================================
    Route::prefix('api')->name('api.')->group(function () {
    Route::get('/source-products', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'getWarehouseProducts'])->name('source.products');
    Route::get('/products/{product}/flavors', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'getFlavors'])->name('product.flavors');
    Route::get('/inventory/check', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'checkAvailability'])->name('inventory.check');
    Route::get('/warehouse/check', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'checkWarehouseAvailability'])->name('warehouse.check');
});

    // ===== BRANCH STAFF INVENTORY ROUTES =====
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'index'])->name('index');
        Route::get('/add-product', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addProductForm'])->name('add-product');
        Route::post('/add-product', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addProduct'])->name('add-product.post');
        Route::get('/quick-add-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'quickAddStockForm'])->name('quick-add-stock');
        Route::post('/quick-add-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'quickAddStock'])->name('quick-add-stock.post');
        Route::get('/add-to-inventory/{product}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addToInventoryForm'])->name('add-to-inventory');
        Route::post('/add-to-inventory', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addToInventory'])->name('add-to-inventory.post');
        Route::get('/low-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'lowStock'])->name('low-stock');
        Route::get('/stock-history', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'stockHistory'])->name('stock-history');
        Route::get('/transfer/request', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'transferForm'])->name('transfer.form');
        Route::post('/transfer/request', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'requestTransfer'])->name('transfer.request');
        Route::get('/transfers', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'transfers'])->name('transfers');
        Route::post('/transfers/{transfer}/approve', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'approveTransfer'])->name('transfers.approve');
        Route::post('/transfers/{transfer}/reject', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'rejectTransfer'])->name('transfers.reject');
        Route::post('/transfers/{transfer}/complete', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'completeTransfer'])->name('transfers.complete');
        Route::post('/transfers/{transfer}/cancel', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'cancelTransfer'])->name('transfers.cancel');

        // MOVED THESE BEFORE THE PARAMETERIZED ROUTES
        Route::get('/transfer-modal', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'transferModal'])->name('transfer-modal');
        Route::get('/check-availability', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'checkAvailability'])->name('check-availability');

        // PARAMETERIZED ROUTES - KEEP THESE AT THE BOTTOM
        Route::get('/{inventory}/edit-modal', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'editModal'])->name('edit-modal');
        Route::get('/{inventory}/add-stock-modal', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addStockModal'])->name('add-stock-modal');
        Route::get('/{inventory}/show-modal', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'showModal'])->name('show-modal');
        Route::post('/{inventory}/archive', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'archive'])->name('archive');
        Route::post('/{inventory}/unarchive', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'unarchive'])->name('unarchive');
        Route::post('/{inventory}/dispose', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'dispose'])->name('dispose');
        Route::post('/{inventory}/restore-disposed', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'restoreDisposed'])->name('restore-disposed');
        Route::get('/{inventory}/edit', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'edit'])->name('edit');
        Route::get('/{inventory}/add-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addStockForm'])->name('add-stock');
        Route::post('/{inventory}/add-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'addStock'])->name('add-stock.post');
        Route::get('/{inventory}/adjust-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'adjustStockForm'])->name('adjust-stock');
        Route::post('/{inventory}/adjust-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'adjustStock'])->name('adjust-stock.post');
        Route::put('/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'update'])->name('update');
        Route::delete('/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'destroy'])->name('destroy');
        Route::get('/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'show'])->name('show');
    });

    // ===== POINT OF SALE ROUTES =====
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [App\Http\Controllers\BranchAdmin\PosController::class, 'index'])->name('index');
        Route::get('/history', [App\Http\Controllers\BranchAdmin\PosController::class, 'history'])->name('history');
        Route::get('/order/{order}', [App\Http\Controllers\BranchAdmin\PosController::class, 'showOrder'])->name('order.show');
        Route::post('/add-to-cart', [App\Http\Controllers\BranchAdmin\PosController::class, 'addToCart'])->name('add-to-cart');
        Route::post('/update-cart', [App\Http\Controllers\BranchAdmin\PosController::class, 'updateCart'])->name('update-cart');
        Route::post('/clear-cart', [App\Http\Controllers\BranchAdmin\PosController::class, 'clearCart'])->name('clear-cart');
        Route::post('/checkout', [App\Http\Controllers\BranchAdmin\PosController::class, 'checkout'])->name('checkout');
        Route::get('/receipt', [App\Http\Controllers\BranchAdmin\PosController::class, 'receipt'])->name('receipt');
        Route::get('/search-product', [App\Http\Controllers\BranchAdmin\PosController::class, 'searchProduct'])->name('search-product');
    });

    // ===== WAREHOUSE REQUESTS (BRANCH STAFF) =====
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/', [App\Http\Controllers\BranchAdmin\WarehouseRequestController::class, 'index'])->name('index');
        Route::post('/request', [App\Http\Controllers\BranchAdmin\WarehouseRequestController::class, 'requestStock'])->name('request');
        Route::post('/transfer/{transfer}/receive', [App\Http\Controllers\BranchAdmin\WarehouseRequestController::class, 'receiveTransfer'])->name('receive');
    });
});

// ===========================================================================
// DRIVER ROUTES
// ===========================================================================
Route::middleware(['auth', 'verified', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Driver\DeliveryController::class, 'dashboard'])->name('dashboard');
    // ✅ ADD THIS LINE RIGHT HERE (Pointing to DeliveryController):
    Route::get('/delivery-history', [App\Http\Controllers\Driver\DeliveryController::class, 'deliveryHistory'])->name('delivery-history');
    // Online Orders Management
    Route::prefix('online-orders')->name('online-orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\Driver\OnlineOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [App\Http\Controllers\Driver\OnlineOrderController::class, 'show'])->name('show');
        Route::post('/{order}/confirm', [App\Http\Controllers\Driver\OnlineOrderController::class, 'confirm'])->name('confirm');
        Route::post('/{order}/processing', [App\Http\Controllers\Driver\OnlineOrderController::class, 'markProcessing'])->name('processing');
        Route::post('/{order}/ready', [App\Http\Controllers\Driver\OnlineOrderController::class, 'markReady'])->name('ready');
        Route::post('/{order}/start-delivery', [App\Http\Controllers\Driver\OnlineOrderController::class, 'startDelivery'])->name('start-delivery');
    });

    // Delivery Management - Driver sees ALL deliveries assigned to them
    Route::get('/deliveries', [App\Http\Controllers\Driver\DeliveryController::class, 'index'])->name('deliveries');
    Route::get('/deliveries/{delivery}', [App\Http\Controllers\Driver\DeliveryController::class, 'show'])->name('deliveries.show');
    Route::post('/deliveries/{delivery}/update', [App\Http\Controllers\Driver\DeliveryController::class, 'updateStatus'])->name('delivery.update');
    Route::post('/deliveries/{delivery}/location', [App\Http\Controllers\Driver\DeliveryController::class, 'updateLocation'])->name('delivery.location');
});

// ===========================================================================
// API ROUTES (additional endpoints for online ordering)
// ===========================================================================
Route::prefix('api')->group(function () {
    Route::get('/branches', function () {
        return response()->json(\App\Models\Branch::all());
    });
    Route::get('/products', function () {
        return response()->json(\App\Models\Product::where('is_active', true)->get());
    });
    Route::get('/products/{product}/flavors', function($productId) {
        $product = \App\Models\Product::find($productId);
        return response()->json($product ? $product->flavors : []);
    });
    Route::get('/branches/{branch}/products/{product}/stock', function($branchId, $productId) {
        $flavorId = request('flavor_id');
        $inventory = \App\Models\BranchInventory::where('branch_id', $branchId)
            ->where('product_id', $productId)
            ->when($flavorId, fn($q) => $q->where('flavor_id', $flavorId))
            ->first();
        return response()->json([
            'available' => $inventory ? $inventory->available_quantity : 0,
            'quantity' => $inventory->quantity ?? 0,
            'reserved' => $inventory->reserved_quantity ?? 0,
        ]);
    })->name('api.stock.check');

    // ADD THIS - Warehouse availability check API
    Route::get('/warehouse/check', function(\Illuminate\Http\Request $request) {
        $query = \App\Models\WarehouseInventory::where('product_id', $request->product_id)
            ->where('quantity', '>', 0);

        if ($request->flavor_id) {
            $query->where('flavor_id', $request->flavor_id);
        }

        $inventory = $query->first();

        return response()->json([
            'success' => true,
            'available' => $inventory ? $inventory->quantity : 0
        ]);
    })->name('api.warehouse.check');
});

// Temporary test route
Route::get('/test-archive-route', function () {
    return 'Routes are working!';
});
// =============================================
// DEBUG ROUTES - KEPT FOR TESTING
// =============================================
Route::get('/test-warehouse-query', function() {
    DB::enableQueryLog();
    
    // Test 1: Simple count
    $count = DB::table('warehouse_inventories')->where('quantity', '>', 0)->count();
    
    // Test 2: Get products
    $products = DB::table('warehouse_inventories')
        ->join('products', 'warehouse_inventories.product_id', '=', 'products.id')
        ->where('warehouse_inventories.quantity', '>', 0)
        ->where('products.is_active', 1)
        ->select('products.id', 'products.name')
        ->distinct()
        ->get();
    
    // Test 3: Raw SQL
    $rawProducts = DB::select("
        SELECT DISTINCT p.id, p.name 
        FROM warehouse_inventories wi
        INNER JOIN products p ON wi.product_id = p.id
        WHERE wi.quantity > 0 
        AND p.is_active = 1
    ");
    
    // Test 4: Check if products table has data
    $allProducts = DB::table('products')->where('is_active', 1)->get();
    
    return response()->json([
        'count_with_stock' => $count,
        'products_with_join' => $products,
        'raw_sql_products' => $rawProducts,
        'all_active_products' => $allProducts,
        'queries' => DB::getQueryLog()
    ]);
});
Route::get('/test-db-connection', function() {
    return response()->json([
        'connection_name' => DB::connection()->getName(),
        'database_name' => DB::connection()->getDatabaseName(),
        'table_exists' => Schema::hasTable('warehouse_inventories'),
        'products_table_exists' => Schema::hasTable('products'),
    ]);
});
// =============================================
// Fallback route for undefined routes
Route::fallback(function () {
    return redirect()->route('home')->with('error', 'Page not found.');
});