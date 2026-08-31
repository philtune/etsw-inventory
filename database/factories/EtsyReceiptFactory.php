<?php

namespace Database\Factories;

use App\Models\EtsyReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<EtsyReceipt> */
class EtsyReceiptFactory extends Factory
{
	public function definition():array
	{
		return [
			'created_at'         => Carbon::now(),
			'updated_at'         => Carbon::now(),
			'buyer_user_id'      => $this->faker->randomNumber(),
			'name'               => $this->faker->name(),
			'city'               => $this->faker->city(),
			'state'              => $this->faker->word(),
			'status'             => $this->faker->word(),
			'message_from_buyer' => $this->faker->word(),
			'is_gift'            => $this->faker->boolean(),
			'subtotal'           => $this->faker->words(),
			'meta'               => $this->faker->words(),
		];
	}
}
