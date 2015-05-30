(function() {

	var bodyEl = document.body;

	/**
	 * Creates a new Top Bar from an element
	 *
	 * @param wrapperEl
	 * @param config
	 * @returns {{$element: *, toggle: toggle, show: show, hide: hide}}
	 * @constructor
	 */
	var Bar = function( wrapperEl, config ) {

		// Vars & State
		var barEl = wrapperEl.querySelector('.mctb-bar');
		var iconEl = wrapperEl.querySelector('.mctb-close');
		var visible = false;

		// Functions

		function init() {

			// remove "no_js" field
			var noJsField = barEl.querySelector('input[name="_mctb_no_js"]');
			noJsField.parentElement.removeChild(noJsField);

			iconEl.style.display = 'block';

			// fade response 3 seconds after showing bar
			window.setTimeout(fadeResponse, 3000);

			// Show the bar straight away?
			if( readCookie( "mctb_bar_hidden" ) != 1 ) {
				show()
			}

			// Listen to `click` events on the icon
			addEvent(iconEl, 'click', toggle);
		}

		/**
		 * Show the bar
		 *
		 * @returns {boolean}
		 */
		function show( manual ) {

			if( visible ) {
				return false;
			}

			if( manual ) {
				eraseCookie( 'mctb_bar_hidden' );
			}

			// Add bar height to <body> padding
			barEl.style.display = 'block';
			bodyEl.style.paddingTop = ( ( parseInt( bodyEl.style.paddingTop )  || 0 ) + barEl.clientHeight ) + "px";
			iconEl.innerHTML = config.icons.hide;
			visible = true;

			return true;
		}

		/**
		 * Hide the bar
		 *
		 * @returns {boolean}
		 */
		function hide(manual) {
			if( ! visible ) {
				return false;
			}

			if( manual ) {
				createCookie( "mctb_bar_hidden", 1, config.cookieLength );
			}

			barEl.style.display = 'none';
			document.body.style.paddingTop = 0;
			visible = false;
			iconEl.innerHTML = config.icons.show;

			return true;
		}

		/**
		 * Fade out the response message
		 */
		function fadeResponse() {
			var responseEl = wrapperEl.querySelector('.mctb-response');
			fadeOut(responseEl);
		}

		/**
		 * Toggle visibility of the bar
		 *
		 * @returns {boolean}
		 */
		function toggle() {
			return visible ? hide(true) : show(true);
		}

		// Code to run upon object instantiation
		init();

		// Return values
		return {
			element: wrapperEl,
			toggle: toggle,
			show: show,
			hide: hide
		}

	};

	// Init bar
	ready( function() {
		window.MailChimpTopBar = new Bar( document.getElementById('mailchimp-top-bar'), window.mctb );
	});

	/**
	 * DOMContentLoaded (IE8 compatible)
	 *
	 * @param fn
	 */
	function ready(fn) {
		if (document.readyState != 'loading'){
			fn();
		} else if (document.addEventListener) {
			document.addEventListener('DOMContentLoaded', fn);
		} else {
			document.attachEvent('onreadystatechange', function() {
				if (document.readyState != 'loading')
					fn();
			});
		}
	}

	/**
	 * Add event (IE8 compatible)
	 *
	 * @param element
	 * @param eventName
	 * @param callback
	 */
	function addEvent(element, eventName, callback) {
		if (element.addEventListener) {
			return element.addEventListener(eventName, callback, false);
		} else if (element.attachEvent)  {
			return element.attachEvent('on' + eventName, callback);
		}
	}

	/**
	 * Fades out the given element
	 *
	 * @param element
	 */
	function fadeOut(element) {
		var opacity = 1;

		function fadeStep() {

			if (opacity <= 0.1){
				element.style.display = 'none';
				return false;
			}

			element.style.opacity = opacity;
			opacity -= opacity * 0.1;

			if( typeof( window.requestAnimationFrame ) === "function" ) {
				window.requestAnimationFrame(fadeStep);
			} else {
				window.setTimeout(fadeStep, 25);
			}

			return true;
		}

		fadeStep();
	}

	/**
	 * Creates a cookie
	 *
	 * @param name
	 * @param value
	 * @param days
	 */
	function createCookie(name, value, days) {
		var expires;

		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			expires = "; expires=" + date.toGMTString();
		} else {
			expires = "";
		}
		document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
	}

	/**
	 * Reads a cookie
	 *
	 * @param name
	 * @returns {*}
	 */
	function readCookie(name) {
		var nameEQ = encodeURIComponent(name) + "=";
		var ca = document.cookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
		}
		return null;
	}

	/**
	 * Erases a cookie
	 *
	 * @param name
	 */
	function eraseCookie(name) {
		createCookie(name, "", -1);
	}

})();
