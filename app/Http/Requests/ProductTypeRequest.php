<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductTypeRequest extends FormRequest
{

	protected function prepareForValidation():void
	{
		$this->validate([
			'variants'                 => 'nullable|array',
			'variants.label'           => 'nullable|string|max:24',
			'variants.options'         => 'nullable|array',
			'variants.options.*.key'   => 'nullable|string|max:16',
			'variants.options.*.value' => 'nullable|string|max:128',
			'variants.default'         => 'nullable|string',
		]);
		$variants = $this->array('variants');
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
		$this->merge([
			'variants'  => $variants,
			'is_bundle' => $this->boolean('is_bundle')
		]);
	}

	public function rules():array
	{
		return [
			'code'                     => 'nullable|string|max:16',
			'label'                    => 'nullable|string|max:255',
			'is_bundle'                => 'boolean',
			'variants'                 => 'nullable|array',
			'child_product_type_ids'   => 'nullable|array',
			'child_product_type_ids.*' => 'exists:product_types,id',
		];
	}

}
