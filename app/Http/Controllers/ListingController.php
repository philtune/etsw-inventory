<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use App\Services\ListingService;
use Throwable;

class ListingController extends Controller
{
	public function import()
	{
		try {
			ListingService::importAll();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Listings imported!');
	}
}
