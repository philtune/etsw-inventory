<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAggregate;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductAggregate> */
class ProductAggregateFactory extends Factory
{
	public function definition():array
	{
		return [
			'etsy_listings_count'          => $this->faker->randomNumber(),
			'etsy_transactions_qty'        => $this->faker->randomNumber(),
			'etsy_revenue'                 => $this->faker->randomFloat(),
			'wholesale_order_products_qty' => $this->faker->randomNumber(),
			'wholesale_revenue'            => $this->faker->randomFloat(),
			'total_qty'                    => $this->faker->randomNumber(),
			'total_revenue'                => $this->faker->randomFloat(),

			'product_id' => Product::factory(),
		];
	}
}
