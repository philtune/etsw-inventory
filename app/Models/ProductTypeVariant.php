<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTypeVariant extends Model
{
	use HasUuids;

	public $timestamps = false;
	protected $guarded = [];

	/**
	 * @return BelongsTo<ProductType,$this>
	 */
	public function productType():BelongsTo
	{
		return $this->belongsTo(ProductType::class);
	}
}
