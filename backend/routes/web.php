<?php

use App\Http\Controllers\FeedCatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/pakan', [FeedCatalogController::class, 'index'])
    ->name('catalog.index');

Route::get('/pakan/{feedProduct}', [FeedCatalogController::class, 'show'])
    ->whereNumber('feedProduct')
    ->name('catalog.show');