<?php

use Illuminate\Support\Facades\Auth;
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

Route::POST('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


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
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/wishlist', [CartController::class, 'wishlist'])->name('wishlist');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
// //});

// Admin Routes
//Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

  //  Product Management
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::put('product/update/{product:id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/product/delete/{product:id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::post('/product/create/{product:id}', [AdminController::class, 'insertProduct'])->name('admin.products.create');

    // Order Management
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
//});

// Home Route
Route::get('/home', [HomeController::class, 'showHome'])
->name('home');
//Route::get('/orders', [OrderController::class, 'index'])->name('orders');
//Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.detail');


Route::get('/about', function () {
    return view('customer.about');
})->name('about');

Route::get('/profile', function () {
    return view('customer.profile');
})->name('profile');

// Profile Routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
//     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
// });
