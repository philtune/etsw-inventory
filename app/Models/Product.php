<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
		'can_stock'   => 'boolean',
		'stock'       => 'json',
		'is_archived' => 'boolean',
		'is_bundle'   => 'boolean',
	];

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
	 * @return BelongsToMany<Product,$this>
	 */
	public function parentProduct():BelongsToMany
	{
		return $this->belongsToMany(
			Product::class,
			'bundle_products',
			'child_product_id',
			'parent_product_id',
		);
	}

	/**
	 * @return BelongsToMany<Product,$this>
	 */
	public function childProducts():BelongsToMany
	{
		return $this->belongsToMany(
			Product::class,
			'bundle_products',
			'parent_product_id',
			'child_product_id',
		);
	}

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
	 * @return Attribute<string,never>
	 */
	public function title():Attribute
	{
		return Attribute::get(fn() => '[' . ( $this->productType?->code ?: '?' ) . '-' . ( $this->scent?->code ?: '?' ) . '] ' . $this->label)->shouldCache();
	}

}
