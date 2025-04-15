<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
	public function index()
	{
		return ProductType::all();
	}

	public function store(Request $request)
	{
		$data = $request->validate([

		]);

		return ProductType::create($data);
	}

	public function show(ProductType $productType)
	{
		return $productType;
	}

	public function update(Request $request, ProductType $productType)
	{
		$data = $request->validate([

		]);

		$productType->update($data);

		return $productType;
	}

	public function destroy(ProductType $productType)
	{
		$productType->delete();

		return response()->json();
	}
}
