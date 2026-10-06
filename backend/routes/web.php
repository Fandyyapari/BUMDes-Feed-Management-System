<?php

use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\FeedCatalogController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Halaman publik.
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/pakan', [FeedCatalogController::class, 'index'])
    ->name('catalog.index');

Route::get('/pakan/{feedProduct}', [FeedCatalogController::class, 'show'])
    ->whereNumber('feedProduct')
    ->name('catalog.show');

// Daftar dan masuk untuk pengunjung yang belum login.
Route::middleware('guest')->group(function () {
    Route::get('/daftar', [CustomerAuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/daftar', [CustomerAuthController::class, 'register'])
        ->middleware('throttle:3,1')
        ->name('customer.register.store');

    Route::get('/masuk', [CustomerAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/masuk', [CustomerAuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('customer.login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profil', [CustomerProfileController::class, 'edit'])
        ->name('customer.profile.edit');

    Route::put('/profil', [CustomerProfileController::class, 'update'])
        ->name('customer.profile.update');
});

// Keluar hanya untuk pengguna yang sudah login.
Route::post('/keluar', [CustomerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
