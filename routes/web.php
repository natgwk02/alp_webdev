<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminOrderController;

// Authentication Routes
Route::get('/login', [AuthController::class, "show"])
->name('login.show');

Route::post('/login_auth', [AuthController::class, "login_auth"])
->name('login.auth');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register'); // untuk tampilkan form
Route::post('/register', [AuthController::class, 'register'])->name('register.submit'); 
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'resetPassword'])->name('password.update');


// // Customer Routes
// //Route::middleware(['auth', 'customer'])->group(function () {
    Route::get( '/', [HomeController::class, 'showHome'])->name('home');
    Route::get('/products', action: [ProductController::class, 'index'])->name('products');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.detail');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/wishlist', [CartController::class, 'wishlist'])->name('wishlist');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
// //});

// Admin Routes
//Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Product Management
    Route::get('/products', [AdminController::class, 'products'])
    ->name('admin.products');
    // ->name('products');
    //dijadiin satu sm products
    // Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
    // Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit');

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
//});

// Home Route
Route::get('/home', [HomeController::class, 'showHome'])
->name('home');
//Route::get('/orders', [OrderController::class, 'index'])->name('orders');
//Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.detail');
