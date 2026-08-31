<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductType;
use App\Models\Scent;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
	public function definition():array
	{
		return [
			'created_at'       => Carbon::now(),
			'updated_at'       => Carbon::now(),
			'label'            => $this->faker->word(),
			'is_bundle'        => $this->faker->boolean(),
			'is_made_to_order' => $this->faker->boolean(),
			'stock'            => $this->faker->randomNumber(),
			'stock_updated_at' => Carbon::now(),
			'snooze_until'     => Carbon::now(),
			'is_archived'      => $this->faker->boolean(),
			'notes'            => $this->faker->word(),

			'product_type_id' => ProductType::factory(),
			'scent_id'        => Scent::factory(),
		];
	}
}
