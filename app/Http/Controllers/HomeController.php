<?php

namespace App\Http\Controllers;

use App\Services\EtsyAuthService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
	public function __invoke():View
	{
		return view('home', [
			'access_token'  => EtsyAuthService::getAccessToken(),
			'expires_at'    => EtsyAuthService::currentTokenExpiresAt(),
		]);
	}
}
