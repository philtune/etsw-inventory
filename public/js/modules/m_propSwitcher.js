export const m_propSwitcher = _me => {
	const orig_arg_str = _me.getAttribute('data-prop-switcher')
	if ( orig_arg_str ) {
		const rules = orig_arg_str.split('|')
		rules.forEach(rule => {
			let [directive, selector, ...values] = rule.split(',')
			directive = directive.trim().toLowerCase()
			values = values.map(value => value.trim())
			const [action, conditional, control] = directive.split('-')
			const doAction = should => {
				switch ( action ) {
					case 'show':
						_me.style.display = should ? '' : 'none'
						break
					case 'hide':
						_me.style.display = should ? 'none' : ''
						break
					case 'required':
						_me.required = should
						break
					case 'disabled':
						_me.disabled = should
						break
					case 'class':
						_me.classList.toggle(values[control === 'checked' ? 0 : 1], should)
				}
			}
			const triggers = document.querySelectorAll(selector.trim())
			if ( control === 'change' ) {
				triggers.forEach(_trigger => {
					_trigger.addEventListener('change', e => {
						let should
						switch ( conditional ) {
							case 'if':
								should = values.includes(e.target.value)
								break;
							case 'unless':
								should = !values.includes(e.target.value)
								break;
						}
						doAction(should)
					})
				})
			}
			if ( control === 'checked' ) {
				triggers.forEach(_trigger => {
					_trigger.addEventListener('change', e => {
						let should
						switch ( conditional ) {
							case 'if':
								should = e.target.checked
								break;
							case 'unless':
								should = !e.target.checked
								break;
						}
						doAction(should)
					})
				})
			}

		})
	}
}
