@props([
	'uid' => 'id_' . uniqid(),
	/** @var \App\Models\Product */
	'product' => null,
])
<table class="m_table">
	<tr>
		<th class="w-1px"><label for="{{ $uid }}_label">Label</label></th>
		<td>
			<input
				class="input"
				name="label"
				value="{{ $product?->label }}"
				maxlength="255"
				id="{{ $uid }}_label"
				required
			/>
		</td>
	</tr>
	<tr>
		<th><label class="nowrap" for="{{ $uid }}_is_bundle">Is Bundle?</label></th>
		<td>
			<input
				type="checkbox"
				name="is_bundle"
				@checked($product?->is_bundle)
				id="{{ $uid }}_is_bundle"
			/>
		</td>
	</tr>
	<tr
		data-prop-switcher="hide-unless-checked,#{{ $uid }}_is_bundle"
		@style(['display:none'=>!($product?->is_bundle ?? false)])
	>
		<th><label for="{{ $uid }}_child_product_ids">Products</label></th>
		<td>
			<x-form.select
				name="child_product_ids[]"
				:options="$child_product_options"
				:default="$product?->bundleItems->map(fn(\App\Models\ProductBundleItem $productBundleItem) => $productBundleItem->child_product_id . '|' . $productBundleItem->productTypeVariant?->id)->toArray()"
				style="width:24rem"
				multiple
				id="{{ $uid }}_child_product_ids"
			/>
		</td>
	</tr>
	<tr
		data-prop-switcher="hide-if-checked,#{{ $uid }}_is_bundle"
		@style(['display:none'=>$product?->is_bundle])
	>
		<th><label for="{{ $uid }}_product_type_id">Type</label></th>
		<td>
			<x-form.select
				name="product_type_id"
				:options="$product_type_options"
				:default="$product?->product_type_id"
				initial="-- PRODUCT TYPE --"
				id="{{ $uid }}_product_type_id"
			/>
		</td>
	</tr>
	<tr
		data-prop-switcher="hide-if-checked,#{{ $uid }}_is_bundle"
		@style(['display:none'=>$product?->is_bundle])
	>
		<th><label for="{{ $uid }}_scent_id">Scent</label></th>
		<td>
			<x-form.select
				name="scent_id"
				:options="$scent_options"
				:default="$product?->scent_id"
				initial="-- SCENT --"
				id="{{ $uid }}_scent_id"
			/>
		</td>
	</tr>
</table>
