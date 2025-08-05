<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Scent;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index()
	{
		return view('products.index', [
			'product_type_options' => ProductType
				::query()
				->orderBy('code')
				->get(['id', 'code', 'label'])
				->reduce(fn(array $c, ProductType $productType) => $c + [
						$productType->id => "$productType->code - $productType->label"
					], []),
			'scent_options'        => Scent
				::query()
				->orderBy('code')
				->get(['id', 'code', 'label'])
				->reduce(fn(array $c, Scent $scent) => $c + [
						$scent->id => "$scent->code - $scent->label"
					], []),
		]);
	}

	public function store(Request $request)
	{
		Product::create($request->validate([
			'product_type_id' => 'required|exists:product_types,id',
			'scent_id'        => 'required|exists:scents,id',
			'label'           => 'nullable|string|max:255',
		]));

		return back()->with('toast', 'Product added!');
	}

	public function stock()
	{
		return view('products.stock');
	}
}
