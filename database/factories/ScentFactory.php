<?php

namespace Database\Factories;

use App\Models\Scent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Scent>
 */
class ScentFactory extends Factory
{
	protected $model = Scent::class;

	public function definition():array
	{
		return [
			'label' => $this->faker->word(),
		];
	}
}
