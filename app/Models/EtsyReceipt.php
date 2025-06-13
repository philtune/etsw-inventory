<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EtsyReceipt extends Model
{
	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'grandtotal'          => 'json',
		'subtotal'            => 'json',
		'total_price'         => 'json',
		'total_shipping_cost' => 'json',
		'total_tax_cost'      => 'json',
		'total_vat_cost'      => 'json',
		'discount_amt'        => 'json',
		'gift_wrap_price'     => 'json',
		'shipments'           => 'json',
		'refunds'             => 'json',
		'created_at'          => 'datetime',
		'updated_at'          => 'datetime',
	];

	const TYPE_ETSY_COM = 0;
	const TYPE_PATTERN_SHOP = 1;

	/**
	 * @return HasMany<EtsyTransaction,$this>
	 */
	public function etsyTransactions():HasMany
	{
		return $this->hasMany(EtsyTransaction::class);
	}

}
