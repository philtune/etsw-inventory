<?php

namespace App\Http\Controllers;

use App\Services\ListingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;

class EtsyListingController extends Controller
{

	public function index():View
	{
		return view('etsy-listings.index');
	}

	public function import():RedirectResponse
	{
		try {
			ListingService::importAll();
		} catch ( ConnectionException $th ) {
			return back()->withErrors($th->getMessage());
		}
		return back()->with('status', 'Listings imported!');
	}
}
