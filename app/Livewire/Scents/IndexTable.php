<?php

namespace App\Livewire\Scents;

use App\Livewire\Concerns\IndexTableComponent;
use App\Models\Scent;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

/**
 * @extends IndexTableComponent<Scent>
 */
class IndexTable extends IndexTableComponent
{

	public int $perPage = 32;
	protected array $searchColumns = [
		'label',
		'code'
	];
	public string $order_column = 'code';
	public bool $order_desc = false;

	public function render():View
	{
		/** @var LengthAwarePaginator<array-key,Scent> $collection */
		$collection = $this->collection();
		return view('scents.index-table', [
			'collection' => $collection,
		]);
	}

	protected function query():Builder
	{
		return Scent
			::query()
			->withCount(['products', 'etsyListings'])
			->withSum('productAggregates as total_revenue', 'total_revenue');
	}

	private function rules(?Scent $scent = null):array
	{
		return [
			'code'  => [
				'required', 'string', 'max:16',
				Rule::unique('scents')
				    ->withoutTrashed()
				    ->when($scent, fn(Unique $rule) => $rule->ignore($scent))
			],
			'label' => [
				'required', 'string', 'max:255',
				Rule::unique('scents')
				    ->withoutTrashed()
				    ->when($scent, fn(Unique $rule) => $rule->ignore($scent))
			],
		];
	}

	public function store(array $formData):void
	{
		Scent::query()->create(static::validated($formData, $this->rules()));
		$this->dispatch('toast', 'Scent created!', '--success');
	}

	public function update(Scent $scent, array $formData):void
	{
		$scent->update(static::validated($formData, $this->rules($scent)));
		$this->dispatch('toast', 'Scent updated!', '--success');
	}

	public function delete(Scent $scent):void
	{
		$scent->delete();
		$this->dispatch('toast', 'Scent deleted!', '--success');
	}

	public function restore(string $scent_id):void
	{
		$scent = Scent::withTrashed()->findOrFail($scent_id);
		$scent->restore();
		$this->dispatch('toast', 'Scent restored!', '--success');
	}

	public function forceDelete(string $scent_id):void
	{
		$scent = Scent::withTrashed()->findOrFail($scent_id);
		$scent->forceDelete();
		$this->dispatch('toast', 'Scent permanently deleted!', '--success');
	}

}
