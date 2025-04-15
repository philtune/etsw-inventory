<?php

namespace App\Http\Controllers;

use App\Models\Scent;
use Illuminate\Http\Request;

class FragranceController extends Controller
{
	public function index()
	{
		return Scent::all();
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'label' => ['required'],
		]);

		return Scent::create($data);
	}

	public function show(Scent $fragrance)
	{
		return $fragrance;
	}

	public function update(Request $request, Scent $fragrance)
	{
		$data = $request->validate([
			'label' => ['required'],
		]);

		$fragrance->update($data);

		return $fragrance;
	}

	public function destroy(Scent $fragrance)
	{
		$fragrance->delete();

		return response()->json();
	}
}
