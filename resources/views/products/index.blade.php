<x-layouts.app>
	<x-slot:toolbar>
		<form action="{{ route('products.store') }}" method="POST" class="l_cols --sm">
			@csrf
			<x-form.select
				name="scent_id"
				:options="$scent_options"
				initial="-- SCENT --"
				required
				style="width:14rem"
			/>
			<x-form.select
				name="product_type_id"
				:options="$product_type_options"
				initial="-- PRODUCT TYPE --"
				required
				style="width:14rem"
			/>
			<label>
				Label:
				<input
					class="input"
					name="label"
					maxlength="255"
					style="width:12rem"
				/>
			</label>
			<button
				type="submit"
				class="u_btn"
			>Add Product
			</button>
		</form>
	</x-slot:toolbar>
	<livewire:products.index-table
		:$product_type_options
		:$scent_options
	/>
</x-layouts.app>
