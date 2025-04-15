<?php

namespace Database\Seeders;

use App\Models\Scent;
use Illuminate\Database\Seeder;

class ScentSeeder extends Seeder
{
	public function run():void
	{
		Scent::factory()->createMany([
			[
				'code'  => 'PCC',
				'label' => 'Pumpkin Caramel Chai',
			],
			[
				'code'  => 'TOB',
				'label' => 'Tobacco Bourbon | Vanilla | Tobacco, Oat & Honey',
			],
			[
				'code'  => 'CB',
				'label' => 'Cracklin Birch',
			],
			[
				'code'  => 'WSL',
				'label' => 'White Sage & Lavender',
			],
			[
				'code'  => 'BBP',
				'label' => 'Birch & Black Pepper',
			],
			[
				'code'  => 'H&C',
				'label' => 'Honeysuckle & Calendula',
			],
			[
				'code'  => 'PP',
				'label' => 'Peach, Please',
			],
			[
				'code'  => 'SP',
				'label' => 'Snow Pricess',
			],
			[
				'code'  => 'PS',
				'label' => 'Peppermint Stick',
			],
			[
				'code'  => 'R&S',
				'label' => 'Rosemary & Sage',
			],
			[
				'code'  => 'WSSS',
				'label' => 'Wood Sage & Sea Salt',
			],
			[
				'code'  => 'L&C',
				'label' => 'Lavender & Chamomile',
			],
			[
				'code'  => 'G',
				'label' => 'Goddess (Lavender & Patchouli)',
			],
			[
				'code'  => 'JR',
				'label' => 'Jammin\' Rose',
			],
			[
				'code'  => 'MT',
				'label' => 'Mahogany & Teakwood',
			],
			[
				'code'  => 'B&M',
				'label' => 'Bergamot & Mahogany',
			],
			[
				'code'  => 'SPF',
				'label' => 'Sugar Plum Fizz | Hocus Pocus | Black Cherry',
			],
			[
				'code'  => 'NL',
				'label' => 'Northern Lights',
			],
			[
				'code'  => 'HAC',
				'label' => 'Honeyed Apple Cider',
			],
			[
				'code'  => 'AF',
				'label' => 'Autumn Flannel',
			],
			[
				'code'  => 'SMF',
				'label' => 'Smoky Mountain Forest | Splendor',
			],
			[
				'code'  => 'VB',
				'label' => 'Vanilla Bean | Buttercream',
			],
			[
				'code'  => 'CCV',
				'label' => 'Creamy Coconut & Vanilla',
			],
			[
				'code'  => 'LIB',
				'label' => 'Meet Me In The Library',
			],
			[
				'code'  => 'BCV',
				'label' => 'Brazilian Coffee & Vanilla',
			],
			[
				'code'  => 'L',
				'label' => 'Lemongrass',
			],
			[
				'code'  => 'WS',
				'label' => 'Winter Solstice',
			],
			[
				'code'  => 'FSH',
				'label' => 'Five Star Hotel',
			],
			[
				'code'  => 'COCOA',
				'label' => 'Hot Chocolate | Cocoa',
			],
			[
				'code'  => 'LS',
				'label' => 'Love Spell',
			],
			[
				'code'  => 'E&H',
				'label' => 'Elderflower & Hibiscus',
			],
			[
				'code'  => 'C&SS',
				'label' => 'Citrus & Sea Salt',
			],
			[
				'code'  => 'SPEARMINT',
				'label' => 'Spearmint & Eucalyptus | Riptide',
			],
			[
				'code'  => 'UNSCENTED',
				'label' => 'Unscented',
			],
			[
				'code'  => 'VARIETY',
				'label' => 'Variety',
			],
			[
				'code'  => 'H&R',
				'label' => 'Honeycomb & Rosemary',
			],
		]);
	}
}
