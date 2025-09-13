@props([
	/** @var \App\Models\ProductType */
	'productType' => null,
	'uid' => 'create_' . uniqid(),
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
				required
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
				required
			/>
		</td>
	</tr>
</table>

<h3 style="font-weight:bold; text-align: center; font-size: 1rem">Variants</h3>

<livewire:product-types.variant-definition
	:$productType
	wire:key="{{ $productType?->id }}_variant_definition"
/>
