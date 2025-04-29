<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class EtsyListingController extends Controller
{

	public function index():View
	{
		return view('etsy.listings.index');
	}

}
