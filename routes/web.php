<?php

use App\Http\Controllers\EtsyAuthorizationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
     ->name('home');

Route::get('/etsy-api/redirect-url', [EtsyAuthorizationController::class, 'redirectUrl'])
     ->name('etsy-api.redirect-url');

Route::get('/etsy-api/refresh-token', [EtsyAuthorizationController::class, 'refreshToken'])
     ->name('etsy-api.refresh-token');

Route::post('/import-all', [HomeController::class, 'importAll'])
     ->name('import-all');

Route::get('/listings/import', [ListingController::class, 'import'])
     ->name('listings.import');

Route::get('/receipts/import', [ReceiptController::class, 'import'])
     ->name('receipts.import');

Route::get('/transactions/import', [TransactionController::class, 'import'])
     ->name('transactions.import');
