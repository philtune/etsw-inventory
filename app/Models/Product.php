<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];

	protected function casts():array
	{
		return [
			'is_archived' => 'boolean',
			'meta'        => 'json',
		];
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

}
