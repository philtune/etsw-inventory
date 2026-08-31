<?php

namespace App\Models;

use Database\Factories\ProductTypeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
	/** @use HasFactory<ProductTypeFactory> */
	use HasFactory;
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];

	/**
	 * @return Attribute<string,never>
	 */
	public function title():Attribute
	{
		return Attribute::get(fn() => "$this->code - $this->label")->shouldCache();
	}

	/**
	 * @return HasMany<Product>
	 */
	public function products():HasMany
	{
		return $this->hasMany(Product::class);
	}

	/**
	 * @return HasManyThrough<ProductAggregate,Product,$this>
	 */
	public function productAggregates():HasManyThrough
	{
		return $this->through('products')->has('productAggregate');
	}

	/**
	 * @return HasManyThrough<EtsyListing,Product,$this>
	 */
	public function etsyListings():HasManyThrough
	{
		return $this->through('products')->has('etsyListings');
	}

	/**
	 * @return HasManyThrough<EtsyTransaction,EtsyListing,$this>
	 */
	public function etsyTransactions():HasManyThrough
	{
		return $this->through('etsyListings')->has('etsyTransactions');
	}

	/**
	 * @return HasMany<ProductTypeVariant,$this>
	 */
	public function variants():HasMany
	{
		return $this->hasMany(ProductTypeVariant::class);
	}

	/**
	 * @return BelongsTo<ProductTypeVariant,$this>
	 */
	public function defaultVariant():BelongsTo
	{
		return $this->belongsTo(ProductTypeVariant::class, 'product_type_variant_id');
	}

}
