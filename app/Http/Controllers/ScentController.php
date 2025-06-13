<?php

namespace App\Http\Controllers;

use App\Models\Scent;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScentController extends Controller
{

	public function index():View
	{
		return view('scents.index', [
			'scents' => Scent
				::query()
				->orderBy('label')
				->withCount([
					'etsyListings as unarchived_listings_count' => fn(Builder $query) => $query->where('etsy_listings.is_archived', false),
					'etsyListings as archived_listings_count' => fn(Builder $query) => $query->where('etsy_listings.is_archived', true),
				])
				->withSum('etsy_transactions as revenue', DB::raw("etsy_transactions.price->>'$.amount' / etsy_transactions.price->>'$.divisor'"))
				->get(),
		]);
	}

	public function store(Request $request):RedirectResponse
	{
		Scent::query()->create($request->validate([
			'code'  => 'nullable|string|max:16|unique:scents,code',
			'label' => 'nullable|string|max:255',
		]));
		return back()->with('status', 'Scent created!');
	}

	public function update(Scent $scent, Request $request):RedirectResponse
	{
		$scent->update($request->validate([
			'code'  => 'nullable|string|max:16',
			'label' => 'nullable|string|max:255',
		]));
		return back()->with('status', 'Scent updated!');
	}

	public function delete(Scent $scent):RedirectResponse
	{
		$scent->delete();
		return redirect()->route('scents.index')->with('status', 'Scent deleted!');
	}

}
