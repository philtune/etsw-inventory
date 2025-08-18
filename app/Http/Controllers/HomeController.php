<?php

namespace App\Http\Controllers;

use App\Models\EtsyListing;
use App\Models\EtsyReceipt;
use App\Models\EtsyTransaction;
use App\Models\OauthToken;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
	public function dashboard():View
	{
		$etsyOauthToken = OauthToken::getEtsyToken();
		return view('home', [
			'etsyOauthToken'     => $etsyOauthToken,
			'listing_count'      => EtsyListing::count(),
			'listing_latest'     => EtsyListing::latest()->first()?->created_at->setTimezone('America/New_York'),
			'receipt_count'      => EtsyReceipt::count(),
			'receipt_latest'     => EtsyReceipt::latest()->first()?->created_at->setTimezone('America/New_York'),
			'transaction_count'  => EtsyTransaction::count(),
			'transaction_latest' => EtsyTransaction::latest()->first()?->created_at->setTimezone('America/New_York'),
			'revenue_to_date'    => EtsyReceipt::sum('subtotal')
		]);
	}

}
