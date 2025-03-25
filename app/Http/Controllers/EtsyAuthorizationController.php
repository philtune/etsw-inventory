<?php

namespace App\Http\Controllers;

use App\Services\EtsyAuthService;
use Illuminate\Http\Request;

class EtsyAuthorizationController extends Controller
{
	public function redirectUrl(Request $request)
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

	public function refreshToken()
	{
		EtsyAuthService::refreshToken();

		return redirect()->route('home')->with('status', 'Access Token Refreshed.');
	}
}
