<?php

use App\Http\Controllers\EtsyAuthorizationController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)
     ->name('home');

Route::get('/etsy-api/redirect-url', [EtsyAuthorizationController::class, 'redirectUrl'])
     ->name('etsy-api.redirect-url');

Route::get('/etsy-api/refresh-token', [EtsyAuthorizationController::class, 'refreshToken'])
     ->name('etsy-api.refresh-token');
