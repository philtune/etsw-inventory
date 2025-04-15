<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Throwable;

class TransactionController extends Controller
{
	public function import()
	{
		try {
			TransactionService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Transactions imported!');
	}
}
