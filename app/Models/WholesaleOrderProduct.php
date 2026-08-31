<?php

namespace App\Models;

use Database\Factories\WholesaleOrderProductFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesaleOrderProduct extends Model
{
	/** @use HasFactory<WholesaleOrderProductFactory> */
	use HasFactory;
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];

	protected $casts = [
		'variation' => 'json',
	];

	/**
	 * @return BelongsTo<WholesaleOrder,$this>
	 */
	public function wholesaleOrder():BelongsTo
	{
		return $this->belongsTo(WholesaleOrder::class);
	}

	/**
	 * @return BelongsTo<Product,$this>
	 */
	public function product():BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

}
