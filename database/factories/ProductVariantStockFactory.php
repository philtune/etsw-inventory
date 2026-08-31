<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductTypeVariant;
use App\Models\ProductVariantStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductVariantStock> */
class ProductVariantStockFactory extends Factory
{
	public function definition():array
	{
		return [
			'product_id'              => Product::factory(),
			'product_type_variant_id' => ProductTypeVariant::factory(),
			'stock'                   => $this->faker->randomNumber(),
			'stock_updated_at'        => $this->faker->date(),
		];
	}
}
