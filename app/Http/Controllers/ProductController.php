<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index()
	{
		return view('products.index');
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'product_type_id' => ['nullable', 'exists:product_types'],
			'scent_id'        => ['required', 'exists:scents'],
			'label'           => ['required'],
			'is_archived'     => ['boolean'],
			'meta'            => ['required'],
		]);

		return Product::create($data);
	}

	public function show(Product $product)
	{
		return $product;
	}

	public function update(Request $request, Product $product)
	{
		$data = $request->validate([
			'product_type_id' => ['nullable', 'exists:product_types'],
			'scent_id'        => ['required', 'exists:scents'],
			'label'           => ['required'],
			'is_archived'     => ['boolean'],
			'meta'            => ['required'],
		]);

		$product->update($data);

		return $product;
	}

	public function destroy(Product $product)
	{
		$product->delete();

		return response()->json();
	}
}
