<?php

use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index'])
     ->name('products.index');

Route::post('/products', [ProductController::class, 'store'])
     ->name('products.store');

Route::get('/product-stock', [ProductController::class, 'stock'])
     ->name('products.stock');
