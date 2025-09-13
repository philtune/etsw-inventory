<?php

namespace App\Livewire\ProductTypes;

use App\Models\ProductType;
use App\Models\ProductTypeVariant;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VariantDefinition extends Component
{
	public ?string $product_type_id = null;
	public bool $has_variants;
	public ?string $label;
	public array $options = [['label' => '', 'aliases' => '']];
	public ?string $default;

	public function mount($productType):void
	{
		/** @var ?ProductType $productType */
		$this->product_type_id = $productType?->id;
		$this->label = $productType?->variant_label;
		$this->has_variants = (bool) $productType?->variants->isNotEmpty();
		if ( $productType ) {
			$this->options = $productType->variants
				?->reduce(fn(array $c, ProductTypeVariant $productTypeVariant) => $c + [
						$productTypeVariant->id => [
							'label'   => $productTypeVariant->label,
							'aliases' => $productTypeVariant->aliases,
						]
					], []);
			$this->default = $productType->defaultVariant?->id;
		}
	}

	public function render():View
	{
		return view('product-types.variant-definition', [
			'option_options'                  => collect($this->options)
				->filter(fn($option) => $option['label'])
				->reduce(
					fn(array $c, $option, $i) => $c + [$i => $option['label']],
					[]
				)
		]);
	}

	public function add():void
	{
		$this->reset('label', 'options', 'default');
		$this->has_variants = true;
	}

	public function addOption():void
	{
		$this->options[] = [
			'label'   => '',
			'aliases' => '',
		];
	}

	public function removeOption($i):void
	{
		unset($this->options[$i]);
	}

	public function remove():void
	{
		$this->reset('has_variants');
	}
}
