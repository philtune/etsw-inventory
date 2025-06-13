<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'dashboard'])
     ->name('home');

Route::group([], [
	base_path('/routes/web/scents.php'),
	base_path('/routes/web/product-types.php'),
	base_path('/routes/web/products.php'),
	base_path('/routes/web/etsy.php'),
	base_path('/routes/web/wholesale.php'),
]);
