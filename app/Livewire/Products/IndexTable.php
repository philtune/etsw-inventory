<?php

namespace App\Livewire\Products;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * @extends IndexTableComponent<Product>
 */
class IndexTable extends IndexTableComponent
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
	public string $order_column = 'products.label';
	public bool $order_desc = false;

	protected function query():Builder
	{
		return Product
			::query()
			->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
			->leftJoin('scents', 'products.scent_id', '=', 'scents.id')
			->select('products.*');
	}

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Product> $collection */
		$collection = $this->collection();
		return view('products.index-table', [
			'collection' => $collection,
		]);
	}

	public function create():void
	{
		$this->reset(['scent_id', 'product_type_id', 'label']);
		$this->dispatch('toast', 'Product added!');
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

}
