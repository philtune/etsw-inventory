<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Services\ListingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class ListingController extends Controller
{

	public function index():View
	{
		return view('listings.index', [
			'listings' => Listing
				::query()
				->latest()
				->paginate(100, [
					'id', 'title', 'state', 'quantity', 'num_favorers', 'price', 'views'
				]),
		]);
	}

	public function update(Listing $listing, Request $request):RedirectResponse
	{
		$listing->update($request->validate([
			'product_type_id' => 'nullable|uuid|exists:product_types,id',
		]));
		return back()->with('status', 'Listing updated!');
	}

	public function import():RedirectResponse
	{
		try {
			ListingService::importAll();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Listings imported!');
	}
}
