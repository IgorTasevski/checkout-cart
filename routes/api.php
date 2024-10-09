<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SkuController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('products')
         ->name('products.')
         ->controller(ProductController::class)
         ->group(function () {
             Route::get('/', 'index');
             Route::post('/', 'store');
             Route::get('{id}', 'show');
             Route::put('{id}', 'update');
             Route::delete('{id}', 'destroy');
         });

    Route::prefix('skus')
         ->name('skus.')
         ->controller(SkuController::class)
         ->group(function () {
             Route::get('/', 'index');
             Route::post('/', 'store');
             Route::get('{id}', 'show');
             Route::put('{id}', 'update');
             Route::put('decrement/{id}', 'decrementStock');
             Route::delete('{id}', 'destroy');
         });


    Route::prefix('configurations')
         ->name('configurations.')
         ->controller(ConfigurationController::class)
         ->group(function () {
             Route::get('/', 'index');
             Route::post('/', 'store');
             Route::get('{id}', 'show');
             Route::put('{id}', 'update');
             Route::put('decrement/{id}', 'decrementStock');
             Route::delete('{id}', 'destroy');
         });

    Route::prefix('cart')
         ->name('cart.')
         ->controller(CartController::class)
         ->group(function () {
             Route::get('/', 'index');
             Route::post('add', 'store');
             Route::post('update', 'update');
         });

});
