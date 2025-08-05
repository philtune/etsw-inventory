<?php

namespace App\Http\Controllers;

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

	public function store(Request $request):RedirectResponse
	{
		ProductType::create($request->validate([
			'code'  => [
				'nullable',
				'string',
				'max:16',
				Rule::unique('product_types')->withoutTrashed()
			],
			'label' => [
				'nullable',
				'string',
				'max:255',
				Rule::unique('product_types')->withoutTrashed()
			],
		]));
		return back()->with('toast', 'Product type created!');
	}

	public function update(Request $request, ProductType $productType):RedirectResponse
	{
		$request->validate([
			'variants'                 => 'nullable|array',
			'variants.label'           => 'nullable|string|max:24',
			'variants.options'         => 'nullable|array',
			'variants.options.*.key'   => 'nullable|string|max:16',
			'variants.options.*.value' => 'nullable|string|max:24',
			'variants.default'         => 'nullable|string',
		]);
		$variants = $request->array('variants');
		if ( empty($variants) ) {
			$variants = null;
		} else {
			$variants['options'] = array_reduce(
				array_filter($variants['options'], fn($option) => trim($option['key']) && trim($option['value'])),
				fn(array $c, $option) => $c + [
						trim($option['key']) => trim($option['value'])
					],
				[]
			);
		}
		$request->merge(['variants' => $variants]);
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
				'variants'  => 'nullable|array',
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
