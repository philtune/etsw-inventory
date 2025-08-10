@props([
	'uid' => 'id_' . uniqid(),
	/** @var \App\Models\Product */
	'product' => null,
])
<table class="m_table">
	<tr>
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
	<tr>
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
	<tr>
		<th><label for="{{ $uid }}_label">Label</label></th>
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
		<th><label for="{{ $uid }}_can_stock">Stockable</label></th>
		<td>
			<input
				type="checkbox"
				name="can_stock"
				@checked($product?->can_stock)
				id="{{ $uid }}_can_stock"
			/>
		</td>
	</tr>
</table>
