<?php

namespace App\Http\Controllers;

use App\Models\OauthToken;
use App\Services\EtsyAuthService;
use App\Services\EtsyListingService;
use App\Services\EtsyReceiptService;
use App\Services\EtsyTransactionService;
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
		if ( $request->state !== OauthToken::getEtsyToken()?->state ) {
			return redirect()->route('home')->with('error', 'Form expired. Please refresh the page and try again.');
		}
		EtsyAuthService::authCode($request->code);

		return redirect()->route('home')->with('toast', 'Access Token Generated.');
	}

	public function apiRefreshToken():RedirectResponse
	{
		EtsyAuthService::refreshToken();

		return redirect()->route('home')->with('toast', 'Access Token Refreshed.');
	}

	public function importAll():RedirectResponse
	{
		try {
			EtsyListingService::importAll();
			EtsyReceiptService::import();
			EtsyTransactionService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('toast', 'Everything imported!');
	}

	public function importListings():RedirectResponse
	{
		try {
			EtsyListingService::importAll();
		} catch ( ConnectionException $th ) {
			return back()->withErrors($th->getMessage());
		}
		return back()->with('toast', 'Listings imported!');
	}

	public function importReceipts()
	{
		try {
			EtsyReceiptService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('toast', 'Receipts imported!');
	}

	public function importTransactions()
	{
		try {
			EtsyTransactionService::import();
		} catch ( Throwable $th ) {
			return back()->withErrors('Error: ' . $th->getMessage());
		}
		return back()->with('toast', 'Transactions imported!');
	}

}
