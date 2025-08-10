<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductTypeRequest;
use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductTypeController extends Controller
{
	public function index():View
	{
		return view('product-types.index', [
			'child_product_type_options' => ProductType
				::query()
				->orderByDesc('is_bundle')
				->orderBy('label')
				->withCount(['products', 'etsyListings'])
				->get()
				->where('is_bundle', false)
				->reduce(fn(array $c, ProductType $productType) => $c + [
						$productType->id => $productType->title
					], [])
		]);
	}

	public function store(ProductTypeRequest $request):RedirectResponse
	{
		$productType = ProductType::create($request->only([
			'code',
			'label',
			'is_bundle',
			'variants'
		]));
		$productType->childProductTypes()->sync($request->array('child_product_type_ids'));
		return back()->with('toast', 'Product type created!');
	}

	public function update(ProductTypeRequest $request, ProductType $productType):RedirectResponse
	{
		$productType->update($request->only([
			'code',
			'label',
			'is_bundle',
			'variants'
		]));
		$productType->childProductTypes()->sync($request->array('child_product_type_ids'));
		return back()->with('toast', 'Product type updated!');
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
