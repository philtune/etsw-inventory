<?php

namespace App\Livewire;

use App\Models\Listing;
use Livewire\Component;

class ListingsTable extends Component
{
	public function render()
	{
		return view('livewire.listings-table', [
			'listings' => Listing
				::query()
				->latest()
				->paginate(100, [
					'id', 'product_type_id', 'title', 'state', 'quantity', 'num_favorers', 'price', 'views'
				]),
		]);
	}
}
