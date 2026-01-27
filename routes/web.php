<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;

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

// Branch Admin Routes - WITHOUT middleware for now
Route::prefix('branch-admin')->name('branch-admin.')->group(function () {
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
});

// API Routes - Simplified
Route::prefix('api')->group(function () {
    Route::get('/branches', function () {
        $branches = \App\Models\Branch::all();
        return response()->json($branches);
    });
});