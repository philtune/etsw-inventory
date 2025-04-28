<div>
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
		<p style="display:flex;align-items:center;gap:0.5rem">
			<input
				type="search"
				wire:model.live.debounce="search"
				placeholder="Search"
				class="input"
			/>
			<label>
				<input type="checkbox" wire:model.live="edit_mode"/> Edit Mode
			</label>
			<label>
				<input type="checkbox" wire:model.live="archived"/> Archived
			</label>
			<x-form.select
				wire:model.live="product_type_id"
				:options="$product_type_options"
				initial="-- PRODUCT TYPE --"
				style="width:14rem"
				:default="$product_type_id"
			/>
			<x-form.select
				wire:model.live="scent_id"
				:options="$scent_options"
				initial="-- SCENT --"
				style="width:14rem"
				:default="$scent_id"
			/>
			<label data-tooltip="Sales After">
				<input
					type="datetime-local"
					wire:model.debounce="sales_after"
					class="input"
				/>
			</label>
			<label data-tooltip="Sales Before">
				<input
					type="datetime-local"
					wire:model.debounce="sales_before"
					class="input"
				/>
			</label>
		</p>
	</div>
	<style>
		.listing_title {
			max-width:     20rem;
			text-overflow: ellipsis;
			white-space:   nowrap;
			overflow:      hidden;
		}
	</style>
	<div id="overflow" style="max-width:100%;overflow:auto">
		<table style="width:100%">
			<thead>
			<tr>
				<x-th.sortable label="Product Type" column="product_types.label"/>
				<x-th.sortable label="Scent" column="scents.label"/>
				<th style="width:1px">Thumb</th>
				<x-th.sortable label="Title" column="listings.title"/>
				<x-th.sortable label="State" column="listings.state_enum"/>
				<x-th.sortable label="Views" column="views"/>
				<x-th.sortable label="Favs" column="num_favorers"/>
				<th title="Inventory">Inv.</th>
				<x-th.sortable label="Ending" column="ending_at"/>
				<x-th.sortable label="Sales" column="transactions_count"/>
				<x-th.sortable column="revenue">
					<x-slot:label>
						<span data-tooltip="Revenue" class="--tt-left">Rev.</span>
					</x-slot:label>
				</x-th.sortable>
				<th>
					<span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span>
				</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $listings as $listing )
				<tr>
					<td class="text-center w-1px" @style(['background:#fff6e2'=>!$listing->product_type_id])>
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
					<td class="text-center w-1px" @style(['background:#fff6e2'=>!$listing->scent_id])>
						@if( $edit_mode )
							<x-form.select
								wire:change="updateScent('{{ $listing->id }}', $event.target.value)"
								:options="$scent_options"
								:default="$listing->scent_id"
								initial="-- SCENT --"
								style="width:14rem"
							/>
						@else
							<span data-tooltip="{{ $listing->scent?->label }}" class="--tt-right --tt-sm">{{ $listing->scent?->code ?: '[Undefined]' }}</span>
						@endif
					</td>
					<td>
						<a href="{{ $listing->url }}" target="_blank"><img src="{{ $listing->thumbnail }}" alt="Thumbnail"/></a>
					</td>
					<td>
						<div data-tooltip="{{ $listing->title }}" class="--tt-lg">
							<div class="listing_title">
								{!! $listing->title !!}
							</div>
						</div>
					</td>
					<td>
						<div class="u_badge {{  $listing->state_class }}">{{ $listing->state }}</div>
					</td>
					<td>{{ number_format($listing->meta['views']) }}</td>
					<td>{{ number_format($listing->meta['num_favorers']) }}</td>
					<td>{{ $listing->quantity }}</td>
					<td>
						{{ $listing->ending_at->format('Y/m/d') }}<br/>
						{{ $listing->ending_at->diffForHumans(short:true) }}
					</td>
					<td>{{ $listing->transactions_count }}</td>
					<td>${{ number_format($listing->revenue, 2) }}</td>
					<td>
						@svg('icon-ellipsis-vertical')
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
