<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesaleOrderLineItem extends Model
{
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];

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

	const STATUS_TODO = 'todo';
	const STATUS_STARTED = 'started';
	const STATUS_COMPLETED = 'completed';
	const STATUS_PACKED = 'packed';
	const status_options = [
		self::STATUS_TODO      => 'To Do',
		self::STATUS_STARTED   => 'Started',
		self::STATUS_COMPLETED => 'Completed',
		self::STATUS_PACKED    => 'Packed',
	];

	public function status():string
	{
		return self::status_options[$this->status_enum] ?? 'Unknown';
	}

}
