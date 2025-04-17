<?php

use App\Http\Controllers\ListingController;

Route::get('/listings', [ListingController::class, 'index'])
     ->name('listings.index');

Route::patch('/listings/{listing}', [ListingController::class, 'update'])
     ->name('listings.update');

Route::get('/listings/import', [ListingController::class, 'import'])
     ->name('listings.import');
