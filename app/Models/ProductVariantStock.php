<?php

namespace App\Models;

use Database\Factories\ProductVariantStockFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariantStock extends Pivot
{
	/** @use HasFactory<ProductVariantStockFactory> */
	use HasFactory;
	use HasUuids;

	protected $table = 'product_variant_stocks';
	public $timestamps = false;
	protected $casts = [
		'stock'            => 'integer',
		'stock_updated_at' => 'datetime',
	];

	/**
	 * @return BelongsTo<Product,$this>
	 */
	public function product():BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	/**
	 * @return BelongsTo<ProductTypeVariant,$this>
	 */
	public function productTypeVariant():BelongsTo
	{
		return $this->belongsTo(ProductTypeVariant::class);
	}

}
