(function($) {

	var bodyEl = document.body;
	var $body = $(bodyEl);

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
		var $bar = $(barEl);
		var $icon = $(iconEl);

		// Functions

		function init() {

			// add token field 1 second after initializign the bar
			window.setTimeout(addTokenField, 1000);

			// fade response 3 seconds after showing bar
			window.setTimeout(fadeResponse, 3000);

			// Show the bar straight away?
			if( readCookie( "mctb_bar_hidden" ) != 1 ) {
				show()
			}

			// Listen to `click` events on the icon
			$icon.click( toggle );
		}

		/**
		 * Show the bar
		 *
		 * @returns {boolean}
		 */
		function show( manual ) {

			if( visible || $bar.is( ':animated' ) ) {
				return false;
			}

			if( manual ) {
				// Add bar height to <body> padding
				var bodyPadding = ( ( parseInt( bodyEl.style.paddingTop )  || 0 ) + $bar.outerHeight() ) + "px";
				$body.animate({ 'padding-top': bodyPadding });
				$bar.slideDown();
				eraseCookie( 'mctb_bar_hidden' );
			} else {
				// Add bar height to <body> padding
				barEl.style.display = 'block';
				bodyEl.style.paddingTop = ( ( parseInt( bodyEl.style.paddingTop )  || 0 ) + $bar.outerHeight() ) + "px";
			}

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
			if( ! visible || $bar.is( ':animated' ) ) {
				return false;
			}

			if( manual ) {
				$bar.slideUp();
				$body.animate({ 'padding-top': 0 });
				createCookie( "mctb_bar_hidden", 1, config.cookieLength );
			} else {
				barEl.style.display = 'none';
				document.body.style.paddingTop = 0;
			}

			visible = false;
			iconEl.innerHTML = config.icons.show;

			return true;
		}

		/**
		 * Adds a timestamp field to prevent bots from submitting instantly
		 */
		function addTokenField() {

			var pathname = window.location.pathname;
			var token = (pathname.length * 11).toString() + (pathname.split('/').length * 111).toString();

			var tokenEl = document.createElement('input');
			tokenEl.setAttribute('name', '_mctb_token');
			tokenEl.setAttribute('type', 'hidden');
			tokenEl.setAttribute('value', token );
			barEl.querySelector('form').appendChild(tokenEl);
		}

		/**
		 * Fade out the response message
		 */
		function fadeResponse() {
			var responseEl = wrapperEl.querySelector('.mctb-response');
			if( responseEl ) {
				 $(responseEl).fadeOut();
			}
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
	$(document).ready( function() {
		window.MailChimpTopBar = new Bar( document.getElementById('mailchimp-top-bar'), window.mctb );
	});

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

})(window.jQuery);
