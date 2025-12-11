<?php

namespace App\Livewire\Products;

use App\Livewire\Concerns\LivewireTable;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductTypeVariant;
use App\Models\Scent;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * @extends LivewireTable<Product>
 */
class IndexTable extends LivewireTable
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
		$this->product_type_options = ProductType
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, ProductType $productType) => $c + [
					$productType->id => "$productType->code - $productType->label"
				], []);
		$this->scent_options = Scent
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, Scent $scent) => $c + [
					$scent->id => "$scent->code - $scent->label"
				], []);
	}

	public function calculate_options():void
	{
		$this->child_product_options = Product
			::query()
			->orderBy('is_archived')
			->orderBy('label')
			->where('is_bundle', false)
			->with(['productType:id,code', 'scent:id,code'])
			->get()
			->reduce(function (array $c, Product $childProduct) {
				return ( $productTypeVariants = $childProduct->productType?->variants )?->isNotEmpty() ?
					$c + $productTypeVariants
						->reduce(fn(array $_c, ProductTypeVariant $productTypeVariant) => $_c + [
								$childProduct->id . '|' . $productTypeVariant->id => ( $childProduct->is_archived ? '[ARCHIVED] ' : '' ) . $childProduct->title . ' (' . $productTypeVariant->label . ')',
							], []) :
					$c + [
						$childProduct->id . '|' => ( $childProduct->is_archived ? '[ARCHIVED] ' : '' ) . $childProduct->title . ' (default)',
					];
			}, []);
	}

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Product> $collection */
		$collection = $this->collection();
		return view('products.index-table', [
			'collection' => $collection
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
				'etsyListings' => fn(HasMany $query) => $query
					->orderBy('is_archived')
					->orderBy('state_enum'),
				'productType'  => [
					'variants' => fn(HasMany $query) => $query->where('is_archived', false)
				],
				'scent',
				'bundleItems'  => [
					'productTypeVariant',
					'childProduct'
				],
				'variantStocks'
			])
			->when(
				!$this->show_archived,
				fn(Builder $query) => $query->where('is_archived', false)
			);
	}

	private function rules():array
	{
		return [
			'product_type_id'  => 'nullable|exists:product_types,id',
			'scent_id'         => 'nullable|exists:scents,id',
			'label'            => 'nullable|string|max:255',
			'is_bundle'        => 'boolean',
			'is_made_to_order' => 'boolean',
			'notes'            => 'nullable|string|max:1024',
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
		$product->update(static::validated($formData, $this->rules()));

		static::validated($formData, [
			'child_product_ids'   => 'nullable|array',
			'child_product_ids.*' => 'string',
		]);
		$child_product_ids = array_reduce(
			array: $formData['child_product_ids'] ?? [],
			callback: fn(array $c, $str) => $c + [
					explode('|', $str)[0] => [
						'product_type_variant_id' => explode('|', $str)[1]
					]
				],
			initial: []
		);
		static::validated([
			'product_ids'              => array_keys($child_product_ids),
			'product_type_variant_ids' => array_column($child_product_ids, 'product_type_variant_id'),
		], [
			'product_ids.*'              => 'exists:products,id',
			'product_type_variant_ids.*' => 'exists:product_types_variants,id',
		]);
		$product
			->bundleItems()
			->syncUsing($child_product_ids, 'child_product_id');
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
