<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductAggregate;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StockTable extends Component
{
	use WithPagination;

	#[Url]
	public int $perPage = 50;
	#[Url]
	public string $search = '';
	#[Url]
	public string $order_column = 'product_aggregates.total_revenue';
	#[Url]
	public bool $order_desc = true;

	public function render():View
	{
		return view('products.stock-table', [
			'products' => Product
				::query()
				->select('products.*')
				->with(['productAggregate', 'productType', 'scent', 'etsyListings'])
				->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
				->leftJoin('scents', 'products.scent_id', '=', 'scents.id')
				->leftJoin('product_aggregates', 'products.id', '=', 'product_aggregates.product_id')
				->where('products.is_archived', false)
				->withCount('etsyListings')
				->when(
					$this->search,
					fn($query) => $query->where(fn(Builder $_query) => $_query
						->where('products.label', 'like', "%$this->search%")
						->orWhere('product_types.code', 'like', "%$this->search%")
						->orWhere('product_types.label', 'like', "%$this->search%")
						->orWhere('scents.code', 'like', "%$this->search%")
						->orWhere('scents.label', 'like', "%$this->search%")
					)
				)
				->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc')
				->paginate($this->perPage),
		]);
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

	public function updateInStock(Product $product, string $key, string $value):void
	{
		Validator::validate(compact('key', 'value'), [
			'key' => [
				'required',
				'string',
				Rule::in(array_keys($product->productType->variants['options'])),
			],
			'value' => 'nullable|numeric|min:0'
		]);
		$product->update(['variants_in_stock' => array_merge($product->variants_in_stock, [$key => $value ?: 0])]);
		$this->dispatch('toast', 'Product stock updated!', '--success');
	}

	public function updatedSearch():void
	{
		$this->resetPage();
	}

	public function updateAggregates():void
	{
		$aggregates = Product
			::query()
			->select('id as product_id')
			->withSum('etsyTransactions as etsy_transactions_qty', 'quantity')
			->withSum(
				'etsyTransactions as etsy_revenue',
				DB::raw("quantity * round(price->>'$.amount' / price->>'$.divisor', 2) - buyer_coupon")
			)
			->withSum('wholesaleOrderProducts as wholesale_order_products_qty', 'quantity')
			->withSum(
				'wholesaleOrderProducts as wholesale_revenue',
				DB::raw('quantity * price_per_unit')
			)
			->addSelect(DB::raw(<<<'EOF'
coalesce((select
	sum(quantity * round(price ->> '$.amount' / price ->> '$.divisor', 2) - buyer_coupon)
from `etsy_transactions`
inner join `etsy_listings` on `etsy_listings`.`listing_id` = `etsy_transactions`.`listing_id`
where `products`.`id` = `etsy_listings`.`product_id`), 0)
+
coalesce((select
	sum(quantity * price_per_unit)
from `wholesale_order_products`
where `products`.`id` = `wholesale_order_products`.`product_id`
	and `wholesale_order_products`.`deleted_at` is null), 0)
as total_revenue
EOF
			))
			->where('products.is_archived', false)
			->get();
		$aggregates->each(function (Product $product) {
			ProductAggregate
				::updateOrCreate([
					'product_id' => $product->product_id,
				], $product->toArray());
		});
		$this->dispatch('toast', 'Aggregates updated!', '--success');
	}
}
