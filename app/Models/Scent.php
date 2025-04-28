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
	 * @return HasMany<Listing,$this>
	 */
	public function listings():HasMany
	{
		return $this->hasMany(Listing::class);
	}

	/**
	 * @return HasManyThrough<Transaction,Listing,$this>
	 */
	public function transactions():HasManyThrough
	{
		return $this->through('listings')->has('transactions');
	}
}
