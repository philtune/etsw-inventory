<div>
	@if( !$has_variants )
		<button
			type="button"
			class="u_btn --sm --bare"
			wire:click="add"
		>@svg('icon-plus') Add Variants
		</button>
	@else
		<div class="l_rows --sm">
			<label>
				<input
					type="text"
					class="input"
					placeholder="Label"
					name="variants[label]"
					wire:model.live.debounce="label"
					maxlength="24"
				/>
			</label>
			Options:
			@foreach( $options as $i => $option )
				<div class="l_cols --sm">
					<label>
						<input
							type="text"
							class="input"
							name="variants[options][{{ $i }}][key]"
							placeholder="key"
							wire:model.live.debounce="options.{{ $i }}.key"
							maxlength="16"
							style="width:4rem;"
						/>
					</label>:
					<label>
						<input
							type="text"
							class="input"
							name="variants[options][{{ $i }}][value]"
							placeholder="value"
							wire:model.live.debounce="options.{{ $i }}.value"
							maxlength="24"
							style="width:4rem;"
						/>
					</label>
					<button
						type="button"
						class="u_btn --danger --sm --bare"
						wire:click="removeOption('{{ $i }}')"
					>@svg('icon-xmark')</button>
				</div>
			@endforeach
			<div>
				<button
					type="button"
					class="u_btn --sm --bare"
					wire:click="addOption"
				>@svg('icon-plus') Add Option
				</button>
			</div>
			@if( count($options) )
				<label>
					Default:
					<span style="display:inline-block"><x-form.select
							name="variants[default]"
							:options="$option_options"
							:default="$default"
							:initial="false"
						/></span>
				</label>
			@endif
		</div>
		<button
			type="button"
			class="u_btn --danger --sm --bare"
			wire:click="remove"
		>@svg('icon-xmark') Remove Variants
		</button>
	@endif
</div>
