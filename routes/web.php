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
        'city' => 'nullable|string|max:100',
        'province' => 'nullable|string|max:100',
        'zip_code' => 'nullable|string|max:10',
        'birthdate' => 'nullable|date|before:'.now()->subYears(18)->format('Y-m-d'),
        'terms' => 'required|accepted',
    ]);
    
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => 'customer',
        'phone' => $validated['phone'],
        'address' => $validated['address'],
        'city' => $validated['city'] ?? 'Calamba',
        'province' => $validated['province'] ?? 'Laguna',
        'zip_code' => $validated['zip_code'] ?? null,
        'birthdate' => $validated['birthdate'] ?? null,
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

// ===== STEP 7: PROTECTED ROUTES THAT REQUIRE VERIFICATION =====
// Customer Routes - Require email verification
Route::middleware(['auth', 'verified'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if (!in_array($user->role, ['customer', 'super_admin', 'branch_admin'])) {
            return redirect()->route('home')->with('error', 'Access denied. Customer area only.');
        }
        
        return view('customer.dashboard');
    })->name('dashboard');
    
    // Products
    Route::get('/products', function () {
        return "Customer Products - To be implemented";
    })->name('products.index');
    
    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', function () {
            return "Cart - To be implemented";
        })->name('index');
    });
});

// Admin Routes (Super Admin) - Require email verification
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin only.');
        }
        
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Simple admin routes
    Route::get('/branches', function () {
        if (Auth::user()->role !== 'super_admin') {
            return redirect()->route('login');
        }
        return "Branches Management - To be implemented";
    })->name('branches.index');
    
    // ===== STAFF MANAGEMENT ROUTES =====
    // Staff Management (ONLY super admin can access)
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/', function () {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            // If controller exists, use it, otherwise placeholder
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->index(request());
            }
            return "Staff Management - Please create StaffController first";
        })->name('index');
        
        Route::get('/create', function () {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->create();
            }
            return "Create Staff - Please create StaffController first";
        })->name('create');
        
        Route::post('/', function () {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->store(request());
            }
            return redirect()->route('admin.staff.index')->with('error', 'StaffController not found');
        })->name('store');
        
        Route::get('/{staff}/edit', function ($staff) {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $user = \App\Models\User::findOrFail($staff);
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->edit($user);
            }
            return "Edit Staff - Please create StaffController first";
        })->name('edit');
        
        Route::put('/{staff}', function ($staff) {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $user = \App\Models\User::findOrFail($staff);
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->update(request(), $user);
            }
            return redirect()->route('admin.staff.index')->with('error', 'StaffController not found');
        })->name('update');
        
        Route::delete('/{staff}', function ($staff) {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $user = \App\Models\User::findOrFail($staff);
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->destroy($user);
            }
            return redirect()->route('admin.staff.index')->with('error', 'StaffController not found');
        })->name('destroy');
        
        Route::post('/{staff}/reset-password', function ($staff) {
            if (Auth::user()->role !== 'super_admin') {
                return redirect()->route('login');
            }
            if (class_exists(\App\Http\Controllers\Admin\StaffController::class)) {
                $user = \App\Models\User::findOrFail($staff);
                $controller = app()->make(\App\Http\Controllers\Admin\StaffController::class);
                return $controller->resetPassword(request(), $user);
            }
            return redirect()->route('admin.staff.index')->with('error', 'StaffController not found');
        })->name('reset-password');
    });
    // ===== END OF STAFF MANAGEMENT ROUTES =====
});

// Branch Admin Routes - Require email verification
Route::middleware(['auth', 'verified'])->prefix('branch-admin')->name('branch-admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
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
    if (class_exists(\App\Http\Controllers\BranchAdmin\OrderController::class)) {
        Route::resource('orders', \App\Http\Controllers\BranchAdmin\OrderController::class);
        Route::post('/orders/{order}/process', [\App\Http\Controllers\BranchAdmin\OrderController::class, 'processOrder'])->name('orders.process');
        Route::get('/pos', [\App\Http\Controllers\BranchAdmin\OrderController::class, 'pos'])->name('pos');
        Route::post('/pos/quick-sale', [\App\Http\Controllers\BranchAdmin\OrderController::class, 'quickSale'])->name('pos.quick-sale');
    } else {
        // Placeholder routes for orders
        Route::get('/orders', function () {
            return "Orders Management - To be implemented";
        })->name('orders.index');
        
        Route::get('/pos', function () {
            return "Point of Sale - To be implemented";
        })->name('pos');
    }
    
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
});
// ===== END OF STEP 7 =====

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