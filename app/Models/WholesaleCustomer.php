<?php

namespace App\Models;

use Database\Factories\WholesaleCustomerFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesaleCustomer extends Model
{
	/** @use HasFactory<WholesaleCustomerFactory> */
	use HasFactory;
	use HasUuids;
	use SoftDeletes;

	protected $guarded = [];

	/**
	 * @return HasMany<WholesaleOrder,$this>
	 */
	public function wholesaleOrders():HasMany
	{
		return $this->hasMany(WholesaleOrder::class);
	}
}
