<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'dashboard'])
     ->name('home');

Route::view('/scents', 'scents.index')
     ->name('scents.index');

Route::group([], [
	base_path('/routes/web/product-types.php'),
	base_path('/routes/web/etsy.php'),
	base_path('/routes/web/wholesale.php'),
	function() {
		Route::view('/products', 'products.index')
		     ->name('products.index');

		Route::view('/products.stock', 'products.stock')
		     ->name('products.stock');
	}
]);

Route::view('test-map', 'test-map');
