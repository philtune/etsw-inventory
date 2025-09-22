<?php

namespace App\Livewire\Products;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\EtsyTransaction;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class StockTable2 extends IndexTableComponent
{

	public int $perPage = 100;
	public string $order_column = 'revenue';
	public bool $order_desc = true;

	/**
	 * @inheritDoc
	 */
	protected function query():Builder
	{
		return Product
			::query()
			->select([
				'products.id',
				'products.label',
				'products.is_bundle',
				'product_types.code as product_type_code',
				'scents.code as scent_code',
				'revenue' => EtsyTransaction
					::query()
					->selectRaw('sum(etsy_transactions.price - etsy_transactions.shop_coupon)')
					->leftJoin('etsy_listings', 'etsy_listings.id', '=', 'etsy_transactions.etsy_listing_id')
					->where('etsy_listing_id', '=', \DB::raw('etsy_transactions.etsy_listing_id'))
					->where('etsy_listings.product_id', '=', \DB::raw('products.id'))
					->where('etsy_transactions.created_at', '>', now()->subYear())
				//					fn($query) => $query
				//					->selectRaw('sum(etsy_transactions.price - etsy_transactions.shop_coupon)')
			])
			->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
			->leftJoin('scents', 'scents.id', '=', 'products.scent_id')
			//			->leftJoin('etsy_listings', 'etsy_listings.product_id', '=', 'products.id')
			//			->leftJoin('etsy_transactions', 'etsy_transactions.etsy_listing_id', '=', 'etsy_listings.id')
			->where('products.is_archived', false)
			->with([
				'etsyListings' => fn(HasMany $query) => $query
					->orderBy('is_archived')
					->orderBy('state_enum'),
			])//			->ddRawSql()
			;
	}

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Product> $collection */
		$collection = $this->collection();
		return view('products.stock-table2', [
			'collection' => $collection,
		]);
	}
}
