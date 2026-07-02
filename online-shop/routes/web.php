<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::get('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth', function () {
    $next = request()->query('next', '/');
    $target = '/auth/login' . ($next !== '/' ? '?next=' . urlencode($next) : '');
    return redirect($target);
})->name('auth.redirect');
Route::get('/auth/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route:: get('/auth/register', [AuthController::class, 'showRegister'])->name('auth.register.form')->middleware('guest');
Route::post('/auth/login', [AuthController::class, 'handleLogin'])->name('auth.login');
Route:: post('auth/register', [AuthController:: class, 'handleRegister'])->name('auth.register');
Route:: get('/profile', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
Route::get('/', [CatalogController::class, 'showProducts'])->name('catalog.index');
Route::get('/category/{categoryId}', [CatalogController::class, 'showProductsByCategory'])
    ->name('catalog.category');
