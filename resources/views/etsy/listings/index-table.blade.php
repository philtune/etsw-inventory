@props([
	/** @var \Illuminate\Pagination\LengthAwarePaginator<\App\Models\EtsyListing> */
	'etsyListings'
])
<div class="l_rows">
	@if ( session('status') )
		<div style="border: 1px solid;border-radius: 4px;padding:6px;">
			{!! session('status') !!}
		</div>
	@endif
	@if ( $errors->any() )
		<ul style="border: 1px solid;border-radius: 4px;padding:6px;">
			@foreach ( $errors->all() as $error )
				<li>Error: {!! $error !!}</li>
			@endforeach
		</ul>
	@endif
	<div style="display: flex; justify-content: space-between; gap: 1rem; align-items: end;">
		<div>
			{{ $etsyListings->links('pagination') }}
		</div>
		<div class="l_rows --sm">
			<div class="l_cols --md">
				<label>
					<input type="checkbox" wire:model.live="edit_mode"/> Edit Mode
				</label>
				<input
					type="search"
					wire:model.live.debounce="search"
					placeholder="Search"
					class="input"
				/>
				<label>
					<x-form.select
						wire:model.live="product_type_id"
						:options="$product_type_options"
						initial="-- PRODUCT TYPE --"
						style="width:14rem"
						:default="$product_type_id"
					/>
				</label>
				<label>
					<x-form.select
						wire:model.live="scent_id"
						:options="$scent_options"
						initial="-- SCENT --"
						style="width:14rem"
						:default="$scent_id"
					/>
				</label>
				<label>
					<x-form.select
						wire:model.live="status"
						:options="['active'=>'Active', 'inactive' => 'Inactive']"
						initial="-- STATUS --"
						style="width:14rem"
						:default="$status"
					/>
				</label>
			</div>
			<div class="l_cols --md --right">
				<fieldset>
					<legend>Sales for</legend>
					<button type="button" class="u_btn --sm" wire:click="allTime()">All Time</button>
					<button type="button" class="u_btn --sm" wire:click="previousYear()">Previous Year</button>
					<button type="button" class="u_btn --sm" wire:click="last12Months()">Last 12 months</button>
					<button type="button" class="u_btn --sm" wire:click="last30Days()">Last 30 days</button>
					<button type="button" class="u_btn --sm" wire:click="last24Hours()">Last 24 hours</button>
				</fieldset>
				<label>
					<small>Sales From</small><br/>
					<input
						type="date"
						id="sales_after"
						wire:model.live.debounce="sales_after"
						class="input"
					/>
				</label>
				<label>
					<small>Sales To</small><br/>
					<input
						type="date"
						id="sales_before"
						wire:model.live.debounce="sales_before"
						class="input"
					/>
				</label>
			</div>
		</div>
	</div>
	<div class="l_cols">
		@if( !$archived )
			<h2>Active Listings</h2>
			<a wire:click.prevent="$toggle('archived')" href="#">View {{ $archived_count }} Archived</a>
		@else
			<h2>Archived Listings</h2>
			<a wire:click.prevent="$toggle('archived')" href="#">View {{ $active_count }} Active</a>
		@endif
	</div>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<x-th.sortable label="Product" column="products.label"/>
				{{--				<x-th.sortable label="Scent" column="scents.label"/>--}}
				{{--				<x-th.sortable label="Product Type" column="product_types.label"/>--}}
				<x-th.sortable label="Title" column="etsy_listings.title"/>
				<x-th.sortable label="State" column="etsy_listings.state_enum"/>
				<x-th.sortable label="Views" column="views" :desc-first/>
				<x-th.sortable label="Favs" column="num_favorers" :desc-first/>
				<x-th.sortable label="Ending" column="ending_at"/>
				<x-th.sortable label="Age" column="age" :desc-first/>
				<x-th.sortable label="Sales" column="etsy_transactions_count" :desc-first/>
				<x-th.sortable label="Revenue" column="revenue" :desc-first/>
				<x-th.sortable label="Rev/mo" column="revenue_per_month" :desc-first/>
				<th><span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span></th>
			</tr>
			</thead>
			<tbody>
			@foreach( $etsyListings as $etsyListing )
				<tr wire:key="{{ $etsyListing->id }}">
					<td @class(['t_highlight' => !$etsyListing->product_id])>
						@if( $edit_mode )
							<x-form.select
								wire:change="updateProduct('{{ $etsyListing->id }}', $event.target.value)"
								:options="$product_options"
								:default="$etsyListing->product_id"
								initial="-- PRODUCT --"
								style="width:14rem"
							/>
						@else
							<span data-tooltip="{{ $etsyListing->product?->label ?: '[Undefined]' }}" class="--tt-right --tt-lg">{{ $etsyListing->product?->productType->code ?? '?' }} - {{ $etsyListing->product?->scent->code ?? '?' }}</span>
						@endif
					</td>
					{{--					<td @class(['t_highlight' => !$etsyListing->scent_id])>--}}
					{{--						@if( $edit_mode )--}}
					{{--							<x-form.select--}}
					{{--								wire:change="updateScent('{{ $etsyListing->id }}', $event.target.value)"--}}
					{{--								:options="$scent_options"--}}
					{{--								:default="$etsyListing->scent_id"--}}
					{{--								initial="-- SCENT --"--}}
					{{--								style="width:14rem"--}}
					{{--							/>--}}
					{{--						@else--}}
					{{--							@if( $etsyListing->scent_id )--}}
					{{--								<span--}}
					{{--									data-tooltip="{{ $etsyListing->scent->label }}"--}}
					{{--									class="--tt-right --tt-sm"--}}
					{{--								>{{ $etsyListing->scent->code }}</span>--}}
					{{--							@else--}}
					{{--								<span data-tooltip="{{ $etsyListing->scent?->label }}" class="--tt-right --tt-sm">{{ $etsyListing->scent?->code ?: '[Undefined]' }}</span>--}}
					{{--							@endif--}}
					{{--						@endif--}}
					{{--					</td>--}}
					{{--					<td @class(['t_highlight' => !$etsyListing->product_type_id])>--}}
					{{--						@if( $edit_mode )--}}
					{{--							<x-form.select--}}
					{{--								wire:change="updateProductType('{{ $etsyListing->id }}', $event.target.value)"--}}
					{{--								:options="$product_type_options"--}}
					{{--								:default="$etsyListing->product_type_id"--}}
					{{--								initial="-- PRODUCT TYPE --"--}}
					{{--								style="width:14rem"--}}
					{{--							/>--}}
					{{--						@else--}}
					{{--							<span data-tooltip="{{ $etsyListing->productType?->label }}" class="--tt-right --tt-sm">{{ $etsyListing->productType?->code ?: '[Undefined]' }}</span>--}}
					{{--						@endif--}}
					{{--					</td>--}}
					<td>
						<div class="l_cols">
							<a href="https://www.etsy.com/your/shops/me/listing-editor/edit/{{ $etsyListing->listing_id }}" target="_blank"><img src="{{ $etsyListing->thumbnail }}" alt="Thumbnail"/></a>
							<div data-tooltip="{{ $etsyListing->title }}" class="--tt-lg">
								{!! Str::limit($etsyListing->title, 48) !!}
							</div>
						</div>
					</td>
					<td>
						<div class="u_badge {{  $etsyListing->state_class }}">{{ $etsyListing->state }}</div>
					</td>
					<td>
						<div class="l_cols --sm">@svg('icon-eye', 'text-success') {{ number_format($etsyListing->meta['views']) }}</div>
					</td>
					<td>
						<div class="l_cols --sm">@svg('icon-heart', 'text-success') {{ number_format($etsyListing->meta['num_favorers']) }}</div>
					</td>
					<td class="nowrap">
						<div class="l_cols --sm">@svg('icon-calendar-days', 'text-muted') {{ $etsyListing->ending_at->format('M d, Y') }}</div>
						<small>{{ $etsyListing->ending_at->diffForHumans(short:true) }}</small>
					</td>
					<td>
						{{ $etsyListing->age }} mos
					</td>
					<td>
						<div class="l_cols --sm">@svg('icon-sack-dollar', 'text-success') {{ $etsyListing->etsy_transactions_count }}</div>
						<small>{{ round($etsyListing->etsy_transactions_count / ($etsyListing->age ?: 1)) }}/mo</small>
					</td>
					<td class="text-right">
						${{ number_format($etsyListing->revenue, 2) }}
					</td>
					<td class="text-right">
						${{ number_format($etsyListing->revenue / ($etsyListing->age ?: 1), 2) }}/mo
					</td>
					<td class="nowrap">
						<a href="javascript:navigator.clipboard.writeText('{{ $etsyListing->id }}')" title="{{ $etsyListing->id }}">#</a>
						@if( $edit_mode )
							@if( $etsyListing->is_archived )
								<button type="button" class="u_btn --success --sm --tt-left" wire:click="unarchive('{{ $etsyListing->id}}')" data-tooltip="Unarchive">@svg('icon-rotate-left')</button>
							@else
								<button type="button" class="u_btn --warning --sm" wire:click="archive('{{ $etsyListing->id}}')" data-tooltip="Archive">@svg('icon-box-archive')</button>
							@endif
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $etsyListings->links('pagination') }}
</div>
