import {m_propSwitcher} from "./modules/m_propSwitcher.js";

const register = init => {
	document.addEventListener('DOMContentLoaded', () => {
		init()
		if ( typeof Livewire !== 'undefined' ) {
			Livewire.hook('morphed', () => setTimeout(init, 300))
		}
	})
}
window.bindDOMElem = (selectors, callback) => {
	register(() => {
		document.querySelectorAll(selectors).forEach(callback)
	})
}

;( function(input) {
	Object.entries(input).forEach(([key, callback]) => {
		bindDOMElem(`[data-${key}]`, callback)
	})
} )({
	'prop-switcher': m_propSwitcher,
})

window.toast = (message, flag = null, timeout = 5000) => {
	const _toast = document.createElement('div')
	_toast.classList.add('m_toast', 'u_alert')
	if ( flag ) {
		_toast.classList.add(flag)
	}
	_toast.innerHTML = ' ' + message
	const _icon = document.createElement('i')
	_icon.classList.add('fa', 'fa-exclamation-triangle')
	_toast.prepend(_icon)
	_toast.addEventListener('click', () => _toast.classList.add('--fadeOut'))
	_toast.style.cursor = 'pointer'
	document.querySelector('.m_toast__container').append(_toast)
	setTimeout(() => {
		_toast.classList.add('--fadeOut')
		setTimeout(() => _toast.remove(), 1000)
	}, timeout)
}
document.addEventListener('DOMContentLoaded', () => {
	if ( typeof Livewire !== 'undefined' ) {
		Livewire.on('toast', ([msg, flag = null, timeout = 5000]) => toast(msg, flag, timeout))
	}
})
