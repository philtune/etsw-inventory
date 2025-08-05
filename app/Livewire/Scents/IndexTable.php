<?php

namespace App\Livewire\Scents;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\Scent;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends IndexTableComponent<Scent>
 */
class IndexTable extends IndexTableComponent
{

	public array $child_product_type_options;
	public int $perPage = 32;
	protected array $searchColumns = [
		'label',
		'code'
	];
	public string $order_column = 'code';
	public bool $order_desc = false;

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Scent> $collection */
		$collection = $this->collection();
		return view('scents.index-table', [
			'collection' => $collection,
		]);
	}

	protected function query():Builder
	{
		return Scent
			::query()
			->withCount(['products', 'etsyListings'])
			->withSum('productAggregates as total_revenue', 'total_revenue');
	}

}
