<?php

use App\Http\Controllers\ScentController;

Route::get('/scents', [ScentController::class, 'index'])
     ->name('scents.index');

Route::post('/scents', [ScentController::class, 'store'])
     ->name('scents.store');

Route::patch('/scents/{scent}', [ScentController::class, 'update'])
     ->name('scents.update');

Route::delete('/scents/{scent}', [ScentController::class, 'delete'])
     ->name('scents.delete');
