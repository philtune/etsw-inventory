<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductTypeController extends Controller
{
	public function index():View
	{
		return view('product-types.index');
	}

	public function delete(ProductType $productType):RedirectResponse
	{
		$productType->delete();
		return back()->with('toast', 'Product type deleted!');
	}

	public function restore(ProductType $productType):RedirectResponse
	{
		$productType->restore();
		return back()->with('toast', 'Product type restored!');
	}

}
