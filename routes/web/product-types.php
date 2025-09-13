<?php

use App\Http\Controllers\ProductTypeController;

Route::get('/product-types', [ProductTypeController::class, 'index'])
     ->name('product-types.index');

Route::delete('/product-types/{productType}', [ProductTypeController::class, 'delete'])
     ->name('product-types.delete');

Route::patch('/product-types/{productType}/restore', [ProductTypeController::class, 'restore'])
     ->withTrashed()
     ->name('product-types.restore');
