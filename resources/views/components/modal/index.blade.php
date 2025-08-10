@props([
    'title',
	'trigger' => null,
    'uid' => 'id_' . uniqid(),
    'pushTo' => 'below-body',
    'submitBtn',
])

@if( $trigger )
	<button
		type="button"
		{{ $trigger->attributes->class(['u_btn']) }}
		onclick="openModal('{{ $uid }}')"
	>{{ $trigger }}</button>
@endif
@push($pushTo)
	<div class="m_modal" tabindex="0" id="{{ $uid }}">
		<div class="m_modal-container">
			<button
				type="button"
				class="m_modal-container-background"
				onclick="closeModal('{{ $uid }}')"
				title="close"
			></button>
			<div class="m_modal-container-card">
				<div class="l_cols --split --lg">
					<h2 class="title">{{ $title }}</h2>
				</div>
				{{ $slot }}
				<div class="l_cols --split">
					<button
						type="button"
						class="u_btn --danger --bare"
						onclick="closeModal('{{ $uid }}')"
					>@svg('icon-xmark')Cancel
					</button>
					<button
						type="submit"
						{{ $submitBtn->attributes->class('u_btn') }}
					>{{ $submitBtn }}</button>
				</div>
			</div>
		</div>
	</div>
@endpush
@pushonce('below-body')
	<script>
		window.openModal = modal_id => {
			const _modal = document.getElementById(modal_id)
			_modal.classList.add('--active')
			document.body.style.overflow = 'hidden'
			_modal.querySelector('button').focus()
			document.addEventListener('keydown', e => {
				if ( e.key === 'Escape' ) {
					_modal.classList.remove('--active')
					document.body.style.overflow = ''
					_modal.querySelector('button').blur()
				}
			})
		}

		window.closeModal = modal_id => {
			const _modal = document.getElementById(modal_id)
			_modal.classList.remove('--active')
			document.body.style.overflow = ''
			_modal.querySelector('button').blur()
		}

		document.addEventListener('livewire:init', () => {
			Livewire.hook('morphed', () => {
				if ( !document.querySelector('.m_modal.--active') ) {
					document.body.style.overflow = ''
				}
			});
		});
	</script>
@endpushonce
