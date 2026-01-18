<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\{
    AuthController,
    ProductController,
    OrderController,
    BranchController,
    NotificationController
};

// Public API
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected API
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Branches
    Route::get('/branches', [BranchController::class, 'index']);
    Route::get('/branches/{branch}', [BranchController::class, 'show']);
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/branches/{branch}/products', [ProductController::class, 'byBranch']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    
    // Cart
    Route::get('/cart', [OrderController::class, 'cart']);
    Route::post('/cart/add', [OrderController::class, 'addToCart']);
    Route::put('/cart/update/{item}', [OrderController::class, 'updateCart']);
    Route::delete('/cart/remove/{item}', [OrderController::class, 'removeFromCart']);
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});