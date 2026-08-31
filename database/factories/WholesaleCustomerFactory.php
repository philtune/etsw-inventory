<?php

namespace Database\Factories;

use App\Models\WholesaleCustomer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<WholesaleCustomer> */
class WholesaleCustomerFactory extends Factory
{
	public function definition():array
	{
		return [
			'name'            => $this->faker->name(),
			'primary_address' => $this->faker->address(),
			'phone_numbers'   => $this->faker->phoneNumber(),
			'notes'           => $this->faker->word(),
			'created_at'      => Carbon::now(),
			'updated_at'      => Carbon::now(),
		];
	}
}
