<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
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
	];

	/**
	 * @return BelongsTo<Receipt,$this>
	 */
	public function receipt():BelongsTo
	{
		return $this->belongsTo(Receipt::class);
	}
}
