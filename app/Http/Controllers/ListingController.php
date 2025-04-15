<?php

namespace App\Http\Controllers;

use App\Services\DataImportService;
use Throwable;

class ListingController extends Controller
{
	public function import()
	{
		try {
			DataImportService::importListings();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Listings imported!');
	}
}
