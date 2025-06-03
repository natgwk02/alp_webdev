<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminOrderController;


// Authentication Routes

Route::post('/login', [AuthController::class, 'login_auth'])->name('login.auth');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::POST('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Customer Routes
// Route::middleware(['auth', 'customer'])->group(function () {
Route::get('/', [HomeController::class, 'showHome'])->name('home');
Route::get('/products', action: [ProductController::class, 'index'])->name('products');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.detail');
// Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
Route::post('/order/{id}/received', [OrderController::class, 'markAsReceived'])->name('order.received');
Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.applyVoucher');
Route::get('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.removeVoucher');
// });

// Admin Routes

Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
Route::get('/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('admin.dashboard'); // ✅ tambahkan ini

//  Product Management
Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
Route::put('/admin/products/{product}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update');
Route::get('/admin/products/{product}/edit-data', [AdminController::class, 'getProductData'])->name('admin.products.edit-data');
Route::delete('admin/products/delete/{product}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
Route::post('/product/create', [AdminController::class, 'insertProduct'])->name('admin.products.create');
Route::get('/admin/products/trash', [AdminController::class, 'trash'])->name('admin.products.trash');
Route::post('/admin/products/{product}/restore', [AdminController::class, 'restore'])->name('admin.products.restore');

    // Order Management
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('admin/orders/status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

// Home Route
Route::middleware('web')->group(function () {
    Route::get('/home', [HomeController::class, 'showHome'])
        ->name('home');
});

// Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.detail');


Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/wishlist', [ProductController::class, 'wishlist'])->name('wishlist');
    Route::post('wishlist/add/{productId}', [ProductController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('wishlist/remove/{productId}', [ProductController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::get('wishlist/toggle/{productId}', [ProductController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update/{productId}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/checkout-process', [OrderController::class, 'processCheckout'])->name('checkout');
    Route::get('/checkout', [OrderController::class, 'showCheckoutForm'])->name('checkout.form');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/counts', [ CartController::class, 'getCounts'])->name('counts');
});
Route::middleware('auth:sanctum')->get('/badge-counts', 'App\Http\Controllers\BadgeController@getCounts');



Route::get('/about', function () {
    return view('customer.about');
})->name('about');

// Route::get('/profile', function () {
//     return view('customer.profile');
// })->name('profile');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
// Profile Routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
//     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
// });
