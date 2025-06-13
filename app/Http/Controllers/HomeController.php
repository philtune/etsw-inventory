<?php

namespace App\Http\Controllers;

use App\Models\EtsyListing;
use App\Models\EtsyReceipt;
use App\Models\EtsyTransaction;
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
			'listing_count' => EtsyListing::count(),
			'listing_latest' => EtsyListing::latest()->first()->created_at->setTimezone('America/New_York'),
			'receipt_count' => EtsyReceipt::count(),
			'receipt_latest' => EtsyReceipt::latest()->first()->created_at->setTimezone('America/New_York'),
			'transaction_count' => EtsyTransaction::count(),
			'transaction_latest' => EtsyTransaction::latest()->first()->created_at->setTimezone('America/New_York'),
			'revenue_to_date' => EtsyReceipt
				::selectRaw("sum(json_extract(grandtotal, '\$.amount'))/100 as revenue")
				->first()
				->revenue
		]);
	}

}
