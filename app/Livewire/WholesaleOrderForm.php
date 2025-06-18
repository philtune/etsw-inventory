<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\WholesaleCustomer;
use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderProduct;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WholesaleOrderForm extends Component
{
	public $wholesale_order_id;
	public array $wholesale_customer_options;
	public $wholesale_customer_id;
	public $ordered_at;
	public $notes;
	public $product_options;

	public $listeners = ['refresh'];

	public function mount(WholesaleOrder $wholesaleOrder):void
	{
		$this->wholesale_order_id = $wholesaleOrder->id;
		$this->wholesale_customer_options = WholesaleCustomer
			::query()
			->orderBy('name')
			->get(['id', 'name'])
			->reduce(fn(array $c, WholesaleCustomer $wholesaleCustomer) => $c + [
					$wholesaleCustomer->id => $wholesaleCustomer->name
				], []);
		$this->wholesale_customer_id = $wholesaleOrder->wholesale_customer_id;
		$this->ordered_at = $wholesaleOrder->ordered_at->format('Y-m-d');
		$this->notes = $wholesaleOrder->notes;
		$this->product_options = Product
			::all()
			->reduce(fn(array $c, Product $product) => $c + [
					$product->id => $product->label
				], []);
	}

	public function render():View
	{
		$wholesaleOrder = WholesaleOrder::find($this->wholesale_order_id);
		return view('livewire.wholesale-order-form', [
			'wholesaleOrder'         => $wholesaleOrder,
			'wholesaleCustomer'      => $this->wholesale_customer_id ?
				WholesaleCustomer::find($this->wholesale_customer_id) :
				null,
			'items_quantity'         => $wholesaleOrder
				->wholesaleOrderProducts()
				->sum('quantity'),
			'wholesaleOrderProducts' => $wholesaleOrder
				->wholesaleOrderProducts()
				->get(),
		]);
	}

	public function updatedWholesaleCustomerId():void
	{
		WholesaleOrder::find($this->wholesale_order_id)->update(['wholesale_customer_id' => $this->wholesale_customer_id ?: null]);
	}

	public function updatedOrderedAt():void
	{
		WholesaleOrder::find($this->wholesale_order_id)->update(['ordered_at' => $this->ordered_at]);
	}

	public function updatedNotes():void
	{
		WholesaleOrder::find($this->wholesale_order_id)->update(['notes' => $this->notes]);
	}

}
