<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
	public function run():void
	{
		ProductType::factory()->createMany([
			[
				'section_id' => 19684707,
				'code'       => 'CPS',
				'label'      => 'Cold Processed Soap',
				'meta'       => [
					'variants' => [
						[
							'label' => "5 oz",
							"price" => 12.99
						],
						[
							'label' => "3.5 oz",
							"price" => 10.99
						],
						[
							'label' => "1.5 oz",
							"price" => 7.99
						],
					],
				],
			],
			[
				'section_id' => 36188927,
				'code'       => 'SB',
				'label'      => 'Shampoo Bar',
				'meta'       => [
					'variants' => [
						[
							'label' => "2.4 oz",
							"price" => 15.99
						],
						[
							'label' => "3.9 oz",
							"price" => 23.49
						],
					],
				],
			],
			[
				'section_id' => 36174672,
				'code'       => 'CB',
				'label'      => 'Conditioner Bar',
			],
			[
				'section_id' => 39672549,
				'code'       => 'RLS',
				'label'      => 'Room & Linen Spray',
			],
			[
				'section_id' => 32315602,
				'code'       => 'SET',
				'label'      => 'Shower Set - Soap | Shampoo | Conditioner',
			],
			[
				'section_id' => 32315602,
				'code'       => 'HCS',
				'label'      => 'Haircare Set - Shampoo | Conditioner',
			],
			[
				'section_id' => 32315602,
				'code'       => 'BRS',
				'label'      => 'Bathroom Set - Soap | Shave Cream | Beard Oil',
			],
			[
				'section_id' => 27609224,
				'code'       => 'WSS',
				'label'      => 'Whipped Sugar Scrub',
			],
			[
				'section_id' => 32315602,
				'code'       => 'BUNDLE',
				'label'      => 'Bundle',
			],
			[
				'section_id' => 40348874,
				'code'       => 'WM',
				'label'      => 'Wax Melt',
			],
			[
				'section_id' => 31236411,
				'code'       => 'BO',
				'label'      => 'Beard Oil',
			],
			[
				'section_id' => 31236411,
				'code'       => 'SC',
				'label'      => 'Shave Cream',
			],
			[
				'section_id' => 32330253,
				'code'       => 'SALT',
				'label'      => 'Bath Salts',
			],
			[
				'code'  => 'BUB',
				'label' => 'Bubble Bar',
			],
			[
				'section_id' => 30330375,
				'code'       => 'MASK',
				'label'      => 'Foaming Clay Mask',
			],
			[
				'section_id' => 32330253,
				'code'       => 'BOMB',
				'label'      => 'Bath Bomb',
			],
		]);
	}
}
