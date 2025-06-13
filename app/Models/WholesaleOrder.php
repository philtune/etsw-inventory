<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesaleOrder extends Model
{
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];
	protected $casts = [
		'ordered_at'   => 'datetime',
		'started_at'   => 'datetime',
		'completed_at' => 'datetime',
		'shipped_at'   => 'datetime',
		'pif_at'       => 'datetime',
	];

	/**
	 * @return BelongsTo<WholesaleCustomer,$this>
	 */
	public function wholesaleCustomer():BelongsTo
	{
		return $this->belongsTo(WholesaleCustomer::class);
	}

	/**
	 * @return HasMany<WholesaleOrderLineItem,$this>
	 */
	public function wholesaleOrderLineItems():HasMany
	{
		return $this->hasMany(WholesaleOrderLineItem::class);
	}

	const STATUS_ORDERED = 'ordered';
	const STATUS_STARTED = 'started';
	const STATUS_COMPLETED = 'completed';
	const STATUS_SHIPPED = 'shipped';
	const status_options = [
		self::STATUS_ORDERED   => 'Ordered',
		self::STATUS_STARTED   => 'Started',
		self::STATUS_COMPLETED => 'Completed',
		self::STATUS_SHIPPED   => 'Shipped',
	];

	const DELIVERY_METHOD_SHIPMENT = 'shipment';
	const DELIVERY_METHOD_IN_PERSON = 'in-person';
	const DELIVERY_METHOD_ELECTRONIC = 'electronic';
	const delivery_method_options = [
		self::DELIVERY_METHOD_SHIPMENT   => 'Shipment',
		self::DELIVERY_METHOD_IN_PERSON  => 'In-person',
		self::DELIVERY_METHOD_ELECTRONIC => 'Electronic',
	];

	public function status():string
	{
		return self::status_options[$this->status_enum] ?? 'Unknown';
	}

	public function delivery_method():string
	{
		return self::delivery_method_options[$this->delivery_method_enum] ?? 'Unknown';
	}

	/**
	 * @return Attribute<float,never>
	 */
	public function grandTotal():Attribute
	{
		return Attribute::get(function () {
			$items_grand_total = $this->wholesaleOrderLineItems()->selectRaw('sum(price_per_unit * quantity) as items_grand_total')->first()->items_grand_total;
			return ( $items_grand_total ?: $this->subtotal ) + $this->total_adjustment;
		})->shouldCache();
	}

}
