<?php

namespace App\Http\Controllers;

use App\Services\DataImportService;
use Throwable;

class TransactionController extends Controller
{
	public function import()
	{
		try {
			DataImportService::importTransactions();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Transactions imported!');
	}
}
