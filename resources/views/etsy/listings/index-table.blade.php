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
			{{ $listings->links('pagination') }}
		</div>
		<div class="l_rows --sm">
			<div class="l_cols --md">
				<label>
					<input type="checkbox" wire:model.live="edit_mode"/> Edit Mode
				</label>
				<label>
					<input type="checkbox" wire:model.live="archived"/> Archived
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
			</div>
			<div class="l_cols --md --right">
				<fieldset>
					<legend>Sales for</legend>
					<button type="button" class="u_btn --sm" wire:click="allTime()">All Time</button>
					<button type="button" class="u_btn --sm" wire:click="lastYear()">Last Year</button>
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
	<style>
		.z_listing_title {
			max-width:     20rem;
			text-overflow: ellipsis;
			white-space:   nowrap;
			overflow:      hidden;
		}
	</style>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<x-th.sortable label="Product Type" column="product_types.label"/>
				<x-th.sortable label="Scent" column="scents.label"/>
				<x-th.sortable label="Title" column="listings.title"/>
				<x-th.sortable label="State" column="listings.state_enum"/>
				<x-th.sortable label="Views" column="views" :desc-first="true"/>
				<x-th.sortable label="Favs" column="num_favorers" :desc-first="true"/>
				<x-th.sortable label="Ending" column="ending_at"/>
				<x-th.sortable label="Age" column="listings.created_at" :desc-first="true"/>
				<x-th.sortable label="Sales" column="transactions_count" :desc-first="true"/>
				<x-th.sortable label="Revenue" column="revenue" :desc-first="true"/>
				<th><span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span></th>
			</tr>
			</thead>
			<tbody>
			@foreach( $listings as $listing )
				<tr>
					<td @style(['background:var(--clr-warning-lowest)'=>!$listing->product_type_id])>
						@if( $edit_mode )
							<x-form.select
								wire:change="updateProductType('{{ $listing->id }}', $event.target.value)"
								:options="$product_type_options"
								:default="$listing->product_type_id"
								initial="-- PRODUCT TYPE --"
								style="width:14rem"
							/>
						@else
							<span data-tooltip="{{ $listing->productType?->label }}" class="--tt-right --tt-sm">{{ $listing->productType?->code ?: '[Undefined]' }}</span>
						@endif
					</td>
					<td>
						@if( $edit_mode )
							<x-form.select
								wire:change="updateScent('{{ $listing->id }}', $event.target.value)"
								:options="$scent_options"
								:default="$listing->scent_id"
								initial="-- SCENT --"
								style="width:14rem"
							/>
						@else
							@if( $listing->scent_id )
								<span
									data-tooltip="{{ $listing->scent->label }}"
									class="--tt-right --tt-sm"
								>{{ $listing->scent->code }}</span>
							@else
								<span class="u_badge --warning">Undefined</span>
							@endif
						@endif
					</td>
					<td>
						<div class="l_cols">
							<a href="{{ $listing->url }}" target="_blank"><img src="{{ $listing->thumbnail }}" alt="Thumbnail"/></a>
							<div data-tooltip="{{ $listing->title }}" class="--tt-lg">
								<div class="z_listing_title">
									{!! $listing->title !!}
								</div>
							</div>
						</div>
					</td>
					<td>
						<div class="u_badge {{  $listing->state_class }}">{{ $listing->state }}</div>
					</td>
					<td>
						<div class="l_cols --sm">@svg('icon-eye', 'text-success') {{ number_format($listing->meta['views']) }}</div>
					</td>
					<td>
						<div class="l_cols --sm">@svg('icon-heart', 'text-success') {{ number_format($listing->meta['num_favorers']) }}</div>
					</td>
					<td class="nowrap">
						<div class="l_cols --sm">@svg('icon-calendar-days', 'text-muted') {{ $listing->ending_at->format('M d, Y') }}</div>
						<small>{{ $listing->ending_at->diffForHumans(short:true) }}</small>
					</td>
					<td>
						{{ $listing->created_at->diffForHumans(syntax: true, short:true) }}
					</td>
					<td>
						<div class="l_cols --sm">@svg('icon-sack-dollar', 'text-success') {{ $listing->transactions_count }}</div>
					</td>
					<td>${{ number_format($listing->revenue, 2) }}</td>
					<td class="nowrap">
						<a href="javascript:navigator.clipboard.writeText('{{ $listing->id }}')" title="{{ $listing->id }}">#</a>
						@if( $edit_mode )
							@if( $listing->is_archived )
								<button type="button" class="u_btn" wire:click="unarchive('{{ $listing->id}}')" data-tooltip="Unarchive">@svg('icon-box-archive')</button>
							@else
								<button type="button" class="u_btn" wire:click="archive('{{ $listing->id}}')" data-tooltip="Archive">@svg('icon-box-archive')</button>
							@endif
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $listings->links('pagination') }}
</div>
