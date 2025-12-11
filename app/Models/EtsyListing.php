<?php

namespace App\Models;

use App\Services\EtsyListingService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Number;

class EtsyListing extends Model
{

	//	public $timestamps = false;
	protected $guarded = [];
	protected $casts = [
		'price'             => 'json',
		'inventory'         => 'json',
		'variants_in_stock' => 'json',
		'meta'              => 'json',
		'ending_at'         => 'datetime',
		'last_imported_at'  => 'datetime',
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
	];

	protected static function booted():void
	{
		static::creating(function (self $self) {
			$self->variants_in_stock = $self->calculateVariantsInStock();
			$self->last_imported_at = now();
		});
		static::updating(function (self $self) {
			$self->variants_in_stock = $self->calculateVariantsInStock();
			$self->last_imported_at = now();
		});
	}

	protected function calculateVariantsInStock():array
	{
		return static::calculateVariantsInStockFromProducts($this->inventory['products']);
	}

	public static function calculateVariantsInStockFromProducts(array $products):array
	{
		return array_reduce(
			array: $products,
			callback: function (array $c, array $product) {
				$offering = $product['offerings'][0];
				if ( !$offering['is_enabled'] || ( $offering['is_deleted'] ?? false ) ) {
					return $c;
				}
				if (
					empty($product['property_values']) ||
					count($product['property_values']) > 1 ||
					!( $key = $product['property_values'][0]['values'][0] ?? false )
				) {
					$key = 'default';
				}
				return $c + [
						$key => $offering['quantity'],
					];
			},
			initial: []
		);
	}

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
	 * @return Attribute<boolean,never>
	 */
	public function isActive():Attribute
	{
		return Attribute::get(fn() => $this->state_enum === self::STATE_ACTIVE)->shouldCache();
	}

	/**
	 * @return Attribute<boolean,never>
	 */
	public function isInactive():Attribute
	{
		return Attribute::get(fn() => $this->state_enum === self::STATE_INACTIVE)->shouldCache();
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

	//	/**
	//	 * @return Attribute<string,never>
	//	 */
	//	public function thumbnail():Attribute
	//	{
	//		return Attribute::get(fn() => $this->meta['images'][0]['url_75x75'] ?? '')->shouldCache();
	//	}

	//	/**
	//	 * @return Attribute<int,never>
	//	 */
	//	public function age():Attribute
	//	{
	//		return Attribute::get(fn() => $this->created_at->monthsUntil(now())->count())->shouldCache();
	//	}

	public function variant_in_stock(?string $aliases = null):int
	{
		if ( $this->is_inactive ) {
			return 0;
		}
		if ( $aliases ) {
			foreach ( explode(',', $aliases) as $alias ) {
				if ( array_key_exists($alias, $this->variants_in_stock) ) {
					return $this->variants_in_stock[$alias];
				}
			}
		}
		return $this->variants_in_stock['default'] ?? 0;
	}

	/**
	 * @return Attribute<?array,never>
	 */
	public function inventory():Attribute
	{
		return Attribute::get(function () {
			$inventory_arr = $this->meta['inventory'];
			foreach ( $inventory_arr['products'] as &$product_arr ) {
				unset($product_arr['product_id']);
				unset($product_arr['is_deleted']);
				foreach ( $product_arr['property_values'] as &$property_value_arr ) {
					unset($property_value_arr['scale_name']);
				}
				foreach ( $product_arr['offerings'] as &$offering_arr ) {
					unset($offering_arr['offering_id']);
					unset($offering_arr['is_deleted']);
					$offering_arr['price'] = $offering_arr['price']['amount'] / $offering_arr['price']['divisor'];
				}
			}
			return $inventory_arr;
		});
	}

	public function import():bool
	{
		return $this->update(EtsyListingService::import($this->id));
	}

	public function importInventory():bool
	{
		$etsyListingInventory = EtsyListingService::getInventory($this->id);
		$meta = $this->meta;
		$meta['inventory'] = $etsyListingInventory;
		return $this->update(['meta' => $meta]);
	}

	public function updateInventory(array $variants_in_stock):false|static
	{
		$inventory = $this->inventory;
		foreach ( $inventory['products'] as &$inventory_product ) {
			$data_key = $inventory_product['property_values'][0]['values'][0] ?? 'default';
			$variants_in_stock[$data_key] = intval($variants_in_stock[$data_key]);
			$inventory_product['offerings'][0]['quantity'] = $variants_in_stock[$data_key];
		}
		if ( $updated_inventory = EtsyListingService::updateInventory($this->id, $inventory) ) {
			$meta = $this->meta;
			$meta['inventory'] = $updated_inventory;
			$this->update([
				'meta'              => $meta,
				'variants_in_stock' => $variants_in_stock,
			]);
			return $this;
		}
		return false;
	}

}
