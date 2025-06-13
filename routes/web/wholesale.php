<?php

use App\Http\Controllers\WholesaleCustomerController;
use App\Http\Controllers\WholesaleOrderController;
use App\Models\WholesaleCustomer;
use App\Models\WholesaleOrder;

Route::view('/wholesale', 'wholesale.dashboard', [
	'customers_count' => WholesaleCustomer::count(),
	'orders_count'    => WholesaleOrder::count(),
])
     ->name('wholesale.dashboard');

Route::get('/wholesale-customers', [WholesaleCustomerController::class, 'index'])
     ->name('wholesale-customers.index');

Route::post('/wholesale-customers', [WholesaleCustomerController::class, 'store'])
	->name('wholesale-customers.store');

Route::get('/wholesale-customers/{wholesaleCustomer}', [WholesaleCustomerController::class, 'show'])
	->name('wholesale-customers.show');

Route::patch('/wholesale-customers/{wholesaleCustomer}', [WholesaleCustomerController::class, 'update'])
	->name('wholesale-customers.update');

Route::get('/wholesale-orders', [WholesaleOrderController::class, 'index'])
     ->name('wholesale-orders.index');

Route::get('/wholesale-customers/{wholesaleCustomer}/orders', [WholesaleOrderController::class, 'customerIndex'])
     ->name('wholesale-customers.orders.index');

Route::post('/wholesale-customers/{wholesaleCustomer}/orders', [WholesaleOrderController::class, 'customerStore'])
     ->name('wholesale-customers.orders.store');

Route::get('/wholesale-orders/{wholesaleOrder}', [WholesaleOrderController::class, 'show'])
	->name('wholesale-orders.show');
