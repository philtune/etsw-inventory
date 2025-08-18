<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Number;

class EtsyListing extends Model
{

//	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'price'     => 'json',
		'meta'      => 'json',
		'ending_at' => 'datetime',
		//		'creation_at'          => 'datetime',
		//		'created_at'           => 'datetime',
		//		'original_creation_at' => 'datetime',
		//		'last_modified_at'     => 'datetime',
		//		'updated_at'           => 'datetime',
		//		'state_at'             => 'datetime',
		//		'tags'                 => 'json',
		//		'materials'            => 'json',
		//		'style'                => 'json',
		//		'production_partners'  => 'json',
		//		'skus'                 => 'json',
		//		'shipping_profile'     => 'json',
		//		'shop'                 => 'json',
		//		'images'               => 'json',
		//		'videos'               => 'json',
		//		'user'                 => 'json',
		//		'translations'         => 'json',
		//		'inventory'            => 'json',
	];

	public function etsyTransactions():HasMany
	{
		return $this->hasMany(EtsyTransaction::class, 'etsy_listing_id', 'id');
	}

	/**
	 * @return BelongsTo<Product,$this>
	 */
	public function product():BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	/**
	 * @return BelongsTo<Scent,$this>
	 */
	public function scent():BelongsTo
	{
		return $this->belongsTo(Scent::class);
	}

	const STATE_ACTIVE = 'active';
	const STATE_INACTIVE = 'edit';
	const STATE_SOLD_OUT = 'sold_out';
	const STATE_DRAFT = 'draft';
	const STATE_EXPIRED = 'expired';
	const state_options = [
		self::STATE_ACTIVE   => 'In Stock',
		self::STATE_INACTIVE => 'Inactive',
		self::STATE_SOLD_OUT => 'Sold Out',
		self::STATE_DRAFT    => 'Draft',
		self::STATE_EXPIRED  => 'Expired',
	];

	/**
	 * @return Attribute<string,never>
	 */
	public function state():Attribute
	{
		return Attribute::get(fn() => self::state_options[$this->state_enum] ?? 'Unknown');
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function stateClass():Attribute
	{
		return Attribute::get(fn() => match ( $this->state_enum ) {
			self::STATE_ACTIVE => '--success',
			self::STATE_SOLD_OUT => '--warning',
			self::STATE_DRAFT => '--accent',
			self::STATE_EXPIRED => '--danger',
			default => '--muted',
		});
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function priceFormatted():Attribute
	{
		return Attribute::get(fn() => Number::currency($this->price['amount'] / $this->price['divisor'], $this->price['currency_code']))->shouldCache();
	}

	/**
	 * @return Attribute<string,never>
	 */
	public function thumbnail():Attribute
	{
		return Attribute::get(fn() => $this->meta['images'][0]['url_75x75'] ?? '')->shouldCache();
	}

//	/**
//	 * @return Attribute<int,never>
//	 */
//	public function age():Attribute
//	{
//		return Attribute::get(fn() => $this->created_at->monthsUntil(now())->count())->shouldCache();
//	}
}
