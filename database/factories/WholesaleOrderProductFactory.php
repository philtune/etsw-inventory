<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\WholesaleOrder;
use App\Models\WholesaleOrderProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<WholesaleOrderProduct> */
class WholesaleOrderProductFactory extends Factory
{
	public function definition():array
	{
		return [
			'variation'        => $this->faker->words(),
			'quantity'         => $this->faker->randomNumber(),
			'price_per_unit'   => $this->faker->randomFloat(),
			'total_adjustment' => $this->faker->randomFloat(),
			'notes'            => $this->faker->word(),
			'position'         => $this->faker->randomNumber(),
			'created_at'       => Carbon::now(),
			'updated_at'       => Carbon::now(),

			'wholesale_order_id' => WholesaleOrder::factory(),
			'product_id'         => Product::factory(),
		];
	}
}
