<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/** @extends Factory<ProductType> */
class ProductTypeFactory extends Factory
{
	protected $model = ProductType::class;

	public function definition():array
	{
		return [
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		];
	}
}
