<?php

namespace App\Http\Controllers;

use App\Models\WholesaleOrderProduct;
use Illuminate\Http\Request;

class WholesaleOrderProductController extends Controller
{
	public function index()
	{
		return WholesaleOrderProduct::all();
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'wholesale_order_id' => ['required', 'exists:wholesale_orders'],
		]);

		return WholesaleOrderProduct::create($data);
	}

	public function show(WholesaleOrderProduct $wholesaleOrderProduct)
	{
		return $wholesaleOrderProduct;
	}

	public function update(Request $request, WholesaleOrderProduct $wholesaleOrderProduct)
	{
		$data = $request->validate([
			'wholesale_order_id' => ['required', 'exists:wholesale_orders'],
		]);

		$wholesaleOrderProduct->update($data);

		return $wholesaleOrderProduct;
	}

	public function destroy(WholesaleOrderProduct $wholesaleOrderProduct)
	{
		$wholesaleOrderProduct->delete();

		return response()->json();
	}
}
