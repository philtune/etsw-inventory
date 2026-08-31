<?php

namespace App\Models;

use Database\Factories\WholesaleOrderFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesaleOrder extends Model
{
	/** @use HasFactory<WholesaleOrderFactory> */
	use HasFactory;
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];
	protected $casts = [
		'ordered_at'   => 'datetime',
	];

	/**
	 * @return BelongsTo<WholesaleCustomer,$this>
	 */
	public function wholesaleCustomer():BelongsTo
	{
		return $this->belongsTo(WholesaleCustomer::class);
	}

	/**
	 * @return HasMany<WholesaleOrderProduct,$this>
	 */
	public function wholesaleOrderProducts():HasMany
	{
		return $this->hasMany(WholesaleOrderProduct::class);
	}

	/**
	 * @return Attribute<float,never>
	 */
	public function grandTotal():Attribute
	{
		return Attribute::get(function () {
			return $this
				->wholesaleOrderProducts()
				->selectRaw('sum((price_per_unit * quantity) + total_adjustment) as items_grand_total')
				->first()
				->items_grand_total;
		})->shouldCache();
	}

}
