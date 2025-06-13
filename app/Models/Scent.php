<?php

namespace App\Models;

use Database\Factories\ScentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scent extends Model
{
	use HasUuids;
	use SoftDeletes;
	/** @use HasFactory<ScentFactory> */
	use HasFactory;

	protected $guarded = [];

	/**
	 * @return HasMany<Product,$this>
	 */
	public function products():HasMany
	{
		return $this->hasMany(Product::class);
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
}
