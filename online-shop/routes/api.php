<?php


use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::post('/cart', [CartController::class, 'apiAdd']);
    Route::delete('/cart', [CartController::class, 'apiClear']);
    Route::delete('/cart/{productId}', [CartController::class, 'apiRemove']);
    Route::post('/auth/login', [AuthController::class, 'apiLogin']);
    Route::post('/auth/register', [AuthController::class, 'apiRegister']);
    Route::delete('/auth/session', [AuthController::class, 'apiLogout']);
    Route::post('/orders', [OrderController::class, 'apiCreate'])->middleware('auth');
    Route::patch('/profile', [ProfileController::class, 'apiUpdate'])->name('profile.update')->middleware('auth');
});

Route::fallback(function () {
    return response()->json(['error' => 'Маршрут не найден'], 404);
});
