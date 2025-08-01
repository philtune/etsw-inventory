<x-layouts.app>
	<x-slot:toolbar>
		<x-modal-create
			model-name="Product Type"
			:action="route('product-types.store')"
		>
			@include('product-types.form-inputs')
		</x-modal-create>
	</x-slot:toolbar>
	<livewire:product-types.index-table :$child_product_type_options/>
</x-layouts.app>
