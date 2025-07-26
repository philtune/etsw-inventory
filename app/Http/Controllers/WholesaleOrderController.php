<?php

namespace App\Http\Controllers;

use App\Models\WholesaleCustomer;
use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderProduct;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WholesaleOrderController extends Controller
{
	public function index():View
	{
		return $this->customerIndex(null);
	}

	public function customerIndex(?WholesaleCustomer $wholesaleCustomer):View
	{
		return view('wholesale.orders.index', [
			'pageTitle'                  => 'Wholesale Orders' . ( $wholesaleCustomer ? ' for ' . $wholesaleCustomer->name : '' ),
			'wholesaleCustomer'          => $wholesaleCustomer,
			'wholesaleOrders'            => WholesaleOrder
				::query()
				->when(
					$wholesaleCustomer,
					fn(Builder $query) => $query->where('wholesale_customer_id', $wholesaleCustomer->id),
					fn(Builder $query) => $query->with('wholesaleCustomer')
				)
				->withCount('wholesaleOrderProducts')
				->withSum('wholesaleOrderProducts', 'quantity')
				->selectSub(WholesaleOrderProduct
					::whereRaw('wholesale_orders.id = wholesale_order_products.wholesale_order_id')
					->selectRaw('sum(price_per_unit * quantity)'),
					'items_grand_total'
				)
				->orderByDesc('ordered_at')
				->get(),
			'wholesale_customer_options' => WholesaleCustomer
				::query()
				->orderBy('name')
				->withCount('wholesaleOrders')
				->get(['id', 'name'])
				->reduce(fn(array $c, WholesaleCustomer $wholesaleCustomer) => $c + [
						$wholesaleCustomer->id => "$wholesaleCustomer->name ($wholesaleCustomer->wholesale_orders_count)"
					], []),
		]);
	}

	public function store(Request $request)
	{
		$request->validate([
			'wholesale_customer_id' => 'required|exists:wholesale_customers,id',
		]);
		return $this->customerStore(WholesaleCustomer::find($request->wholesale_customer_id));
	}

	public function customerStore(WholesaleCustomer $wholesaleCustomer):RedirectResponse
	{
		$wholesaleOrder = $wholesaleCustomer->wholesaleOrders()->create([
			'ordered_at' => now()
		]);
		return redirect()->route('wholesale-orders.show', $wholesaleOrder);
	}

	public function show(WholesaleOrder $wholesaleOrder):View
	{
		return view('wholesale.orders.show', [
			'wholesaleOrder' => $wholesaleOrder,
		]);
	}

	public function delete(WholesaleOrder $wholesaleOrder)
	{
		$wholesaleOrder->delete();

		return redirect()
			->route('wholesale-customers.orders.index', $wholesaleOrder->wholesale_customer_id)
			->with('toast', 'Order deleted!');
	}
}
