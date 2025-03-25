<?php

namespace App\Http\Controllers;

use App\Services\EtsyApplicationApi;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
	public function listings(Request $request):JsonResponse
	{
		try {
			return response()->json([EtsyApplicationApi::transactions($request->validate([
				'limit'  => 'integer|min:1',
				'offset' => 'integer|min:0',
			])), ]);
		} catch ( ConnectionException $e ) {
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
}
