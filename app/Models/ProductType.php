<?php

namespace App\Models;

use Database\Factories\ProductTypeFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
	use HasUuids;
	use SoftDeletes;

	/** @use HasFactory<ProductTypeFactory> */
	use HasFactory;

	protected $casts = [
		'meta' => 'json',
	];

	/**
	 * @return HasMany<Product>
	 */
	public function products():HasMany
	{
		return $this->hasMany(Product::class);
	}
}
