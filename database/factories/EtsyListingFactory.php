<?php

namespace Database\Factories;

use App\Models\EtsyListing;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<EtsyListing> */
class EtsyListingFactory extends Factory
{
	public function definition():array
	{
		return [
			'last_imported_at'  => Carbon::now(),
			'created_at'        => Carbon::now(),
			'updated_at'        => Carbon::now(),
			'ending_at'         => Carbon::now(),
			'title'             => $this->faker->word(),
			'state_enum'        => $this->faker->word(),
			'is_archived'       => $this->faker->boolean(),
			'inventory'         => $this->faker->word(),
			'variants_in_stock' => $this->faker->words(),
			'url'               => $this->faker->url(),
			'thumbnail'         => $this->faker->word(),
			'meta'              => $this->faker->words(),

			'product_id' => Product::factory(),
		];
	}
}
