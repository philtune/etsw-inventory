<tr>
	<td>
		<x-form.select
			:options="$product_options"
			wire:model.live="product_id"
			:default="$wholesaleOrderProduct->product_id"
			style="width:18rem"
		/>
	</td>
	<td><small>{!! collect($wholesaleOrderProduct->variation)->implode(fn($val, $key) => "<strong>$key:</strong> $val", ',') !!}</small></td>
	<td>
		<input
			type="number"
			class="input"
			wire:model.live="quantity"
			min="0"
		/>
	</td>
	<td>
		<div style="display:flex;align-items:center;gap:0.25rem">
			$<input
				type="text"
				class="input text-right"
				wire:model.live="price_per_unit"
				min="0"
				style="width:5rem"
			/>
		</div>
	</td>
	<td>
		<div style="display:flex;align-items:center;gap:0.25rem">
			$<input
				type="text"
				class="input text-right"
				wire:model.live="total_adjustment"
				min="0"
				style="width:5rem"
			/>
		</div>
	</td>
	<td class="text-right">${{ number_format($quantity * $price_per_unit + $total_adjustment, 2) }}</td>
	<td>{{ $wholesaleOrderProduct->notes }}</td>
</tr>
