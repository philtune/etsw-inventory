<?php

namespace App\Http\Controllers;

use App\Services\DataImportService;
use Throwable;

class ReceiptController extends Controller
{
	public function import()
	{
		try {
			DataImportService::importReceipts();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Receipts imported!');
	}
}
