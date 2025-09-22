<?php

namespace App\Livewire\ProductTypes;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\ProductType;
use App\Models\ProductTypeVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;

/**
 * @extends IndexTableComponent<ProductType>
 */
class IndexTable extends IndexTableComponent
{

	public int $perPage = 20;
	protected array $searchColumns = [
		'label',
		'code'
	];
	#[Url]
	public string $order_column = 'total_revenue';
	#[Url]
	public bool $order_desc = true;

	protected function getCustomOrders():array
	{
		return [
			'initial' => fn(Builder $query) => $query
				->orderBy('label'),
		];
	}

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,ProductType> $collection */
		$collection = $this->collection();
		return view('product-types.index-table', [
			'collection' => $collection,
		]);
	}

	protected function query():Builder
	{
		return ProductType
			::query()
			->withCount(['products', 'etsyListings', 'variants'])
			->withSum('productAggregates as total_revenue', 'total_revenue');
	}

	public function store(array $formData):void
	{
		parse_str(http_build_query($formData), $formData);
		$productType = ProductType::create([
			'label'         => $formData['label'],
			'code'          => $formData['code'],
			'variant_label' => $formData['variant_label'] ?? null,
		]);
		foreach ( ( $formData['variants'] ?? [] ) as $key => $config ) {
			$productTypeVariant = $productType->variants()->create([
				'label'       => $config['label'],
				'aliases'     => $config['aliases'],
				'is_archived' => $config['is_archived'],
			]);
			if ( $formData['variant_default'] == $key ) {
				$productType->update([
					'product_type_variant_id' => $productTypeVariant->id,
				]);
			}
		}
		$this->dispatch('toast', 'Product type created!', '--success');
	}

	public function update(ProductType $productType, array $formData):void
	{
		parse_str(http_build_query($formData), $formData);
		if ( !array_key_exists('variants', $formData) ) {
			$productType->variants()->delete();
		} else {
			$productType
				->variants()
				->whereNotIn('id', array_keys($formData['variants']))
				->delete();
		}
		foreach ( ( $formData['variants'] ?? [] ) as $key => $config ) {
			if ( is_int($key) ) {
				$productTypeVariant = $productType
					->variants()
					->create([
						'label'       => $config['label'],
						'aliases'     => $config['aliases'],
						'is_archived' => !empty($config['is_archived']),
					]);
			} else {
				$productTypeVariant = ProductTypeVariant::find($key);
				$productTypeVariant
					->update([
						'label'       => $config['label'],
						'aliases'     => $config['aliases'],
						'is_archived' => !empty($config['is_archived']),
					]);
			}
			if ( $formData['variant_default'] == $key ) {
				$productType
					->defaultVariant()
					->associate($productTypeVariant)
					->save();
			}
		}
		$productType->update([
			'label'         => $formData['label'],
			'code'          => $formData['code'],
			'variant_label' => $formData['variant_label'] ?? null,
		]);
		$this->dispatch('toast', 'Product type updated!', '--success');
	}

	public function delete(ProductType $productType):void
	{
		$productType->delete();
		$this->dispatch('toast', 'Product type deleted!', '--success');
	}

	public function restore(string $product_type_id):void
	{
		$product = ProductType::withTrashed()->findOrFail($product_type_id);
		$product->restore();
		$this->dispatch('toast', 'Product type restored!', '--success');
	}

	public function forceDelete(string $product_type_id):void
	{
		$product = ProductType::withTrashed()->findOrFail($product_type_id);
		$product->forceDelete();
		$this->dispatch('toast', 'Product type permanently deleted!', '--success');
	}

}
