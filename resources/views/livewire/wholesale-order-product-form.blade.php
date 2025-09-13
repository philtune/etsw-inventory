<tr>
	<td class="nowrap">
		<button
			type="button"
			class="u_btn --muted --sm"
			wire:click="moveUp"
		>@svg('icon-arrow-up')</button>
		<button
			type="button"
			class="u_btn --muted --sm"
			wire:click="moveDown"
		>@svg('icon-arrow-down')</button>
		<button
			type="button"
			class="u_btn --danger --sm"
			wire:click="delete"
		>@svg('icon-trash-xmark')</button>
		<button
			type="button"
			class="u_btn --warning --sm"
			wire:click="copy"
		>@svg('icon-copy')</button>
	</td>
	<td>
		<x-form.select
			:options="$product_options"
			wire:model.live="product_id"
			:default="$wholesaleOrderProduct->product_id"
			style="width:18rem"
		/>
	</td>
	<td>
		@if( $variant_options )
			<div class="nowrap">
				<small><strong>{{ $wholesaleOrderProduct->product?->productType->variant_label }}:</strong></small>
				<select
					wire:model.change="variation"
					class="select --shy"
					style="width:6rem"
				>
					@foreach( $variant_options as $value => $label )
						<option value="{{ $value }}">{{ $label }}</option>
					@endforeach
				</select>
			</div>
		@endif
	</td>
	<td>
		<input
			type="text"
			inputmode="numeric"
			class="input"
			wire:model.live="quantity"
			onclick="this.select()"
			min="0"
			style="width:4rem"
		/>
	</td>
	<td>
		<div style="display:flex;align-items:center;gap:0.25rem">
			$<input
				type="text"
				class="input text-right"
				wire:model.blur="price_per_unit"
				onclick="this.select()"
				min="0"
				style="width:5rem"
			/>
		</div>
	</td>
	<td>
		@if( is_null($total_adjustment) )
			<a href="#" wire:click.prevent="$set('total_adjustment', '0.00')" class="nowrap">+/-@svg('icon-dollar-sign')</a>
		@else
			<div style="display:flex;align-items:center;gap:0.25rem">
				$<input
					type="text"
					class="input text-right"
					wire:model.blur="total_adjustment"
					onclick="this.select()"
					min="0"
					style="width:5rem"
				/>
			</div>
		@endif
	</td>
	<td class="text-right">${{ number_format($quantity * $price_per_unit + $total_adjustment, 2) }}</td>
	<td>
		@if( is_null($notes) )
			<a href="#" wire:click.prevent="$set('notes', '')">@svg('icon-notes')</a>
		@else
			<input
				type="text"
				class="input --shy"
				placeholder=""
				wire:model.blur="notes"
				onclick="this.select()"
				style="width:10rem"
			/>
		@endif
	</td>
</tr>
