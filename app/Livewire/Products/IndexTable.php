<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
	use WithPagination;

	#[Locked]
	public array $product_type_options;
	#[Locked]
	public array $scent_options;
	#[Url]
	public bool $archived = false;
	#[Url]
	public int $perPage = 50;
	#[Url]
	public string $search = '';
	#[Url]
	public string $order_column = 'products.label';
	#[Url]
	public bool $order_desc = false;

	public function render():View
	{
		$this->dispatch('render');
		return view('products.index-table', [
			'products' => Product
				::query()
				->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
				->leftJoin('scents', 'products.scent_id', '=', 'scents.id')
				->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc')
				->withCount('etsyListings')
				->when(
					$this->search,
					fn($query) => $query->where(fn(Builder $_query) => $_query
						->where('products.label', 'like', "%$this->search%")
						->orWhere('product_types.code', 'like', "%$this->search%")
						->orWhere('product_types.label', 'like', "%$this->search%")
						->orWhere('scents.code', 'like', "%$this->search%")
						->orWhere('scents.label', 'like', "%$this->search%"))
				)
				->paginate($this->perPage, 'products.*'),
		]);
	}

	public function create():void
	{
		$this->reset(['scent_id', 'product_type_id', 'label']);
		$this->dispatch('toast', 'Product added!');
	}

	public function orderBy(string $field, bool $desc_first = false):void
	{
		if ( $this->order_column === $field ) {
			$this->order_desc = !$this->order_desc;
		} else {
			$this->order_desc = $desc_first;
		}
		$this->order_column = $field;
		$this->resetPage();
	}

	public function updateProductType(string $product_id, ?string $product_type_id):void
	{
		Validator::make(compact('product_id', 'product_type_id'), [
			'product_id'      => 'required|exists:products,id',
			'product_type_id' => 'nullable|exists:product_types,id',
		]);
		Product::find($product_id)->update(['product_type_id' => $product_type_id]);
		$this->dispatch('toast', 'Product type updated', '--success');
	}

	public function orderByKey(string $key, bool $desc_first = false):void
	{
		if ( $field = match ( $key ) {
			'ended' => "etsy_listings.meta->>'$.ending_timestamp'",
			default => null
		} ) {
			if ( $this->order_column === $field ) {
				$this->order_desc = !$this->order_desc;
			} else {
				$this->order_desc = $desc_first;
			}
			$this->order_column = $field;
		}
		$this->resetPage();
	}

	public function updateScent(string $product_id, ?string $scent_id):void
	{
		$scent_id = $scent_id ?: null;
		Validator::validate(compact('product_id', 'scent_id'), [
			'product_id' => 'required|exists:products,id',
			'scent_id'   => 'nullable|exists:scents,id',
		]);
		Product::find($product_id)->update(['scent_id' => $scent_id]);
		$this->dispatch('toast', 'Scent updated', '--success');
	}

	public function updateLabel(string $product_id, ?string $label):void
	{
		Validator::validate(compact('product_id', 'label'), [
			'product_id' => 'required|exists:products,id',
			'label'      => 'nullable|string|max:255',
		]);
		Product::find($product_id)->update(['label' => $label]);
		$this->dispatch('toast', 'Label updated', '--success');
	}

	public function archive(string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'required|exists:products,id',
		]);
		Product::find($product_id)->update(['is_archived' => true]);
		$this->dispatch('toast', 'Product archived!', '--success');
	}

	public function unarchive(string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'required|exists:products,id',
		]);
		Product::find($product_id)->update(['is_archived' => false]);
		$this->dispatch('toast', 'Product unarchived!', '--success');
	}

	public function toggleIsArchived(string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'required|exists:products,id',
		]);
		$product = Product::find($product_id);
		$product->update(['is_archived' => !$product->is_archived]);
		$this->dispatch('toast', 'Product updated!', '--success');
	}

	public function toggleCanStock(string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'required|exists:products,id',
		]);
		$product = Product::find($product_id);
		$product->update(['can_stock' => !$product->can_stock]);
		$this->dispatch('toast', 'Product updated!', '--success');
	}

	public function delete(string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'required|exists:products,id',
		]);
		Product::find($product_id)->delete();
		$this->dispatch('toast', 'Product deleted!', '--success');
	}

	public function updatedSearch():void
	{
		$this->resetPage();
	}
}
