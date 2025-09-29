<?php

namespace App\Livewire\Products\_archive;

use App\Models\Product;
use App\Models\ProductAggregate;
use App\Models\ProductTypeVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class StockTable extends Component
{
	use WithPagination;

	#[Url]
	public int $perPage = 64;
	#[Url]
	public string $search = '';
	#[Url]
	public string $order_column = 'product_aggregates.total_revenue';
	#[Url]
	public bool $order_desc = true;

	public function render():View
	{
		return view('products._ARCHIVE.stock-table', [
			'products' => Product
				::query()
				->select('products.*')
				->with([
					'productAggregate',
					'productType',
					'scent',
					'etsyListings' => fn(HasMany $query) => $query
						->orderBy('is_archived')
						->orderBy('state_enum'),
					'bundleItems'  => [
						'childProduct' => ['variantStocks'],
						'productTypeVariant'
					]
				])
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

	public function updateVariantStock(Product $product, ProductTypeVariant $productTypeVariant, int $stock):void
	{
		$product->variantStocks()->updateOrCreate(
			['product_type_variant_id' => $productTypeVariant->id],
			[
				'stock'            => $stock,
				'stock_updated_at' => now(),
			]
		);
		$this->dispatch('toast', 'Product stock updated!', '--success');
	}

	public function markVariantStockUpdated(Product $product, ProductTypeVariant $productTypeVariant):void
	{
		$product->variantStocks()->updateOrCreate(
			['product_type_variant_id' => $productTypeVariant->id],
			['stock_updated_at' => now(),]
		);
	}

	public function undoMarkVariantStockUpdated(Product $product, ProductTypeVariant $productTypeVariant):void
	{
		$product->variantStocks()->updateOrCreate(
			['product_type_variant_id' => $productTypeVariant->id],
			['stock_updated_at' => now()->subDays(10),]
		);
	}

	public function updateDefaultStock(Product $product, int $stock):void
	{
		$product->update([
			'stock'            => $stock,
			'stock_updated_at' => now(),
		]);
		$this->dispatch('toast', 'Product stock updated!', '--success');
	}

	public function markDefaultStockUpdated(Product $product):void
	{
		$product->update(['stock_updated_at' => now()]);
	}

	public function undoMarkDefaultStockUpdated(Product $product):void
	{
		$product->update(['stock_updated_at' => now()->subDays(10)]);
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
				DB::raw("quantity * price - shop_coupon")
			)
			->withSum('wholesaleOrderProducts as wholesale_order_products_qty', 'quantity')
			->withSum(
				'wholesaleOrderProducts as wholesale_revenue',
				DB::raw('quantity * price_per_unit')
			)
			->addSelect(DB::raw(<<<EOT
coalesce((select
	sum(quantity * price - shop_coupon)
	from `etsy_transactions`
	inner join `etsy_listings` on `etsy_listings`.`id` = `etsy_transactions`.`etsy_listing_id`
	where `products`.`id` = `etsy_listings`.`product_id`), 0)
+ coalesce((select
	sum(quantity * price_per_unit)
	from `wholesale_order_products`
	where `products`.`id` = `wholesale_order_products`.`product_id`
	and `wholesale_order_products`.`deleted_at` is null), 0)
as total_revenue
EOT
			))
			->where('products.is_archived', false)
			//			->where('products.is_bundle', false)
			->get();
		//		dd($aggregates->where('is_bundle', true)->toArray());
		$aggregates->each(function (Product $row) {
			ProductAggregate
				::updateOrCreate([
					'product_id' => $row->product_id,
				], $row->toArray());
		});
		$this->dispatch('toast', 'Aggregates updated!', '--success');
	}

}
