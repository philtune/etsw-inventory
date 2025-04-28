<?php

use App\Http\Controllers\EtsyListingController;

Route::get('/etsy-listings', [EtsyListingController::class, 'index'])
     ->name('etsy-listings.index');

Route::get('/etsy-listings/import', [EtsyListingController::class, 'import'])
     ->name('etsy-listings.import');
