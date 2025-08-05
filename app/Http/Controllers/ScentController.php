<?php

namespace App\Http\Controllers;

use App\Models\Scent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScentController extends Controller
{

	public function index():View
	{
		return view('scents.index');
	}

	public function store(Request $request):RedirectResponse
	{
		Scent::query()->create($request->validate([
			'code'  => [
				'nullable',
				'string',
				'max:16',
				Rule::unique('scents')->withoutTrashed()
			],
			'label' => [
				'nullable',
				'string',
				'max:255',
				Rule::unique('scents')->withoutTrashed()
			],
		]));
		return back()->with('toast', 'Scent created!');
	}

	public function update(Scent $scent, Request $request):RedirectResponse
	{
		$scent->update($request->validate([
			'code'  => 'nullable|string|max:16',
			'label' => 'nullable|string|max:255',
		]));
		return back()->with('toast', 'Scent updated!');
	}

	public function delete(Scent $scent):RedirectResponse
	{
		$scent->delete();
		return redirect()->route('scents.index')->with('toast', 'Scent deleted!');
	}

	public function restore(Scent $scent):RedirectResponse
	{
		$scent->restore();
		return redirect()->route('scents.index')->with('toast', 'Scent restored!');
	}

	public function forceDelete(Scent $scent):RedirectResponse
	{
		$scent->forceDelete();
		return redirect()->route('scents.index')->with('toast', 'Scent permanently deleted!');
	}

}
