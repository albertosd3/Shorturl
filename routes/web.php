<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Short URL redirect (must be last to avoid conflicts)
Route::get('/{shortCode}', [UrlController::class, 'redirect'])
    ->where('shortCode', '[a-zA-Z0-9]{6}')
    ->name('redirect');

// Admin routes
Route::middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('urls')->name('urls.')->group(function () {
        Route::get('/', [UrlController::class, 'index'])->name('index');
        Route::post('/short', [UrlController::class, 'createShortUrl'])->name('create.short');
        Route::post('/rotator', [UrlController::class, 'createRotator'])->name('create.rotator');
        Route::put('/{type}/{id}/toggle', [UrlController::class, 'toggleStatus'])->name('toggle');
    });
});