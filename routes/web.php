<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VerifyEmailController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::get('/privacy_policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('/terms_conditions', [HomeController::class, 'terms_conditions'])->name('terms_conditions');

Route::get('/product/{slug}', [ProductController::class, 'product'])->name('product_view');
Route::post('/product/add_to_cart', [HomeController::class, 'add_to_cart'])->name('add_to_cart');
Route::post('/product/buy_now_prepare', [HomeController::class, 'buy_now_prepare'])->name('buy_now_prepare');

// Payment Routes
Route::post('/checkout/create', [PaymentController::class, 'createCheckout'])->name('create_checkout');
Route::get('/email-check', [VerifyEmailController::class, 'verify']);

// Webhook (must be POST)
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook']);

Route::get('/dashboard/download_file/{file}/{order}', [DashboardController::class, 'downloadFile'])->name('file.download');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/addresses', [DashboardController::class, 'addresses'])->name('addresses');
});

require __DIR__ . '/auth.php';
