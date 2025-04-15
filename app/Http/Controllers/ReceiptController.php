<?php

namespace App\Http\Controllers;

use App\Services\ReceiptService;
use Throwable;

class ReceiptController extends Controller
{
	public function import()
	{
		try {
			ReceiptService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Receipts imported!');
	}
}
