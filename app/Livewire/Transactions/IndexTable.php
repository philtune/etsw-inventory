<?php

namespace App\Livewire\Transactions;

use App\Models\EtsyTransaction;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
	use WithPagination;

	public function render():View
	{
		return view('transactions.index-table', [
			'etsyTransactions' => EtsyTransaction
				::query()
				->select('etsy_transactions.*')
				->leftJoin('etsy_listings', 'etsy_listings.id', 'etsy_transactions.etsy_listing_id')
				->leftJoin('products', 'products.id', 'etsy_listings.product_id')
				->leftJoin('product_types', 'product_types.id', 'products.product_type_id')
				->where('product_types.code', 'CPS')
				->where('etsy_transactions.variations', 'not like', '[]')
				->orderBy('created_at', 'desc')
				->with([
					'etsyListing' => fn($query) => $query->with([
						'product' => fn($query) => $query->with(
							'productType'
						)
					])
				])
				->paginate(100)
		]);
	}
}
