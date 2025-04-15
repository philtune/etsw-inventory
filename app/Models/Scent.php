<?php

namespace App\Models;

use Database\Factories\ScentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scent extends Model
{
	use HasUuids;
	use SoftDeletes;
	/** @use HasFactory<ScentFactory> */
	use HasFactory;

	/**
	 * @return HasMany<Product>
	 */
	public function products():HasMany
	{
		return $this->hasMany(Product::class);
	}
}
