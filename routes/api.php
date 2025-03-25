<?php

use App\Http\Controllers\ApiController;

Route::get('/listings', [ApiController::class, 'listings']);
