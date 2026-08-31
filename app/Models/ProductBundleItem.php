<?php

namespace App\Models;

use Database\Factories\ProductBundleItemFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductBundleItem extends Model
{
	/** @use HasFactory<ProductBundleItemFactory> */
	use HasFactory;
	use HasUuids;

	public $timestamps = false;
	protected $guarded = [];

	/**
	 * @return BelongsTo<Product,$this>
	 */
	public function product():BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	/**
	 * @return BelongsTo<Product,$this>
	 */
	public function childProduct():BelongsTo
	{
		return $this->belongsTo(Product::class, 'child_product_id');
	}

	/**
	 * @return BelongsTo<ProductTypeVariant,$this>
	 */
	public function productTypeVariant():BelongsTo
	{
		return $this->belongsTo(ProductTypeVariant::class);
	}
}
