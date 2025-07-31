<x-layouts.app>
	<x-slot:toolbar>
		<x-modal-create
			model-name="Product Type"
			:action="route('product-types.store')"
		>
			@include('product-types.form-inputs')
		</x-modal-create>
	</x-slot:toolbar>
	<div class="l_cols">
		<table class="m_table w-auto">
			<thead>
			<tr>
				<th><span data-tooltip="Actions" class="--tt-right">@svg('icon-ellipsis-vertical')</span></th>
				<th>Code</th>
				<th>Label</th>
				<th>Variants</th>
				<th>Bundle</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $productTypes as $productType )
				@php($uid = 'edit_' . $productType->id)
				<tr>
					<td>
						<div class="l_cols --sm">
							<x-modal-edit
								model-name="Product Type"
								:action="route('product-types.update', $productType)"
							>
								@include('product-types.form-inputs')
							</x-modal-edit>
							<x-modal-delete
								:action="route('product-types.delete', $productType)"
								model-name="Product Type"
								:can-restore="true"
							/>
						</div>
					</td>
					<td>{{ $productType->code }}</td>
					<td>{{ $productType->label }}</td>
					<td>
						@if( $productType->variants )
							{{ $productType->variants->label ?? '[Unlabelled]' }}:<br/>
							<select
								style="display: block;width:100%;"
								size="{{ count((array) $productType->variants->options ?? []) }}"
								multiple
								disabled
							>
								@foreach( $productType->variants->options ?? [] as $value => $label )
									<option
										value="{{ $value }}"
										@selected($value == $productType->variants->default)
									>{{ $label }}</option>
								@endforeach
							</select>
						@endif
					</td>
					<td>
						@if( $productType->is_bundle)
							{{  $productType->childProductTypes()->pluck('id')->implode(fn(string $id) => $child_product_type_options[$id], ' + ') }}
						@endif
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
