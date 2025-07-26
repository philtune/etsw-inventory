<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EtsyTransaction extends Model
{
	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'price'            => 'json',
		'shipping_cost'    => 'json',
		'variations'       => 'json',
		'product_data'     => 'json',
		'created_at'       => 'datetime',
		'paid_at'          => 'datetime',
		'shipped_at'       => 'datetime',
		'expected_ship_at' => 'datetime',
		'variation'        => 'json',
	];

	/**
	 * @return BelongsTo<EtsyReceipt,$this>
	 */
	public function etsyReceipt():BelongsTo
	{
		return $this->belongsTo(EtsyReceipt::class);
	}

	public function etsyListing():BelongsTo
	{
		return $this->belongsTo(EtsyListing::class, 'listing_id', 'listing_id');
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function subtotal():Attribute
	{
		return Attribute::get(fn() => number_format($this->quantity * $this->price['amount'] / $this->price['divisor'], 2))->shouldCache();
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function adjustments():Attribute
	{
		return Attribute::get(fn() => number_format($this->buyer_coupon, 2))->shouldCache();
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function total():Attribute
	{
		return Attribute::get(fn() => number_format($this->quantity * $this->price['amount'] / $this->price['divisor'] - $this->buyer_coupon, 2))->shouldCache();
	}

}
