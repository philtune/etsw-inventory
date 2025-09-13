<?php

namespace App\Livewire\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
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
	#[Url]
	public bool $trashed = false;

	public function __construct(
		public ?string $stack_id = null
	)
	{
		$this->stack_id = $this->stack_id ?: 'id_' . uniqid();
	}

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
		$query = $this
			->query()
			->when($this->trashed, fn(Builder $builder) => $builder->onlyTrashed());

		foreach ( $this->queryHandlers as $queryHandler ) {
			$queryHandler($query);
		}

		return $query->paginate($this->perPage);
	}

	protected static function validated(array $formData, array $rules, array $messages = [], array $attributes = []):array
	{
		$data = array_reduce(
			array_keys($rules),
			function (array $c, $key) use ($rules, $formData) {
				if ( in_array($rules[$key], ['boolean', 'bool']) ) {
					$formData[$key] = !!( $formData[$key] ?? false );
				}
				if ( array_key_exists($key, $formData) ) {
					$value = $formData[$key];
					$value = $value === '' ? null : $value;
					return $c + [$key => $value];
				}
				return $c;
			},
			[]
		);
		return Validator::make($data, $rules, $messages, $attributes)->validate();
	}

}
