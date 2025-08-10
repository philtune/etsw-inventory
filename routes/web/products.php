<?php

use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index'])
     ->name('products.index');

Route::get('/products.stock', [ProductController::class, 'stock'])
     ->name('products.stock');
