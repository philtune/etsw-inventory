<?php

namespace Database\Factories;

use App\Models\ProductType;
use App\Models\ProductTypeVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<ProductTypeVariant> */
class ProductTypeVariantFactory extends Factory
{
	public function definition():array
	{
		return [
			'label'       => $this->faker->word(),
			'aliases'     => $this->faker->word(),
			'is_archived' => $this->faker->boolean(),

			'product_type_id' => ProductType::factory(),
		];
	}
}
