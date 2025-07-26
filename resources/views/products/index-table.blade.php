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
	<div class="l_cols --split">
		<div>
			{{ $products->links('pagination') }}
		</div>
		<div>
			<input
				type="search"
				wire:model.live.debounce="search"
				placeholder="Search"
				class="input"
			/>
		</div>
	</div>
	<div class="m_table__container">
		<table class="m_table w-auto">
			<thead>
			<tr>
				<x-th.sortable label="Type" column="product_types.label"/>
				<x-th.sortable label="Scent" column="scents.label"/>
				<x-th.sortable label="Label" column="products.label"/>
				<x-th.sortable label="Archived" column="products.is_archived"/>
				<x-th.sortable label="Stockable" column="products.can_stock"/>
				<th><span data-tooltip="Actions" class="--tt-left">@svg('icon-ellipsis-vertical')</span></th>
			</tr>
			</thead>
			<tbody>
			@foreach( $products as $product )
				<tr wire:key="{{ $product->id }}">
					<td @class(['t_highlight' => !$product->product_type_id])>
						<x-form.select
							wire:change="updateProductType('{{ $product->id }}', $event.target.value)"
							:options="$product_type_options"
							:default="$product->product_type_id"
							initial="-- PRODUCT TYPE --"
							style="width:14rem"
						/>
					</td>
					<td @class(['t_highlight' => !$product->scent_id])>
						<x-form.select
							wire:change="updateScent('{{ $product->id }}', $event.target.value)"
							:options="$scent_options"
							:default="$product->scent_id"
							initial="-- SCENT --"
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
					<td class="text-center">
						<label>
							<input
								type="checkbox"
								@checked($product->is_archived)
								wire:change="toggleIsArchived('{{ $product->id }}')"
							/>
						</label>
						{{--						@if( $product->is_archived )--}}
						{{--							<button--}}
						{{--								type="button"--}}
						{{--								class="u_btn --success --sm"--}}
						{{--								wire:click="unarchive('{{ $product->id }}')"--}}
						{{--								data-tooltip="Unarchive"--}}
						{{--							>@svg('icon-rotate-left')</button>--}}
						{{--						@else--}}
						{{--							<button--}}
						{{--								type="button"--}}
						{{--								class="u_btn --warning --sm"--}}
						{{--								wire:click="archive('{{ $product->id }}')"--}}
						{{--								data-tooltip="Archive"--}}
						{{--							>@svg('icon-box-archive')</button>--}}
						{{--						@endif--}}
					</td>
					<td class="text-center">
						<label>
							<input
								type="checkbox"
								@checked($product->can_stock)
								wire:change="toggleCanStock('{{ $product->id }}')"
							/>
						</label>
					</td>
					<td>
						<a
							class="u_btn --sm --tt-left"
							href="javascript:navigator.clipboard.writeText('{{ $product->id }}')"
							data-tooltip="Copy {{ $product->id }}"
						>#</a>
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
	{{ $products->links('pagination') }}
</div>
