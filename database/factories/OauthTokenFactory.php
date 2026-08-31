<?php

namespace Database\Factories;

use App\Models\OauthToken;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/** @extends Factory<OauthToken> */
class OauthTokenFactory extends Factory
{
	public function definition():array
	{
		return [
			'client_enum'           => $this->faker->word(),
			'state'                 => $this->faker->word(),
			'access_token'          => Str::random(10),
			'refresh_token'         => Str::random(10),
			'expires_at'            => Carbon::now(),
			'remaining_today'       => $this->faker->randomNumber(),
			'remaining_this_second' => $this->faker->randomNumber(),
			'last_used_at'          => Carbon::now(),
		];
	}
}
