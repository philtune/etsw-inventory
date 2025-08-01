<?php

namespace App\Livewire\ProductTypes;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class VariantDefinition extends Component
{
	public bool $has_variants;
	public string $label = '';
	public array $options = [['key' => '', 'value' => '']];
	public string $default = '';

	public function mount(?array $variants):void
	{
		$this->has_variants = (bool) $variants;
		if ( $variants ) {
			$this->label = $variants['label'];
			$this->options = collect($variants['options'])
				->reduce(fn(array $c, $value, $key) => array_merge($c, [[
					'key'   => $key,
					'value' => $value
				]]), []);
			$this->default = $variants['default'];
		}
	}

	public function render():View
	{
		return view('product-types.variant-definition', [
			'option_options' => array_reduce(
				array_filter(
					$this->options,
					fn($option) => $option['key'],
				),
				fn(array $c, $option) => $c + [$option['key'] => $option['key']],
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
			'key'   => '',
			'value' => '',
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
