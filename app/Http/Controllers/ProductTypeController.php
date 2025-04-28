<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
	public function index():View
	{
		return view('product-types.index', [
			'productTypes' => ProductType
				::query()
				->orderBy('label')
				->get()
		]);
	}

	public function store(Request $request):RedirectResponse
	{
		ProductType::create($request->validate([
			'code'  => 'nullable|string|max:16',
			'label' => 'nullable|string|max:255',
		]));
		return back()->with('status', 'Product type created!');
	}

	public function update(Request $request, ProductType $productType):RedirectResponse
	{
		$productType->update($request->validate([
			'code'  => 'nullable|string|max:16',
			'label' => 'nullable|string|max:255',
		]));
		return back()->with('status', 'Product type updated!');
	}

	public function destroy(ProductType $productType):RedirectResponse
	{
		$productType->delete();
		return back()->with('status', 'Product type deleted!');
	}
}
