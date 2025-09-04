<?php

namespace App\Livewire\Products;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * @extends IndexTableComponent<Product>
 */
class IndexTable extends IndexTableComponent
{
	use WithPagination;


	public array $child_product_options;
	#[Locked]
	public array $product_type_options;
	#[Locked]
	public array $scent_options;
	#[Url]
	public int $perPage = 50;
	public string $order_column = 'products.label';
	public bool $order_desc = false;
	#[Url]
	public bool $show_archived = false;
	protected array $searchColumns = [
		'products.label',
		'product_types.label',
		'scents.label',
	];

	public function mount():void
	{
		$this->calculate_options();
	}

	public function calculate_options():void
	{
		$this->child_product_options = Product
			::query()
			->orderBy('label')
			->where('is_bundle', false)
			->with(['productType', 'scent'])
			->get()
			->reduce(fn(array $c, Product $product) => $c + array_reduce(array_keys($product->productType->variants['options'] ?? ['default' => '']), fn($_c, $key) => $_c + [
						$product->id . '|' . $key => ( $product->is_archived ? '[ARCHIVED] ' : '' ) . $product->title . ' (' . $key . ')',
					], []), []);
	}

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Product> $collection */
		$collection = $this->collection();
		return view('products.index-table', [
			'collection'      => $collection,
			'bundle_products' => \DB
				::table('bundle_products')
				->whereIn('parent_product_id', $collection->pluck('id'))
				->get()
		]);
	}

	protected function query():Builder
	{
		return Product
			::query()
			->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
			->leftJoin('scents', 'products.scent_id', '=', 'scents.id')
			->select('products.*')
			->with([
				'etsyListings',
				'productType',
				'scent',
				'childProducts' => fn(BelongsToMany $query) => $query
					->with(['productType', 'scent'])
					->withPivot('variant')
			])
			->when(
				!$this->show_archived,
				fn(Builder $query) => $query->where('is_archived', false)
			);
	}

	private function rules():array
	{
		return [
			'product_type_id' => 'nullable|exists:product_types,id',
			'scent_id'        => 'nullable|exists:scents,id',
			'label'           => 'nullable|string|max:255',
			'can_stock'       => 'boolean',
			'is_bundle'       => 'boolean',
		];
	}

	public function store(array $formData):void
	{
		Product::query()->create(static::validated($formData, $this->rules()));
		$this->calculate_options();
		$this->dispatch('toast', 'Product created!', '--success');
	}

	public function update(Product $product, array $formData):void
	{
		static::validated($formData, [
			'child_product_ids'   => 'nullable|array',
			'child_product_ids.*' => 'string',
		]);
		$child_product_ids = array_reduce($formData['child_product_ids'] ?? [], fn(array $c, $str) => $c + [
				explode('|', $str)[0] => [
					'variant' => explode('|', $str)[1]
				]
			], []);
		static::validated(['ids' => array_keys($child_product_ids)], ['ids.*' => 'exists:products,id',]);
		$product->update(static::validated($formData, $this->rules()));
		$product->childProducts()->sync($child_product_ids);
		$this->calculate_options();
		$this->dispatch('toast', 'Product updated!', '--success');
	}

	public function delete(Product $product):void
	{
		$product->delete();
		$this->dispatch('toast', 'Product deleted!', '--success');
	}

	public function restore(string $product_id):void
	{
		$product = Product::withTrashed()->findOrFail($product_id);
		$product->restore();
		$this->dispatch('toast', 'Product restored!', '--success');
	}

	public function forceDelete(string $product_id):void
	{
		$product = Product::withTrashed()->findOrFail($product_id);
		$product->forceDelete();
		$this->dispatch('toast', 'Product permanently deleted!', '--success');
	}

	public function archive(Product $product):void
	{
		$product->update(['is_archived' => true]);
		$this->dispatch('toast', 'Product archived!', '--success');
	}

	public function unarchive(Product $product):void
	{
		$product->update(['is_archived' => false]);
		$this->dispatch('toast', 'Product unarchived!', '--success');
	}

}
