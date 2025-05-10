<?php

namespace App\Livewire;

use App\Models\EtsyListing;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Scent;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class EtsyListingsTable extends Component
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
			->get(['id', 'label', 'scent_id', 'product_type_id'])
			->reduce(fn(array $c, Product $product) => $c + [
					$product->id => "$product->label [{$product->scent?->code} - {$product->productType?->code}]",
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
		$this->dispatch('render');
		$this->initCollection();
		return view('etsy.listings.index-table', [
			'etsyListings'   => $this
				->collection
				->paginate($this->perPage),
			'active_count'   => $this->filteredQuery()->where('etsy_listings.is_archived', false)->count(),
			'archived_count' => $this->filteredQuery()->where('etsy_listings.is_archived', true)->count(),
		]);
	}

	private function initCollection():void
	{
		$this->collection = $this
			->filteredQuery()
			->where('etsy_listings.is_archived', '=', $this->archived)
			->select([
				'products.label',
				//				'product_types.label',
				//				'scents.label',
				'etsy_listings.id',
				'etsy_listings.product_id',
				//				'etsy_listings.scent_id',
				//				'etsy_listings.product_type_id',
				'etsy_listings.title',
				'etsy_listings.meta',
				'etsy_listings.ending_at',
				'etsy_listings.state_enum',
				'etsy_listings.is_archived',
				'etsy_listings.listing_id',
				DB::raw("`etsy_listings`.`meta`->>'$.views' AS `views`"),
				DB::raw("`etsy_listings`.`meta`->>'$.num_favorers' AS `num_favorers`")
			])
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
			->selectSub(
				Transaction
					::query()
					->selectRaw('count(*)')
					->whereColumn('etsy_listings.listing_id', 'transactions.listing_id')
					->when(
						$this->sales_before,
						fn($query) => $query->where('transactions.created_at', '<', Carbon::parse($this->sales_before)),
					)
					->when(
						$this->sales_after,
						fn($query) => $query->where('transactions.created_at', '>', Carbon::parse($this->sales_after)),
					),
				'transactions_count'
			)
			->selectSub(
				Transaction
					::query()
					->selectRaw("sum(price->>'$.amount' / price->>'$.divisor')")
					->whereColumn('etsy_listings.listing_id', 'transactions.listing_id')
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
			->selectRaw("TIMESTAMPDIFF(MONTH, `etsy_listings`.`created_at`, NOW()) as age")
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
			//			->leftJoin('product_types', 'etsy_listings.product_type_id', '=', 'product_types.id')
			//			->leftJoin('scents', 'etsy_listings.scent_id', '=', 'scents.id')
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

	public function updateProduct(string $listing_id, ?string $product_id):void
	{
		Validator::validate(compact('listing_id', 'product_id'), [
			'listing_id' => 'required|exists:etsy_listings,id',
			'product_id' => 'nullable|exists:products,id',
		]);
		EtsyListing::where('id', $listing_id)->update(['product_id' => $product_id]);
	}

	//	public function updateProductType(string $listing_id, ?string $product_type_id):void
	//	{
	//		Validator::validate(compact('listing_id', 'product_type_id'), [
	//			'listing_id'      => 'required|exists:etsy_listings,id',
	//			'product_type_id' => 'nullable|exists:product_types,id',
	//		]);
	//		EtsyListing::where('id', $listing_id)->update(['product_type_id' => $product_type_id]);
	//	}
	//
	//	public function updateScent(string $listing_id, ?string $scent_id):void
	//	{
	//		$scent_id = $scent_id ?: null;
	//		Validator::validate(compact('listing_id', 'scent_id'), [
	//			'listing_id' => 'required|exists:etsy_listings,id',
	//			'scent_id'   => 'nullable|exists:scents,id',
	//		]);
	//		EtsyListing::where('id', $listing_id)->update(['scent_id' => $scent_id]);
	//	}

	public function archive(string $listing_id):void
	{
		EtsyListing::where('id', $listing_id)->update(['is_archived' => true]);
	}

	public function unarchive(string $listing_id):void
	{
		EtsyListing::where('id', $listing_id)->update(['is_archived' => false]);
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
