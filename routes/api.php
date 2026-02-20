<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\SoapController;
use Illuminate\Support\Facades\Route;

Route::post('/soap', [SoapController::class, 'handle'])->name('api.soap');

Route::prefix('/orders')->name('api.orders.')->group(function () {
    Route::get('/price', [OrderController::class, 'getPrice'])->name('price');
    Route::get('/stats', [OrderController::class, 'getStats'])->name('stats');
    Route::get('/search', [OrderController::class, 'search'])->name('search');
    Route::get('/{id}', [OrderController::class, 'show'])->name('show');
});