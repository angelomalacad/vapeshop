<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BranchAdmin\ProductController as BranchAdminProductController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        
        $user = Auth::user();
        switch ($user->role) {
            case 'super_admin':
                return redirect()->route('admin.dashboard');
            case 'branch_admin':
                return redirect()->route('branch-admin.dashboard');
            case 'customer':
            default:
                return redirect()->route('customer.dashboard');
        }
    }
    
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function () {
    $validated = request()->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);
    
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => 'customer', // Default role
    ]);
    
    Auth::login($user);
    return redirect()->route('customer.dashboard');
});

// Customer Routes - WITHOUT middleware for now
Route::prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        if (!in_array($user->role, ['customer', 'super_admin', 'branch_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied. Customer area only.');
        }
        
        return view('customer.dashboard');
    })->name('dashboard');
    
    // Products
    Route::get('/products', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        return "Customer Products - To be implemented";
    })->name('products.index');
    
    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', function () {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            return "Cart - To be implemented";
        })->name('index');
    });
});

// Admin Routes (Super Admin) - WITHOUT middleware for now
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin only.');
        }
        
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Simple admin routes
    Route::get('/branches', function () {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            return redirect()->route('login');
        }
        return "Branches Management - To be implemented";
    })->name('branches.index');
});

// Branch Admin Routes - WITH Inventory Management
Route::prefix('branch-admin')->name('branch-admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied. Branch admin only.');
        }
        
        return view('branch-admin.dashboard');
    })->name('dashboard');
    
    // Inventory Management
    Route::get('/inventory', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'show'])->name('inventory.show');
    Route::get('/inventory/{inventory}/edit', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'destroy'])->name('inventory.destroy');
    
    // Additional Inventory Routes
    Route::post('/inventory/update-stock/{inventory}', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::get('/inventory/low-stock', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'lowStock'])->name('inventory.low-stock');
    Route::get('/inventory/stock-history', [App\Http\Controllers\BranchAdmin\InventoryController::class, 'stockHistory'])->name('inventory.stock-history');
    
    // Products Management (for adding new products to branch)
    // Using the imported BranchAdminProductController
    Route::get('/products', [BranchAdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [BranchAdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [BranchAdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [BranchAdminProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [BranchAdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [BranchAdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [BranchAdminProductController::class, 'destroy'])->name('products.destroy');
    
    // Additional Product Routes
    Route::post('/products/upload-image', [BranchAdminProductController::class, 'uploadImage'])->name('products.upload-image');
    
    // Orders/Sales Processing
    // Check if OrderController exists, if not, use placeholders
    if (class_exists(App\Http\Controllers\BranchAdmin\OrderController::class)) {
        Route::resource('orders', App\Http\Controllers\BranchAdmin\OrderController::class);
        Route::post('/orders/{order}/process', [App\Http\Controllers\BranchAdmin\OrderController::class, 'processOrder'])->name('orders.process');
        Route::get('/pos', [App\Http\Controllers\BranchAdmin\OrderController::class, 'pos'])->name('pos');
        Route::post('/pos/quick-sale', [App\Http\Controllers\BranchAdmin\OrderController::class, 'quickSale'])->name('pos.quick-sale');
    } else {
        // Placeholder routes for orders
        Route::get('/orders', function () {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            return "Orders Management - To be implemented";
        })->name('orders.index');
        
        Route::get('/pos', function () {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            return "Point of Sale - To be implemented";
        })->name('pos');
    }
    
    // Reports
    Route::get('/reports/sales', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
        
        return "Sales Reports - To be implemented";
    })->name('reports.sales');
    
    Route::get('/reports/inventory', function () {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        if (!in_array($user->role, ['branch_admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied.');
        }
        
        return "Inventory Reports - To be implemented";
    })->name('reports.inventory');
});

// API Routes - Simplified
Route::prefix('api')->group(function () {
    Route::get('/branches', function () {
        $branches = \App\Models\Branch::all();
        return response()->json($branches);
    });
    
    Route::get('/products', function () {
        $products = \App\Models\Product::where('is_active', true)->get();
        return response()->json($products);
    });
});

// Fallback route for undefined routes
Route::fallback(function () {
    return redirect()->route('home')->with('error', 'Page not found.');
});