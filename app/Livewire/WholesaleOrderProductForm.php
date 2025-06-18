<?php

namespace App\Livewire;

use App\Models\WholesaleOrderProduct;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WholesaleOrderProductForm extends Component
{
	public $wholesale_order_product_id;
	public $product_id;
	public $product_options;
	public int $quantity = 0;
	public $price_per_unit;
	public $total_adjustment;

	public function mount(WholesaleOrderProduct $wholesaleOrderProduct):void
	{
		$this->wholesale_order_product_id = $wholesaleOrderProduct->id;
		$this->product_id = $wholesaleOrderProduct->product_id;
		$this->quantity = $wholesaleOrderProduct->quantity;
		$this->price_per_unit = $wholesaleOrderProduct->price_per_unit;
		$this->total_adjustment = $wholesaleOrderProduct->total_adjustment;
	}

	public function render():View
	{
		return view('livewire.wholesale-order-product-form', [
			'wholesaleOrderProduct' => WholesaleOrderProduct::find($this->wholesale_order_product_id),
		]);
	}

	public function updatedProductId():void
	{
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['product_id' => $this->product_id ?: null]);
	}

	public function updatedQuantity():void
	{
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['quantity' => $this->quantity]);
		$this->dispatch('refresh')->to(WholesaleOrderForm::class);
	}

	public function updatedPricePerUnit():void
	{
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['price_per_unit' => $this->price_per_unit]);
		$this->dispatch('refresh')->to(WholesaleOrderForm::class);
	}

	public function updatedTotalAdjustment():void
	{
		WholesaleOrderProduct::find($this->wholesale_order_product_id)->update(['total_adjustment' => $this->total_adjustment]);
		$this->dispatch('refresh')->to(WholesaleOrderForm::class);
	}

}
