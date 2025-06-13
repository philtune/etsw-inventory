<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Scent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
	use WithPagination;

	public array $scent_options;
	public array $product_type_options;
	#[Url]
	public bool $archived = false;
	#[Url]
	public int $perPage = 50;
	#[Url]
	public string $search = '';
	#[Url]
	public string $order_column = 'products.label';
	#[Url]
	public bool $order_desc = true;

	public function render():View
	{
		$this->dispatch('render');
		return view('products.index-table', [
			'products'       => Product
				::query()
				->where('products.is_archived', '=', $this->archived)
				->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
				->leftJoin('scents', 'products.scent_id', '=', 'scents.id')
				->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc')
				->withCount('etsyListings')
				->get('products.*'),
			'active_count'   => Product::query()->where('products.is_archived', false)->count(),
			'archived_count' => Product::query()->where('products.is_archived', true)->count(),
		]);
	}

	public function mount():void
	{
		$this->scent_options = Scent
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, Scent $scent) => $c + [
					$scent->id => "$scent->code - $scent->label"
				], []);
		$this->product_type_options = ProductType
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, ProductType $productType) => $c + [
					$productType->id => "$productType->code - $productType->label"
				], []);
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

	public function delete(string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'required|exists:products,id',
		]);
		Product::find($product_id)->delete();
		$this->dispatch('toast', 'Product deleted!', '--success');
	}
}
