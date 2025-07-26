<?php

namespace App\Livewire\Transactions;

use App\Models\EtsyTransaction;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
	use WithPagination;

	public function render():View
	{
		return view('transactions.index-table', [
			'etsyTransactions' => EtsyTransaction
				::orderBy('created_at', 'desc')
				->paginate(50)
		]);
	}
}
