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
		'created_at' => 'datetime',
		'variation'  => 'json',
		'price'      => 'json',
		//		'shipping_cost'    => 'json',
		'variations' => 'json',
		//		'product_data'     => 'json',
		//		'paid_at'          => 'datetime',
		//		'shipped_at'       => 'datetime',
		//		'expected_ship_at' => 'datetime',
		'meta'       => 'json',
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
		return $this->belongsTo(EtsyListing::class, 'etsy_listing_id', 'id');
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function subtotal():Attribute
	{
		return Attribute::get(fn() => number_format($this->quantity * $this->price, 2))->shouldCache();
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function adjustments():Attribute
	{
		return Attribute::get(fn() => number_format($this->shop_coupon, 2))->shouldCache();
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function total():Attribute
	{
		return Attribute::get(fn() => number_format($this->quantity * $this->price - $this->shop_coupon, 2))->shouldCache();
	}

	public static function getVariation(?string $etsy_listing_id, string $variations):?string
	{
		if ( $etsy_listing_id && $etsyListing = EtsyListing::find($etsy_listing_id) ) {
			foreach ( ( $etsyListing->product?->productType?->variants ?: [] ) as $productTypeVariant ) {
				foreach ( explode(',', $productTypeVariant->aliases) as $alias ) {
					if ( str_contains($variations, '"formatted_value":"' . $alias . '"') ) {
						return $productTypeVariant->label;
					}
				}
			}
			return $etsyListing->product?->productType?->defaultVariant?->label;
		}
		return null;
	}

}
