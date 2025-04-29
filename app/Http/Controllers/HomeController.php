<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Receipt;
use App\Models\Transaction;
use App\Services\EtsyAuthService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
	public function dashboard():View
	{
		return view('home', [
			'access_token'  => EtsyAuthService::getAccessToken(),
			'expires_at'    => EtsyAuthService::getCurrentTokenExpiresAt(),
			'refresh_token' => EtsyAuthService::getRefreshToken(),
			'listing_count' => Listing::count(),
			'listing_latest' => Listing::latest()->first()->created_at->setTimezone('America/New_York'),
			'receipt_count' => Receipt::count(),
			'receipt_latest' => Receipt::latest()->first()->created_at->setTimezone('America/New_York'),
			'transaction_count' => Transaction::count(),
			'transaction_latest' => Transaction::latest()->first()->created_at->setTimezone('America/New_York'),
			'revenue_to_date' => DB
				::table('receipts')
				->selectRaw("sum(json_extract(grandtotal, '\$.amount'))/100 as revenue")
				->first()
				->revenue
		]);
	}

}
