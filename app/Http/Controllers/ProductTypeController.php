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
		$productTypes = ProductType
			::query()
			->orderByDesc('is_bundle')
			->orderBy('label')
			->get();
		return view('product-types.index', [
			'productTypes'               => $productTypes,
			'child_product_type_options' => $productTypes
				->where('is_bundle', false)
				->reduce(fn(array $c, ProductType $productType) => $c + [
						$productType->id => $productType->title
					], [])
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
		$request->validate([
			'child_product_type_ids'   => 'nullable|array',
			'child_product_type_ids.*' => 'exists:product_types,id',
		]);
		$productType->update($request
			->merge(['is_bundle' => $request->boolean('is_bundle')])
			->validate([
				'code'      => 'nullable|string|max:16',
				'label'     => 'nullable|string|max:255',
				'is_bundle' => 'boolean',
			]));
		$productType->childProductTypes()->sync($request->array('child_product_type_ids'));
		return back()->with('status', 'Product type updated!');
	}

	public function delete(ProductType $productType):RedirectResponse
	{
		$productType->delete();
		return back()->with('status', 'Product type deleted!');
	}

	public function restore(ProductType $productType):RedirectResponse
	{
		$productType->restore();
		return back()->with('status', 'Product type restored!');
	}

}
