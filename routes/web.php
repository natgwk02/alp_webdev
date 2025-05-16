<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
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
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


// Customer Routes
// //Route::middleware(['auth', 'customer'])->group(function () {
    Route::get( '/', [HomeController::class, 'showHome'])->name('home');
    Route::get('/products', action: [ProductController::class, 'index'])->name('products');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.detail');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/wishlist', [ProductController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{productId}', [ProductController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove/{productId}', [ProductController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/checkoutform', [OrderController::class, 'showCheckoutForm'])->name('checkout.form');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/{id}/received', [OrderController::class, 'markAsReceived'])->name('order.received');

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
    Route::post('/admin/orders/status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
//});

// Home Route
Route::get('/home', [HomeController::class, 'showHome'])
->name('home');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.detail');


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
