<?php

namespace App\Http\Controllers;

class EtsyTransactionController extends Controller
{
	public function index()
	{
		return view('etsy.transactions.index');
	}
}
