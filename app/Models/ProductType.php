<?php

namespace App\Models;

use Database\Factories\ProductTypeFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
	use HasUuids;
	use SoftDeletes;

	/** @use HasFactory<ProductTypeFactory> */
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'variants'  => 'array',
		'is_bundle' => 'boolean',
	];

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
	 * @return BelongsToMany<ProductType,$this>
	 */
	public function parentProductType():BelongsToMany
	{
		return $this->belongsToMany(
			ProductType::class,
			'bundle_product_types',
			'child_product_type_id',
			'parent_product_type_id',
		);
	}

	/**
	 * @return BelongsToMany<ProductType,$this>
	 */
	public function childProductTypes():BelongsToMany
	{
		return $this->belongsToMany(
			ProductType::class,
			'bundle_product_types',
			'parent_product_type_id',
			'child_product_type_id',
		);
	}

}
