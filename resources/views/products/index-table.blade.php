@props([
	/** @var \Illuminate\Database\Eloquent\Collection<array-key,\App\Models\Product> $products */
	'products',
])
<div class="l_rows">
	@if ( $errors->any() )
		<div>
			<ul>
				@foreach ( $errors->all() as $error )
					<li class="u_alert --danger"><i class="fa fa-triangle-exclamation"></i> {!! __($error) !!}</li>
				@endforeach
			</ul>
		</div>
	@endif
	<div class="l_cols">
		@if( !$archived )
			<h2>Active Products <small>({{ $active_count }})</small></h2>
			<a wire:click.prevent="$toggle('archived')" href="#">View {{ $archived_count }} Archived</a>
		@else
			<h2>Archived Products <small>({{ $archived_count }})</small></h2>
			<a wire:click.prevent="$toggle('archived')" href="#">View {{ $active_count }} Active</a>
		@endif
	</div>
	<div class="m_table__container">
		<table class="m_table">
			<thead>
			<tr>
				<x-th.sortable label="Scent" column="scents.label"/>
				<x-th.sortable label="Type" column="product_types.label"/>
				<x-th.sortable label="Label" column="products.label"/>
				<th>Etsy Listings</th>
				<th><span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span></th>
			</tr>
			</thead>
			<tbody>
			@foreach( $products as $product )
				<tr wire:key="{{ $product->id }}">
					<td @class(['t_highlight' => !$product->scent_id])>
						<x-form.select
							wire:change="updateScent('{{ $product->id }}', $event.target.value)"
							:options="$scent_options"
							:default="$product->scent_id"
							initial="-- SCENT --"
							style="width:14rem"
						/>
					</td>
					<td @class(['t_highlight' => !$product->product_type_id])>
						<x-form.select
							wire:change="updateProductType('{{ $product->id }}', $event.target.value)"
							:options="$product_type_options"
							:default="$product->product_type_id"
							initial="-- PRODUCT TYPE --"
							style="width:14rem"
						/>
					</td>
					<td>
						<label>
							<input
								class="input"
								name="label"
								value="{{ $product->label }}"
								maxlength="255"
								style="width:32rem"
								wire:change.blur="updateLabel('{{ $product->id }}', $event.target.value)"
							/>
						</label>
					</td>
					<td>
						{{ $product->etsy_listings_count }}
					</td>
					<td>
						<a href="javascript:navigator.clipboard.writeText('{{ $product->id }}')" title="{{ $product->id }}">#</a>
						@if( $product->is_archived )
							<button
								type="button"
								class="u_btn --success --sm --tt-left"
								wire:click="unarchive('{{ $product->id }}')"
								data-tooltip="Unarchive"
							>@svg('icon-rotate-left')</button>
						@else
							<button
								type="button"
								class="u_btn --warning --sm"
								wire:click="archive('{{ $product->id }}')"
								data-tooltip="Archive"
							>@svg('icon-box-archive')</button>
						@endif
						<button
							type="button"
							class="u_btn --danger --sm"
							wire:click="delete('{{ $product->id }}')"
							data-tooltip="Delete"
						>@svg('icon-trash-xmark')</button>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
