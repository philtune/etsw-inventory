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
			{{ $collection->links('pagination') }}
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
		<table class="m_table">
			<thead>
			<tr>
				<x-th.sortable label="Type" column="product_types.label"/>
				<x-th.sortable label="Scent" column="scents.label"/>
				<x-th.sortable label="Label" column="products.label"/>
				<x-th.sortable label="Archived" column="products.is_archived"/>
				<x-th.sortable label="Stockable" column="products.can_stock"/>
				<x-th.actions/>
			</tr>
			</thead>
			<tbody>
			@foreach( $collection as $product )
				<tr wire:key="{{ $product->id }}">
					<td @class(['t_highlight' => !$product->product_type_id])>
						<x-form.select
							wire:change="updateProductType('{{ $product->id }}', $event.target.value)"
							:options="$product_type_options"
							:default="$product->product_type_id"
							initial="-- PRODUCT TYPE --"
							style="width:10rem"
						/>
					</td>
					<td @class(['t_highlight' => !$product->scent_id])>
						<x-form.select
							wire:change="updateScent('{{ $product->id }}', $event.target.value)"
							:options="$scent_options"
							:default="$product->scent_id"
							initial="-- SCENT --"
							style="width:10rem"
						/>
					</td>
					<td>
						<label>
							<input
								class="input"
								name="label"
								value="{{ $product->label }}"
								maxlength="255"
								style="width:24rem"
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
					<x-td.actions :last="$loop->last">
						<a
							class="u_btn --bare"
							href="javascript:navigator.clipboard.writeText('{{ $product->id }}')"
							onclick="toast('Copied!', '--success')"
						>@svg('icon-copy') Copy Product ID</a>
						<x-modal-wire-delete
							model-name="Product"
							:can-restore="true"
							:push-to="$stack_id"
							wire:click="delete('{{ $product->id }}')"
						/>
					</x-td.actions>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	{{ $collection->links('pagination') }}
	@stack($stack_id)
</div>
