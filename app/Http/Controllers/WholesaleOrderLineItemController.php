<?php

namespace App\Http\Controllers;

use App\Models\WholesaleOrderLineItem;
use Illuminate\Http\Request;

class WholesaleOrderLineItemController extends Controller
{
	public function index()
	{
		return WholesaleOrderLineItem::all();
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'wholesale_order_id' => ['required', 'exists:wholesale_orders'],
		]);

		return WholesaleOrderLineItem::create($data);
	}

	public function show(WholesaleOrderLineItem $wholesaleOrderLineItem)
	{
		return $wholesaleOrderLineItem;
	}

	public function update(Request $request, WholesaleOrderLineItem $wholesaleOrderLineItem)
	{
		$data = $request->validate([
			'wholesale_order_id' => ['required', 'exists:wholesale_orders'],
		]);

		$wholesaleOrderLineItem->update($data);

		return $wholesaleOrderLineItem;
	}

	public function destroy(WholesaleOrderLineItem $wholesaleOrderLineItem)
	{
		$wholesaleOrderLineItem->delete();

		return response()->json();
	}
}
