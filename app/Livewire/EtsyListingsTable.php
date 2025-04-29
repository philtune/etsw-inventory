<?php

namespace App\Livewire;

use App\Models\Listing;
use App\Models\ProductType;
use App\Models\Scent;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EtsyListingsTable extends Component
{
	use WithPagination;

	public array $product_type_options;
	public array $scent_options;
	#[Url]
	public int $perPage = 50;
	#[Url]
	public string $search = '';
	#[Url]
	public string $order_column = 'listings.created_at';
	#[Url]
	public bool $order_desc = true;
	#[Url]
	public bool $archived = false;
	#[Url]
	public ?string $scent_id = null;
	#[Url]
	public ?string $product_type_id = null;
	#[Url]
	public ?string $edit_mode = null;
	#[Url]
	public ?string $sales_before = null;
	#[Url]
	public ?string $sales_after = null;

	public function mount():void
	{
		$this->product_type_options = ProductType
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, ProductType $productType) => $c + [
					$productType->id => "$productType->code - $productType->label"
				], []);
		$this->scent_options = Scent
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, Scent $scent) => $c + [
					$scent->id => "$scent->code - $scent->label"
				], []);
	}

	public function render():View
	{
		$this->dispatch('render');
		return view('etsy.listings.index-table', [
			'listings' => Listing
				::query()
				->whereRaw('listings.`meta`->>"$.who_made" = "i_did"')
				->when(
					$this->search,
					fn($query) => $query->where('listings.title', 'like', "%$this->search%")
				)
				->where('listings.is_archived', '=', $this->archived)
				->when(
					$this->scent_id,
					fn($query) => $query->where('listings.scent_id', '=', $this->scent_id),
				)
				->when(
					$this->product_type_id,
					fn($query) => $query->where('listings.product_type_id', '=', $this->product_type_id),
				)
				->select([
					'listings.id',
					'listings.scent_id',
					'listings.product_type_id',
					'listings.title',
					'listings.meta',
					'listings.ending_at',
					'listings.state_enum',
					'listings.created_at',
					DB::raw("`listings`.`meta`->>'$.views' AS `views`"),
					DB::raw("`listings`.`meta`->>'$.num_favorers' AS `num_favorers`")
				])
				->when(
					in_array($this->order_column, ['views', 'num_favorers']),
					fn($query) => $query
						->orderBy(DB::raw('CHAR_LENGTH(' . $this->order_column . ')'), $this->order_desc ? 'desc' : 'asc')
						->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc'),
					fn($query) => $query->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc'),
				)
				->leftJoin('product_types', 'listings.product_type_id', '=', 'product_types.id')
				->leftJoin('scents', 'listings.scent_id', '=', 'scents.id')
				->withCount('transactions')
				->selectSub(
					Transaction
						::query()
						->selectRaw("sum(price->>'$.amount' / price->>'$.divisor')")
						->whereRaw('`listings`.`listing_id` = `transactions`.`listing_id`')
					->when(
						$this->sales_before,
						fn($query) => $query->where('transactions.created_at', '<', Carbon::parse($this->sales_before)),
					)
					->when(
						$this->sales_after,
						fn($query) => $query->where('transactions.created_at', '>', Carbon::parse($this->sales_after)),
					)
					,
					'revenue'
				)
				//				->selectRaw("(select sum(price ->> '$.amount' / price ->> '$.divisor') from `transactions` where `listings`.`listing_id` = `transactions`.`listing_id`) as `revenue`")
				//				->withSum('transactions as revenue', DB::raw("price->>'$.amount' / price->>'$.divisor'"))
//								->ddRawSql()
				->paginate($this->perPage),
		]);
	}

	public function orderBy(string $field, bool $desc_first = false):void
	{
		if ( $this->order_column === $field ) {
			$this->order_desc = !$this->order_desc;
		} else {
			$this->order_desc = $desc_first;
		}
		$this->order_column = $field;
	}

	public function orderByKey(string $key, bool $desc_first = false):void
	{
		if ( $field = match ( $key ) {
			'ended' => "listings.meta->>'$.ending_timestamp'",
			default => null
		} ) {
			if ( $this->order_column === $field ) {
				$this->order_desc = !$this->order_desc;
			} else {
				$this->order_desc = $desc_first;
			}
			$this->order_column = $field;
		}
	}

	public function updatedSearch():void
	{
		$this->resetPage();
	}

	public function updateProductType(string $listing_id, ?string $product_type_id):void
	{
		Validator::validate(compact('listing_id', 'product_type_id'), [
			'listing_id'      => 'required|exists:listings,id',
			'product_type_id' => 'nullable|exists:product_types,id',
		]);
		Listing::where('id', $listing_id)->update(['product_type_id' => $product_type_id]);
	}

	public function updateScent(string $listing_id, ?string $scent_id):void
	{
		$scent_id = $scent_id ?: null;
		Validator::validate(compact('listing_id', 'scent_id'), [
			'listing_id' => 'required|exists:listings,id',
			'scent_id'   => 'nullable|exists:scents,id',
		]);
		Listing::where('id', $listing_id)->update(['scent_id' => $scent_id]);
	}

	public function archive(string $listing_id):void
	{
		Listing::where('id', $listing_id)->update(['is_archived' => true]);
	}

	public function unarchive(string $listing_id):void
	{
		Listing::where('id', $listing_id)->update(['is_archived' => false]);
	}

	public function allTime():void
	{
		$this->reset(['sales_before', 'sales_after']);
	}

	public function lastYear():void
	{
		$this->sales_before = now()->subYear()->endOfYear()->format('Y-m-d');
		$this->sales_after = now()->subYear()->startOfYear()->format('Y-m-d');
	}

	public function last12Months():void
	{
		$this->sales_before = now()->format('Y-m-d');
		$this->sales_after = now()->subYear()->format('Y-m-d');
	}

	public function last30Days():void
	{
		$this->sales_before = now()->format('Y-m-d');
		$this->sales_after = now()->subMonth()->format('Y-m-d');
	}

	public function last24Hours():void
	{
		$this->sales_before = now()->format('Y-m-d');
		$this->sales_after = now()->subDay()->format('Y-m-d H:i:s');
	}

}
