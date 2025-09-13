<?php

namespace App\Livewire;

use App\Models\ProductTypeVariant;
use App\Models\WholesaleOrderProduct;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class WholesaleOrderProductForm extends Component
{
	#[Locked]
	public $wholesale_order_product_id;
	public $product_id;
	#[Locked]
	public $product_options;
	public $quantity = 0;
	public $price_per_unit;
	public $total_adjustment;
	public $notes;
	#[Locked]
	public $position;
	public $is_last;
	public $variation = [];

	public function mount(WholesaleOrderProduct $wholesaleOrderProduct):void
	{
		$this->wholesale_order_product_id = $wholesaleOrderProduct->id;
		$this->product_id = $wholesaleOrderProduct->product_id;
		$this->quantity = $wholesaleOrderProduct->quantity;
		$this->price_per_unit = $wholesaleOrderProduct->price_per_unit;
		$this->total_adjustment = $wholesaleOrderProduct->total_adjustment == 0 ? null : $wholesaleOrderProduct->total_adjustment;
		$this->notes = $wholesaleOrderProduct->notes ?: null;
		$this->position = $wholesaleOrderProduct->position;
		$this->variation = $wholesaleOrderProduct->variation;
	}

	public function render():View
	{
		$wholesaleOrderProduct = WholesaleOrderProduct::find($this->wholesale_order_product_id);
		return view('livewire.wholesale-order-product-form', [
			'wholesaleOrderProduct' => $wholesaleOrderProduct,
			'variant_options'       => $wholesaleOrderProduct
				->product
				?->productType
				->variants
				->reduce(fn(array $c, ProductTypeVariant $productTypeVariant) => $c + [
						$productTypeVariant->label => $productTypeVariant->aliases,
					], []),
		]);
	}

	public function updatedProductId():void
	{
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['product_id' => $this->product_id ?: null]);
	}

	public function updatedVariation():void
	{
		$wholesaleOrderProduct = WholesaleOrderProduct::find($this->wholesale_order_product_id);
		$wholesaleOrderProduct->update(['variation' => $this->variation]);
	}

	public function updatedQuantity():void
	{
		$this->quantity = $this->quantity ?: 0;
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['quantity' => $this->quantity]);
		$this->dispatch('refresh')->to(WholesaleOrderForm::class);
	}

	public function updatedPricePerUnit():void
	{
		$this->price_per_unit = floatval($this->price_per_unit ?: 0);
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['price_per_unit' => $this->price_per_unit]);
		$this->dispatch('refresh')->to(WholesaleOrderForm::class);
		$this->price_per_unit = number_format($this->price_per_unit, 2);
	}

	public function updatedTotalAdjustment():void
	{
		$this->total_adjustment = floatval($this->total_adjustment ?: 0);
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['total_adjustment' => $this->total_adjustment]);
		$this->dispatch('refresh')->to(WholesaleOrderForm::class);
		$this->total_adjustment = number_format($this->total_adjustment, 2);
	}

	public function updatedNotes():void
	{
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['notes' => $this->notes == '' ? null : $this->notes]);
	}

	public function updated():void
	{
		$this->dispatch('toast', 'Updated!', '--success');
	}

	public function moveUp():void
	{
		$this->dispatch('moveUpProduct', $this->wholesale_order_product_id)->to(WholesaleOrderForm::class);
	}

	public function moveDown():void
	{
		$this->dispatch('moveDownProduct', $this->wholesale_order_product_id)->to(WholesaleOrderForm::class);
	}

	public function delete():void
	{
		$this->dispatch('deleteProduct', $this->wholesale_order_product_id)->to(WholesaleOrderForm::class);
	}

	public function copy():void
	{
		$this->dispatch('copyProduct', $this->wholesale_order_product_id)->to(WholesaleOrderForm::class);
	}

}
