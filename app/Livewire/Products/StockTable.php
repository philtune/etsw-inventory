<?php

namespace App\Livewire\Products;

use App\Livewire\Concerns\LivewireTable;
use App\Models\EtsyListing;
use App\Models\EtsyTransaction;
use App\Models\Product;
use App\Models\ProductTypeVariant;
use App\Models\WholesaleOrderProduct;
use DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;

/**
 * @extends LivewireTable<Product>
 */
class StockTable extends LivewireTable
{

	public int $perPage = 100;
	public string $order_column = 'total_revenue';
	public bool $order_desc = true;
	protected array $searchColumns = [
		'products.label',
		'product_types.code',
		'product_types.label',
		'scents.code',
		'scents.label',
	];
	#[Url]
	public $include_archived = false;

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Product> $collection */
		$collection = $this->collection();
		return view('products.stock-table', [
			'collection' => $collection,
		]);
	}

	/**
	 * @inheritDoc
	 */
	protected function query():Builder
	{
		$ytd = now()->subYear()->toDateTimeString();
		return Product
			::query()
			->select([
				'products.*',
				'product_types.code as product_type_code',
				'scents.code as scent_code',
				'etsy_revenue'      => EtsyTransaction
					::query()
					->selectRaw('sum(etsy_transactions.price - etsy_transactions.shop_coupon)')
					->leftJoin('etsy_listings', 'etsy_listings.id', '=', 'etsy_transactions.etsy_listing_id')
					->where('etsy_listing_id', '=', DB::raw('etsy_transactions.etsy_listing_id'))
					->where('etsy_listings.product_id', '=', DB::raw('products.id'))
					->where('etsy_transactions.created_at', '>', $ytd),
				'wholesale_revenue' => WholesaleOrderProduct
					::query()
					->selectRaw('sum(wholesale_order_products.quantity * wholesale_order_products.price_per_unit - wholesale_order_products.total_adjustment)')
					->leftJoin('wholesale_orders', 'wholesale_orders.id', '=', 'wholesale_order_products.wholesale_order_id')
					->where('wholesale_order_products.product_id', '=', DB::raw('products.id'))
					->where('wholesale_orders.ordered_at', '>', $ytd),
				DB::raw(<<<EOT
COALESCE((SELECT
		SUM(etsy_transactions.price - etsy_transactions.shop_coupon)
	FROM `etsy_transactions`
	LEFT JOIN `etsy_listings` ON `etsy_listings`.`id` = `etsy_transactions`.`etsy_listing_id`
	WHERE etsy_transactions.etsy_listing_id = etsy_transactions.etsy_listing_id
		AND `etsy_listings`.`product_id` = products.id
		AND etsy_transactions.created_at > '$ytd'), 0) + COALESCE((
	SELECT
		sum(wholesale_order_products.quantity * wholesale_order_products.price_per_unit - wholesale_order_products.total_adjustment)
	FROM wholesale_order_products
	LEFT JOIN `wholesale_orders` ON wholesale_orders.id = wholesale_order_products.wholesale_order_id
	WHERE wholesale_order_products.product_id = products.id
		AND wholesale_orders.ordered_at > '$ytd'
), 0) AS total_revenue
EOT
				),
//				'bundle_stock2'      => ProductVariantStock
//					::query()
//					->selectRaw('MIN(product_variant_stocks.stock)')
//					->leftJoin('products as cp', 'cp.id', '=', 'product_variant_stocks.product_id')
//					->leftJoin('product_bundle_items', 'product_bundle_items.child_product_id', '=', 'cp.id')
//					->where('product_bundle_items.product_id', '=', DB::raw('products.id'))
//					->where('products.is_bundle', true),
			])
			->addBundleStock()
			->leftJoin('product_types', 'product_types.id', '=', 'products.product_type_id')
			->leftJoin('scents', 'scents.id', '=', 'products.scent_id')
			->when(
				!$this->include_archived,
				fn(Builder $query) => $query->where('products.is_archived', false)
			)
			->with([
				'etsyListings'                           => fn(HasMany $query) => $query
					->orderBy('is_archived')
					->orderBy('state_enum'),
				'variantStocks',
				'productType:id,product_type_variant_id' => ['variants'],
				'scent',
			]);
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

	public function update($product_id, $formData):void
	{
		parse_str(http_build_query($formData), $formData);
		$etsyListing = EtsyListing::find($formData['etsy_listing_id']);
		if ( $etsyListing->updateInventory($formData['variants_in_stock']) ) {
			$this->dispatch('toast', 'Inventory updated!', '--success');
		} else {
			$this->dispatch('toast', 'There was a problem updating inventory.', '--danger');
		}
	}

	public function import(EtsyListing $etsyListing):void
	{
		if ( $etsyListing->import() ) {
			$this->dispatch('toast', 'Etsy inventory imported!', '--success');
		} else {
			$this->dispatch('toast', 'There was a problem importing inventory.', '--danger');
		}
	}

}
