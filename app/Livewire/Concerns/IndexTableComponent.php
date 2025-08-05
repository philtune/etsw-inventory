<?php

namespace App\Livewire\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @template TModel of Model
 */
abstract class IndexTableComponent extends Component
{
	use WithPagination;
	use Searchable;
	use Sortable;

	#[Url]
	public int $perPage = 15;
	public string $stack_id;
	#[Url]
	public bool $trashed = false;

	/** @var array<Closure> $queryHandlers */
	protected array $queryHandlers = [];

	protected function addQueryHandler(Closure $callback):void
	{
		$this->queryHandlers[] = $callback;
	}

	/**
	 * @return Builder<TModel>
	 */
	abstract protected function query():Builder;

	/**
	 * @return LengthAwarePaginator<array-key,TModel>
	 */
	protected function collection():LengthAwarePaginator
	{
		$this->stack_id = 'id_' . uniqid();

		$query = $this
			->query()
			->when($this->trashed, fn(Builder $builder) => $builder->onlyTrashed());

		foreach ( $this->queryHandlers as $queryHandler ) {
			$queryHandler($query);
		}

		return $query->paginate($this->perPage);
	}

}
