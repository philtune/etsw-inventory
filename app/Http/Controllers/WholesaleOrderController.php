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
			'pageTitle'         => 'Wholesale Orders' . ( $wholesaleCustomer ? ' for ' . $wholesaleCustomer->name : '' ),
			'wholesaleCustomer' => $wholesaleCustomer,
			'wholesaleOrders'   => WholesaleOrder
				::query()
				->when(
					!$wholesaleCustomer,
					fn(Builder $query) => $query->with('wholesaleCustomer')
				)
				->withCount('wholesaleOrderProducts')
				->selectSub(WholesaleOrderProduct
					::whereRaw('wholesale_orders.id = wholesale_order_products.wholesale_order_id')
					->selectRaw('sum(price_per_unit * quantity)'),
					'items_grand_total'
				)
				->orderBy('ordered_at')
				->get(),
		]);
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'customer' => ['required'],
		]);

		return WholesaleOrder::create($data);
	}

	public function customerStore(WholesaleCustomer $wholesaleCustomer):RedirectResponse
	{
		$wholesaleOrder = $wholesaleCustomer->wholesaleOrders()->create();
		return redirect()->route('wholesale-orders.show', $wholesaleOrder);
	}

	public function show(WholesaleOrder $wholesaleOrder):View
	{
		return view('wholesale.orders.show', [
			'wholesaleOrder' => $wholesaleOrder,
		]);
	}

	public function update(Request $request, WholesaleOrder $wholesaleOrder)
	{
		$data = $request->validate([
			'customer' => ['required'],
		]);

		$wholesaleOrder->update($data);

		return $wholesaleOrder;
	}

	public function destroy(WholesaleOrder $wholesaleOrder)
	{
		$wholesaleOrder->delete();

		return response()->json();
	}
}
