@props([
	/** @var \App\Models\ProductType */
	'productType' => null,
	'uid' => 'id_' . uniqid(),
])
<table class="m_table">
	<tr>
		<th><label for="{{ $uid }}_label">Label</label></th>
		<td>
			<input
				class="input"
				name="label"
				value="{{ $productType?->label }}"
				maxlength="255"
				id="{{ $uid }}_label"
				style="width:25rem"
			/>
		</td>
	</tr>
	<tr>
		<th><label for="{{ $uid }}_code">Code:</label></th>
		<td>
			<input
				class="input"
				name="code"
				value="{{ $productType?->code }}"
				maxlength="16"
				id="{{ $uid }}_code"
				style="width:5rem"
			/>
		</td>
	</tr>
	<tr>
		<th><label for="{{ $uid }}_is_bundle">Is Bundle?</label></th>
		<td>
			<div class="l_cols">
				<input
					type="checkbox"
					name="is_bundle"
					@checked($productType?->is_bundle)
					id="{{ $uid }}_is_bundle"
				/>
				<div
					data-prop-switcher="hide-unless-checked,#{{ $uid }} input[name=is_bundle]"
					@style(['display:none'=>!$productType?->is_bundle])
				>
					Products:
					<x-form.select
						name="child_product_type_ids[]"
						:options="$child_product_type_options"
						:default="$productType?->childProductTypes()->pluck('id')->toArray()"
						style="width:20rem"
						multiple
						id="{{ $uid }}_products"
					/>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<th>Variants</th>
		<td>
			<livewire:product-types.variant-definition
				:variants="$productType?->variants"
				wire:key="{{ $productType?->id }}"
			/>
		</td>
	</tr>
</table>
