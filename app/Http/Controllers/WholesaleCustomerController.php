<?php

namespace App\Http\Controllers;

use App\Models\WholesaleCustomer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WholesaleCustomerController extends Controller
{

	public function index():View
	{
		return view('wholesale.customers.index', [
			'wholesaleCustomers' => WholesaleCustomer
				::withCount('wholesaleOrders')
				->orderBy('name')
				->get(),
		]);
	}

	public function store(Request $request):RedirectResponse
	{
		$wholesaleCustomer = WholesaleCustomer::create($request->validate([
			'name' => 'required|string|max:255',
		]));
		return redirect()->route('wholesale-customers.show', $wholesaleCustomer);
	}

	public function show(WholesaleCustomer $wholesaleCustomer):View
	{
		return view('wholesale.customers.show', [
			'wholesaleCustomer' => $wholesaleCustomer
		]);
	}

	public function update(Request $request, WholesaleCustomer $wholesaleCustomer):RedirectResponse
	{
		$wholesaleCustomer->update($request->validate([
			'name'            => 'required|string|max:255',
			'primary_address' => 'nullable|string|max:255',
			'phone_numbers'   => 'nullable|string|max:255',
			'notes'           => 'nullable|string|max:1025',
		]));
		return back()->with('status', 'Customer updated!');
	}

	public function destroy(WholesaleCustomer $wholesaleCustomer)
	{
		$wholesaleCustomer->delete();

		return response()->json();
	}
}
