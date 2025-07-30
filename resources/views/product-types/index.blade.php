<x-layouts.app>
	<x-slot:toolbar>
		<form method="POST" action="{{ route('product-types.store') }}" class="l_cols --sm">
			@csrf
			<label>
				Code
				<input
					class="input"
					name="code"
					maxlength="16"
				/>
			</label>
			<label>
				Label
				<input
					class="input"
					name="label"
					maxlength="255"
				/>
			</label>
			<label>
				Is Bundle?
				<input
					type="checkbox"
					name="is_bundle"
				/>
			</label>
			<button type="submit" class="u_btn">Add Product Type</button>
		</form>
	</x-slot:toolbar>
	<div class="l_cols">
		<table class="m_table w-auto">
			<thead>
			<tr>
				<th><span data-tooltip="Actions" class="--tt-right">@svg('icon-ellipsis-vertical')</span></th>
				<th>Code</th>
				<th>Label</th>
				<th>Bundle</th>
				<th>Variants</th>
			</tr>
			</thead>
			<tbody>
			@foreach( $productTypes as $productType )
				@php($uid = 'edit_' . $productType->id)
				<tr>
					<td>
						<x-modal
							uid="{{ $uid }}"
							title="Edit Product Type"
							submit="Update"
							:action="route('product-types.update', $productType)"
						>
							<x-slot:trigger
								data-tooltip="Edit Product Type"
								class="--tt-right"
							>@svg('icon-pencil')</x-slot:trigger>
							<table class="m_table">
								<tr>
									<th><label for="{{ $uid }}_code">Code:</label></th>
									<td>
										<input
											class="input"
											name="code"
											value="{{ $productType->code }}"
											maxlength="16"
											id="{{ $uid }}_code"
											style="width:5rem"
										/>
									</td>
								</tr>
								<tr>
									<th><label for="{{ $uid }}_label">Label</label></th>
									<td>
										<input
											class="input"
											form="form_{{ $productType->id }}"
											name="label"
											value="{{ $productType->label }}"
											maxlength="255"
											id="{{ $uid }}_label"
											style="width:25rem"
										/>
									</td>
								</tr>
								<tr>
									<th><label for="{{ $uid }}_is_bundle">Is Bundle?</label></th>
									<td>
										<input
											type="checkbox"
											name="is_bundle"
											@checked($productType->is_bundle)
											id="{{ $uid }}_is_bundle"
										/>
									</td>
								</tr>
								<tr
									data-prop-switcher="hide-unless-checked,#{{ $uid }} input[name=is_bundle]"
									@style(['display:none'=>!$productType->is_bundle])
								>
									<th><label for="{{ $uid }}_products">Products</label></th>
									<td>
										<x-form.select
											name="child_product_type_ids[]"
											:options="$child_product_type_options"
											:default="$productType->childProductTypes()->pluck('id')->toArray()"
											style="width:20rem"
											multiple
											id="{{ $uid }}_products"
										/>
									</td>
								</tr>
								<tr>
									<th>Variants</th>
									<td>
										<div class="l_rows --sm">
											<label>
												<input
													type="text"
													class="input"
													name="variants[label]"
													form="form_{{ $productType->id }}"
													placeholder="Label"
													value="{{ $productType->variants['label'] ?? null }}"
												/>
											</label>
											<div class="l_cols --sm">
												<label>
													<input
														type="text"
														class="input"
														name="variants[options][0][key]"
														placeholder="key"
														style="width:5rem;"
													/>
												</label>
												<label>
													<input
														type="text"
														class="input"
														name="variants[options][0][value]"
														placeholder="value"
														style="width:5rem;"
													/>
												</label>
											</div>
											<code>{{ json_encode($productType->variants, JSON_PRETTY_PRINT) }}</code>
										</div>
									</td>
								</tr>
							</table>
						</x-modal>
					</td>
					<td>{{ $productType->code }}</td>
					<td>{{ $productType->label }}</td>
					<td>
						@if( $productType->is_bundle)
							{{  $productType->childProductTypes()->pluck('id')->implode(fn(string $id) => $child_product_type_options[$id], ' + ') }}
						@endif
					</td>
					<td>
						<code>{{ json_encode($productType->variants, JSON_PRETTY_PRINT) }}</code>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
</x-layouts.app>
