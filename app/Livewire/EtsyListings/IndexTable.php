<?php

namespace App\Livewire\EtsyListings;

use App\Models\EtsyListing;
use App\Models\EtsyTransaction;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Scent;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTable extends Component
{
	use WithPagination;

	public array $product_options;
	public array $scent_options;
	public array $product_type_options;
	#[Url]
	public int $perPage = 50;
	#[Url]
	public string $search = '';
	#[Url]
	public string $order_column = 'etsy_listings.created_at';
	#[Url]
	public bool $order_desc = true;
	#[Url]
	public bool $archived = false;
	#[Url]
	public ?bool $edit_mode = false;
	#[Url]
	public ?string $scent_id = null;
	#[Url]
	public ?string $product_type_id = null;
	#[Url]
	public ?string $sales_before = null;
	#[Url]
	public ?string $sales_after = null;
	/** @var Collection<array-key,EtsyListing> $collection */
	public Collection $collection;
	#[Url]
	public ?string $status = null;

	public function mount():void
	{
		$this->product_options = Product
			::query()
			->orderBy('label')
			->with(['scent:id,code', 'productType:id,code'])
			->get(['id', 'label', 'scent_id', 'product_type_id', 'is_bundle'])
			->reduce(fn(array $c, Product $product) => $c + [
					$product->id => ( $product->is_bundle ? '[BUNDLE]' : '[' . ( $product->productType?->code ?: '??' ) . '-' . ( $product->scent?->code ?: '??' ) .']') . ' ' . $product->label,
				], []);
		$this->scent_options = Scent
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, Scent $scent) => $c + [
					$scent->id => "$scent->code - $scent->label"
				], []);
		$this->product_type_options = ProductType
			::query()
			->orderBy('code')
			->get(['id', 'code', 'label'])
			->reduce(fn(array $c, ProductType $productType) => $c + [
					$productType->id => "$productType->code - $productType->label"
				], []);
		$this->initCollection();
	}

	public function render():View
	{
		$this->initCollection();
		return view('etsy.listings.index-table', [
			'etsyListings'   => $this
				->collection
				->paginate($this->perPage),
			'active_count'   => $this->filteredQuery()->where('etsy_listings.is_archived', false)->count(),
			'archived_count' => $this->filteredQuery()->where('etsy_listings.is_archived', true)->count(),
		]);
	}

	protected function getCustomOrders():array
	{
		return [
			'ended' => fn(Builder $query):Builder => $query->orderByRaw("etsy_listings.meta->>'$.ending_timestamp'")
		];
	}

	private function initCollection():void
	{
		$this->collection = $this
			->filteredQuery()
			->where('etsy_listings.is_archived', '=', $this->archived)
			->select([
				'products.label',
				'etsy_listings.id',
				'etsy_listings.product_id',
				'etsy_listings.title',
				'etsy_listings.thumbnail',
				'etsy_listings.meta',
				'etsy_listings.ending_at',
				'etsy_listings.state_enum',
				'etsy_listings.is_archived',
				DB::raw("`etsy_listings`.`meta`->>'$.views' AS `views`"),
				DB::raw("`etsy_listings`.`meta`->>'$.num_favorers' AS `num_favorers`")
			])
			->selectSub(
				EtsyTransaction
					::query()
					->selectRaw('count(*)')
					->whereColumn('etsy_transactions.etsy_listing_id', 'etsy_listings.id')
					->when(
						$this->sales_before,
						fn($query) => $query->where('etsy_transactions.created_at', '<', Carbon::parse($this->sales_before)),
					)
					->when(
						$this->sales_after,
						fn($query) => $query->where('etsy_transactions.created_at', '>', Carbon::parse($this->sales_after)),
					),
				'etsy_transactions_count'
			)
			->selectSub(
				EtsyTransaction
					::query()
					->selectRaw('sum(price)')
					->whereColumn('etsy_transactions.etsy_listing_id', 'etsy_listings.id')
					->when(
						$this->sales_before,
						fn($query) => $query->where('etsy_transactions.created_at', '<', Carbon::parse($this->sales_before)),
					)
					->when(
						$this->sales_after,
						fn($query) => $query->where('etsy_transactions.created_at', '>', Carbon::parse($this->sales_after)),
					)
				,
				'revenue'
			)
			->when(
				in_array($this->order_column, ['views', 'num_favorers']),
				fn($query) => $query
					->orderBy(DB::raw('CHAR_LENGTH(' . $this->order_column . ')'), $this->order_desc ? 'desc' : 'asc')
					->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc'),
				fn($query) => $query
					->when(
						$this->order_column !== 'revenue_per_month',
						fn($query) => $query
							->orderBy($this->order_column, $this->order_desc ? 'desc' : 'asc'),
						fn($query) => $query
							->orderBy('revenue', $this->order_desc ? 'desc' : 'asc'),
					)
			)
			->selectRaw("TIMESTAMPDIFF(MONTH, `etsy_listings`.`created_at`, `etsy_listings`.`ending_at`) as age")
			->with([
				'product' => fn($query) => $query->with(['productType:id,code', 'scent:id,code'])
			])
			->get()
			->when(
				$this->order_column === 'revenue_per_month',
				fn(Collection $collection) => $collection
					->sortBy(
						callback: fn($etsyListing) => $etsyListing->revenue / $etsyListing->age,
						descending: $this->order_desc
					)
			);
	}

	/**
	 * @return Builder<EtsyListing>
	 */
	private function filteredQuery():Builder
	{
		return EtsyListing
			::query()
			->whereRaw('etsy_listings.`meta`->>"$.who_made" = "i_did"')
			->when(
				$this->search,
				fn($query) => $query->where('etsy_listings.title', 'like', "%$this->search%")
			)
			->when(
				$this->status === 'active',
				fn($query) => $query->whereIn('etsy_listings.state_enum', [
					EtsyListing::STATE_ACTIVE,
					EtsyListing::STATE_SOLD_OUT,
				])
			)
			->when(
				$this->status === 'inactive',
				fn($query) => $query->whereIn('etsy_listings.state_enum', [
					EtsyListing::STATE_INACTIVE,
					EtsyListing::STATE_EXPIRED,
					EtsyListing::STATE_DRAFT,
				])
			)
			->leftJoin('products', 'etsy_listings.product_id', '=', 'products.id')
			->when(
				$this->scent_id,
				fn($query) => $query->where('products.scent_id', '=', $this->scent_id),
			)
			->when(
				$this->product_type_id,
				fn($query) => $query->where('products.product_type_id', '=', $this->product_type_id),
			);
	}

	public function orderBy(string $field, bool $desc_first = false):void
	{
		if ( $this->order_column === $field ) {
			$this->order_desc = !$this->order_desc;
		} else {
			$this->order_desc = $desc_first;
		}
		$this->order_column = $field;
		$this->resetPage();
	}

	public function orderByKey(string $key, bool $desc_first = false):void
	{
		if ( $field = match ( $key ) {
			'ended' => "etsy_listings.meta->>'$.ending_timestamp'",
			default => null
		} ) {
			if ( $this->order_column === $field ) {
				$this->order_desc = !$this->order_desc;
			} else {
				$this->order_desc = $desc_first;
			}
			$this->order_column = $field;
		}
		$this->resetPage();
	}

	public function updated():void
	{
		$this->resetPage();
	}

	public function updatedSearch():void
	{
		$this->resetPage();
	}

	public function updateProduct(EtsyListing $etsyListing, ?string $product_id):void
	{
		Validator::validate(compact('product_id'), [
			'product_id' => 'nullable|exists:products,id',
		]);
		$etsyListing->update(['product_id' => $product_id]);
	}

	public function archive(EtsyListing $etsyListing):void
	{
		$etsyListing->update(['is_archived' => true]);
	}

	public function unarchive(EtsyListing $etsyListing):void
	{
		$etsyListing->update(['is_archived' => false]);
	}

	public function allTime():void
	{
		$this->reset(['sales_before', 'sales_after']);
	}

	public function previousYear():void
	{
		$this->sales_before = now()->subYear()->endOfYear()->addSecond()->format('Y-m-d');
		$this->sales_after = now()->subYear()->startOfYear()->format('Y-m-d');
	}

	public function last12Months():void
	{
		$this->sales_before = now()->addDay()->format('Y-m-d');
		$this->sales_after = now()->subYear()->format('Y-m-d');
	}

	public function last30Days():void
	{
		$this->sales_before = now()->addDay()->format('Y-m-d');
		$this->sales_after = now()->subMonth()->format('Y-m-d');
	}

	public function last24Hours():void
	{
		$this->sales_before = now()->addDay()->format('Y-m-d H:i:s');
		$this->sales_after = now()->subDay()->format('Y-m-d H:i:s');
	}

}
