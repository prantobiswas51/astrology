<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/cart', [HomeController::class, 'cart'])->name('cart');
Route::get('/privacy_policy', [HomeController::class, 'privacyPolicy'])->name('privacy_policy');
Route::get('/terms_conditions', [HomeController::class, 'termsConditions'])->name('terms_conditions');

Route::get('/product/{slug}', [HomeController::class, 'product'])->name('product_view');
Route::post('/product/add_to_cart', [HomeController::class, 'add_to_cart'])->name('add_to_cart');
Route::post('/product/buy_now_prepare', [HomeController::class, 'buy_now_prepare'])->name('buy_now_prepare');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
