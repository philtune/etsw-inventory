<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductBundleItem;
use App\Models\ProductTypeVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductBundleItem> */
class ProductBundleItemFactory extends Factory
{
	public function definition():array
	{
		return [
			'product_id'              => Product::factory(),
			'child_product_id'        => Product::factory(),
			'product_type_variant_id' => ProductTypeVariant::factory(),
		];
	}
}
