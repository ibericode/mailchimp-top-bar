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
		var barEl = wrapperEl.querySelector('.mctp-bar');
		var iconEl = wrapperEl.querySelector('.mctp-close');
		var visible = false;
		var $bar = $(barEl);
		var $icon = $(iconEl);

		// Functions

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

		function addTimestampField() {
			var timestamp = document.createElement('input');
			timestamp.setAttribute('name', '_mctb_timestamp');
			timestamp.setAttribute('type', 'hidden');
			timestamp.setAttribute('value', currentTimeInSeconds);
			barEl.querySelector('form').appendChild(timestamp);
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

		// add timestamp field
		addTimestampField();

		// Show the bar straight away?
		if( readCookie( "mctb_bar_hidden" ) != 1 ) {
			show()
		}


		// Listen to `click` events on the icon
		$icon.click( toggle );

		// Return values
		return {
			element: wrapperEl,
			toggle: toggle,
			show: show,
			hide: hide
		}

	};

	// Init Bar on window.load
	var currentTimeInSeconds = Math.floor(new Date().getTime() / 1000);
	$(window).load( function() {
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
