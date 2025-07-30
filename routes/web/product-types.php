<?php

use App\Http\Controllers\ProductTypeController;

Route::get('/product-types', [ProductTypeController::class, 'index'])
     ->name('product-types.index');

Route::post('/product-types', [ProductTypeController::class, 'store'])
     ->name('product-types.store');

Route::patch('/product-types/{productType}', [ProductTypeController::class, 'update'])
     ->name('product-types.update');

Route::delete('/product-types/{scent}', [ProductTypeController::class, 'delete'])
     ->name('product-types.delete');
