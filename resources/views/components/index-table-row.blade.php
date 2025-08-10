@props([
    /** @var Model */
    'model',
    'modelName',
    'stack_id',
    'loop',
    'editModal' => null,
    'editWireModal' => null,
    'canRestore' => true,
    'secondaryActions' => null,
])

@php($edit_uid = 'edit_' . $model->id)
<tr
	wire:key="{{ $model->id }}"
	onclick="openModal('{{ $edit_uid }}')"
	{{ $attributes->style(['cursor:pointer']) }}
>
	{!! $slot !!}
	<x-td.actions :$loop>
		@if( !$canRestore || !$model->deleted_at )
			@if( $editModal )
				<x-modal.edit
					:$modelName
					:action="$editModal->attributes->get('action')"
					:uid="$edit_uid"
					:push-to="$stack_id"
				>
					{!! $editModal !!}
				</x-modal.edit>
			@elseif( $editWireModal )
				<x-modal.wire.edit
					:id="$model->id"
					:$modelName
					:uid="$edit_uid"
					:push-to="$stack_id"
				>
					{!! $editWireModal !!}
				</x-modal.wire.edit>
			@endif
		@else
			<button
				type="button"
				class="u_btn --success --bare"
				wire:click="restore('{{ $model->id }}')"
			>
				@svg('icon-rotate-left') Restore {{ $modelName }}
			</button>
		@endif
		{!! $secondaryActions !!}
		@if( !$canRestore || !$model->deleted_at )
			<x-modal.wire.delete
				wire:click="delete('{{ $model->id }}')"
				:$modelName
				:$canRestore
				:push-to="$stack_id"
			/>
		@else
			<x-modal.wire.force-delete
				:$modelName
				:push-to="$stack_id"
				wire:click="forceDelete('{{ $model->id }}')"
			/>
		@endif
	</x-td.actions>
</tr>
