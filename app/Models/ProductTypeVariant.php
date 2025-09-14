<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductTypeVariant extends Model
{
	use HasUuids;

	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'is_archived' => 'boolean',
	];

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
	public function productBundleItems():HasMany
	{
		return $this->hasMany(ProductBundleItem::class);
	}
}
