<?php

namespace App\Models;

use Database\Factories\ProductAggregateFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAggregate extends Model
{
	/** @use HasFactory<ProductAggregateFactory> */
	use HasFactory;
	use HasUuids;

	public $timestamps = false;
	protected $guarded = [];

	protected $casts = [
		'etsy_revenue'      => 'float',
		'wholesale_revenue' => 'float',
		'total_revenue'     => 'float',
	];

	/**
	 * @return BelongsTo<Product,$this>
	 */
	public function product():BelongsTo
	{
		return $this->belongsTo(Product::class);
	}
}
