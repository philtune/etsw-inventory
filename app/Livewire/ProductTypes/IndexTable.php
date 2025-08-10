<?php

namespace App\Livewire\ProductTypes;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\ProductType;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;

/**
 * @extends IndexTableComponent<ProductType>
 */
class IndexTable extends IndexTableComponent
{

	public array $child_product_type_options;
	public int $perPage = 20;
	protected array $searchColumns = [
		'label',
		'code'
	];
	#[Url]
	public string $order_column = 'initial';

	protected function getCustomOrders():array
	{
		return [
			'initial' => fn(Builder $query) => $query
				->orderByDesc('is_bundle')
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
			->withCount(['products', 'etsyListings'])
			->withSum('productAggregates as total_revenue', 'total_revenue');
	}

}
