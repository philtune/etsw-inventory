<?php

namespace Database\Factories;

use App\Models\EtsyListing;
use App\Models\EtsyReceipt;
use App\Models\EtsyTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<EtsyTransaction> */
class EtsyTransactionFactory extends Factory
{
	public function definition():array
	{
		return [
			'created_at'       => Carbon::now(),
			'variation'        => $this->faker->words(),
			'quantity'         => $this->faker->randomNumber(),
			'listing_image_id' => $this->faker->randomNumber(),
			'price'            => $this->faker->words(),
			'variations'       => $this->faker->words(),
			'shop_coupon'      => $this->faker->randomFloat(),
			'meta'             => $this->faker->words(),

			'etsy_receipt_id' => EtsyReceipt::factory(),
			'etsy_listing_id' => EtsyListing::factory(),
		];
	}
}
