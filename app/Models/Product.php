<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];
	protected $casts = [
		'stock'            => 'integer',
		'is_archived'      => 'boolean',
		'is_bundle'        => 'boolean',
		'stock_updated_at' => 'datetime',
	];

	protected static function booted():void
	{
		static::creating(function (self $self) {
			if ( $self->is_bundle ) {
				$self->product_type_id = null;
				$self->scent_id = null;
			}
		});
		static::updating(function (self $self) {
			if ( $self->is_bundle ) {
				$self->product_type_id = null;
				$self->scent_id = null;
			}
			if ( $self->isDirty('product_type_id') ) {
				$self
					->variantStocks()
					->each(fn(ProductVariantStock $productVariantStock) => $productVariantStock->delete());
			}
		});
	}

	/**
	 * @return BelongsTo<Scent,$this>
	 */
	public function scent():BelongsTo
	{
		return $this->belongsTo(Scent::class);
	}

	/**
	 * @return BelongsTo<ProductType,$this>
	 */
	public function productType():BelongsTo
	{
		return $this->belongsTo(ProductType::class);
	}

	/**
	 * @return HasMany<ProductBundleItem,$this>
	 */
	public function bundleItems():HasMany
	{
		return $this->hasMany(ProductBundleItem::class);
	}

	//	/**
	//	 * @return HasMany<ProductBundleItem,$this>
	//	 */
	//	public function bundleParentItems():HasMany
	//	{
	//		return $this->hasMany(ProductBundleItem::class, 'child_product_id');
	//	}

	/**
	 * @return HasMany<EtsyListing,$this>
	 */
	public function etsyListings():HasMany
	{
		return $this->hasMany(EtsyListing::class);
	}

	/**
	 * @return HasManyThrough<EtsyTransaction,EtsyListing,$this>
	 */
	public function etsyTransactions():HasManyThrough
	{
		return $this->through('etsyListings')->has('etsyTransactions');
	}

	/**
	 * @return HasMany<WholesaleOrderProduct,$this>
	 */
	public function wholesaleOrderProducts():HasMany
	{
		return $this->hasMany(WholesaleOrderProduct::class);
	}

	/**
	 * @return HasOne<ProductAggregate,$this>
	 */
	public function productAggregate():HasOne
	{
		return $this->hasOne(ProductAggregate::class);
	}

	/**
	 * @return HasMany<ProductVariantStock,$this>
	 */
	public function variantStocks():HasMany
	{
		return $this->hasMany(ProductVariantStock::class);
	}

	public function getVariantStock(ProductTypeVariant $productTypeVariant):?ProductVariantStock
	{
		return $this
			->variantStocks
			->where('product_type_variant_id', $productTypeVariant->id)
			->first();
	}

	public function getVariantStockCount(ProductTypeVariant $productTypeVariant):int
	{
		return $this
			->getVariantStock($productTypeVariant)
			?->stock ?: 0;
	}

	/**
	 * @return Attribute<int,never>
	 */
	public function bundleStock():Attribute
	{
		return Attribute::get(
			fn() => $this->is_bundle ?
				$this
					->bundleItems
					->min(fn(ProductBundleItem $productBundleItem) => $productBundleItem
						->childProduct
						->getVariantStockCount($productBundleItem->productTypeVariant)) :
				null
		)->shouldCache();
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function title():Attribute
	{
		return Attribute::get(fn() => '[' . ( $this->productType?->code ?: '?' ) . '-' . ( $this->scent?->code ?: '?' ) . '] ' . $this->label)->shouldCache();
	}

}
