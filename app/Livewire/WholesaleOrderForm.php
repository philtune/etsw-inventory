<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\WholesaleCustomer;
use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderProduct;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class WholesaleOrderForm extends Component
{
	#[Locked]
	public $wholesale_order_id;
	#[Locked]
	public $wholesale_customer_options;
	public $wholesale_customer_id;
	public $ordered_at;
	public $notes;
	public $invoice_url;
	public $product_options;

	public $listeners = ['refresh', 'moveUpProduct', 'moveDownProduct', 'deleteProduct', 'copyProduct'];

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
		$this->invoice_url = $wholesaleOrder->invoice_url;
		$this->product_options = Product
			::all()
			->reduce(fn(array $c, Product $product) => $c + [
					$product->id => $product->title
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
				->orderBy('position')
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

	public function updatedInvoiceUrl():void
	{
		WholesaleOrder::find($this->wholesale_order_id)->update(['invoice_url' => $this->invoice_url]);
	}

	public function updated():void
	{
		$this->dispatch('toast', 'Updated!');
	}

	public function addProduct():void
	{
		$wholesaleOrder = WholesaleOrder::find($this->wholesale_order_id, 'id');
		$wholesaleOrder->wholesaleOrderProducts()->create([
			'position' => $wholesaleOrder->wholesaleOrderProducts()->max('position') + 1,
		]);
		$this->dispatch('toast', 'Updated!');
	}

	public function copyProduct(string $wholesale_order_product_id):void
	{
		$wholesaleOrder = WholesaleOrder::find($this->wholesale_order_id, 'id');
		$wholesaleOrder
			->wholesaleOrderProducts()
			->find($wholesale_order_product_id)
			?->replicate()
			->fill([
				'position' => $wholesaleOrder->wholesaleOrderProducts()->max('position') + 1,
			])
			->save();
		$this->dispatch('toast', 'Row copied!');
	}

	public function moveUpProduct(string $wholesale_order_product_id):void
	{
		$wholesaleOrder = WholesaleOrder::find($this->wholesale_order_id, 'id');
		$wholesaleOrderProducts = $wholesaleOrder
			->wholesaleOrderProducts()
			->orderBy('position')
			->get(['id', 'position']);
		$me = $wholesaleOrderProducts
			->where('id', $wholesale_order_product_id)
			->first();
		if ( ( $my_pos = $wholesaleOrderProducts->search($me) ) > 0 ) {
			$sibling = $wholesaleOrderProducts[$my_pos - 1];
			$wholesaleOrderProducts[$my_pos - 1] = $me;
			$wholesaleOrderProducts[$my_pos] = $sibling;
			$wholesaleOrderProducts
				->each(fn($wholesaleOrderProduct, $position) => $wholesaleOrderProduct->update(['position' => $position]));
		}
		$this->dispatch('toast', 'Updated!');
	}

	public function moveDownProduct(string $wholesale_order_product_id):void
	{
		$wholesaleOrder = WholesaleOrder::find($this->wholesale_order_id, 'id');
		$wholesaleOrderProducts = $wholesaleOrder
			->wholesaleOrderProducts()
			->orderBy('position')
			->get(['id', 'position']);
		$me = $wholesaleOrderProducts
			->where('id', $wholesale_order_product_id)
			->first();
		if ( ( $my_pos = $wholesaleOrderProducts->search($me) ) < $wholesaleOrderProducts->max('position') ) {
			$sibling = $wholesaleOrderProducts[$my_pos + 1];
			$wholesaleOrderProducts[$my_pos + 1] = $me;
			$wholesaleOrderProducts[$my_pos] = $sibling;
			$wholesaleOrderProducts
				->each(fn($wholesaleOrderProduct, $position) => $wholesaleOrderProduct->update(['position' => $position]));
		}
		$this->dispatch('toast', 'Updated!');
	}

	public function deleteProduct(string $wholesale_order_product_id):void
	{
		WholesaleOrderProduct
			::where('id', $wholesale_order_product_id)
			->where('wholesale_order_id', $this->wholesale_order_id)
			->delete();
		$this->dispatch('toast', 'Row deleted!');
	}

}
