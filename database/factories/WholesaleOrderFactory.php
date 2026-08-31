<?php

namespace Database\Factories;

use App\Models\WholesaleCustomer;
use App\Models\WholesaleOrder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<WholesaleOrder> */
class WholesaleOrderFactory extends Factory
{
	public function definition():array
	{
		return [
			'created_at'  => Carbon::now(),
			'updated_at'  => Carbon::now(),
			'notes'       => $this->faker->word(),
			'ordered_at'  => Carbon::now(),
			'invoice_url' => $this->faker->url(),

			'wholesale_customer_id' => WholesaleCustomer::factory(),
		];
	}
}
