<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Scent;

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

	public function stock()
	{
		return view('products.stock');
	}
}
