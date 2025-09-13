<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariantStock extends Pivot
{
	use HasUuids;
	protected $table = 'product_variant_stocks';
	public $timestamps = false;
	protected $casts = [
		'stock' => 'integer',
	];
}
