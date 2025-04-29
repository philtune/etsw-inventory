<?php

namespace App\Http\Controllers;

use App\Services\EtsyAuthService;
use App\Services\ListingService;
use App\Services\ReceiptService;
use App\Services\TransactionService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EtsyApiController extends Controller
{
	public function apiRedirectUrl(Request $request)
	{
		$request->validate([
			'state' => 'required',
			'code'  => 'required|string', // This authorization code has a functional life of 1 hour
		]);
		if ( $request->state !== session('state') ) {
			return redirect()->route('home')->with('error', 'Form expired. Please refresh the page and try again.');
		}
		EtsyAuthService::authCode($request->code);

		return redirect()->route('home')->with('status', 'Access Token Generated.');
	}

	public function apiRefreshToken():RedirectResponse
	{
		EtsyAuthService::refreshToken();

		return redirect()->route('home')->with('status', 'Access Token Refreshed.');
	}

	public function importAll():RedirectResponse
	{
		try {
			ListingService::importAll();
			ReceiptService::import();
			TransactionService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Everything imported!');
	}

	public function importListings():RedirectResponse
	{
		try {
			ListingService::importAll();
		} catch ( ConnectionException $th ) {
			return back()->withErrors($th->getMessage());
		}
		return back()->with('status', 'Listings imported!');
	}

	public function importReceipts()
	{
		try {
			ReceiptService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Receipts imported!');
	}

	public function importTransactions()
	{
		try {
			TransactionService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('status', 'Transactions imported!');
	}

}
