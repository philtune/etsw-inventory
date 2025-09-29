<?php

use App\Http\Controllers\EtsyApiController;
use App\Http\Controllers\EtsyListingController;
use App\Http\Controllers\EtsyTransactionController;
use App\Services\EtsyListingService;

Route::get('/etsy-api/api-redirect-url', [EtsyApiController::class, 'apiRedirectUrl'])
     ->name('etsy-api.api-redirect-url');

Route::get('/etsy-api/api-refresh-token', [EtsyApiController::class, 'apiRefreshToken'])
     ->name('etsy-api.api-refresh-token');

Route::get('/etsy-api/import-all', [EtsyApiController::class, 'importAll'])
     ->name('etsy-api.import-all');

Route::get('/etsy-api/import-receipts', [EtsyApiController::class, 'importReceipts'])
     ->name('etsy-api.import-receipts');

Route::get('/etsy-api/import-listings', [EtsyApiController::class, 'importListings'])
     ->name('etsy-api.import-listings');

Route::get('/etsy-api/import-inventory', function () {
	EtsyListingService::importAllInventory();
	return back()->with('toast', 'Etsy stock updated!');
})
     ->name('etsy-api.import-inventory');


Route::get('/etsy/listings', [EtsyListingController::class, 'index'])
     ->name('etsy.listings.index');

Route::get('/etsy/transactions', [EtsyTransactionController::class, 'index'])
     ->name('etsy.transactions.index');
