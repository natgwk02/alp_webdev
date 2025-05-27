<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminOrderController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login_auth'])->name('login.auth');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.update');

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
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/wishlist', [ProductController::class, 'wishlist'])->middleware('auth')->name('wishlist');

    Route::post('/wishlist/{productId}', [ProductController::class, 'addToWishlist'])->name('wishlist.add');
    Route::get('/wishlist/toggle/{productId}', [ProductController::class, 'toggleWishlist']);
    Route::post('/wishlist/remove/{productId}', [ProductController::class, 'removeFromWishlist'])->name('wishlist.remove');
    Route::post('/checkout-process', [OrderController::class, 'processCheckout'])->name('checkout');
    Route::get('/checkout', [OrderController::class, 'showCheckoutForm'])->name('checkout.form');
    // Route::post('/checkout/place-order', [CartController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::post('/order/{id}/received', [OrderController::class, 'markAsReceived'])->name('order.received');
    Route::post('/cart/apply-voucher', [CartController::class, 'applyVoucher'])->name('cart.applyVoucher');
    Route::get('/cart/remove-voucher', [CartController::class, 'removeVoucher'])->name('cart.removeVoucher');
// });

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    //  Product Management
    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::put('product/update/{product:id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::post('/product/delete/{product:id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');
    Route::post('/product/create', [AdminController::class, 'insertProduct'])->name('admin.products.create');

    // Order Management
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('admin/orders/status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});

// Home Route
Route::middleware('web')->group(function(){
    Route::get('/home', [HomeController::class, 'showHome'])
    ->name('home');
});

// Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('order.detail');


Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/wishlist', [ProductController::class, 'index'])->name('wishlist.index');
});


Route::get('/about', function () {
    return view('customer.about');
})->name('about');

Route::get('/profile', function () {
    return view('customer.profile');
})->name('profile');

// Route::POST('/logout', function () {
//     Auth::logout();
//     request()->session()->invalidate();
//     request()->session()->regenerateToken();
//     return redirect('/login');
// })->name('logout');
// Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Profile Routes
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
//     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
// });
