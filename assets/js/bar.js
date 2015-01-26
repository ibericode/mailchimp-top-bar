(function($) {

	/**
	 * Creates a new Top Bar from an element
	 *
	 * @param $wrapper
	 * @param config
	 * @returns {{$element: *, toggle: toggle, show: show, hide: hide}}
	 * @constructor
	 */
	var Bar = function( $wrapper, config ) {

		// Vars & State
		var $bar = $wrapper.find('.mctp-bar');
		var $icon = $wrapper.find('.mctp-close');
		var visible = false;

		// Functions

		/**
		 * Show the bar
		 *
		 * @returns {boolean}
		 */
		function show() {
			if( $bar.is( ':animated' ) || visible ) {
				return false;
			}

			$bar.slideDown();
			eraseCookie( 'mctb_bar_hidden' );
			$icon.html(config.icons.hide);
			visible = true;

			return true;
		}

		/**
		 * Hide the bar
		 *
		 * @returns {boolean}
		 */
		function hide() {
			if( $bar.is( ':animated' ) || ! visible ) {
				return false;
			}

			$bar.slideUp();
			visible = false;
			createCookie( "mctb_bar_hidden", 1, config.cookieLength );
			$icon.html(config.icons.show);

			return true;
		}

		/**
		 * Toggle visibility of the bar
		 *
		 * @returns {boolean}
		 */
		function toggle() {
			return visible ? hide() : show();
		}

		// Code to run upon object instantiation

		// Move element to begin of <body>
		$wrapper.insertBefore( document.body.firstChild );

		// Listen to `click` events on the icon
		$icon.click( toggle );

		// Show the bar straight away?
		if( readCookie( "mctb_bar_hidden" ) != 1 ) {
			$bar.show();
			visible = true;
			$icon.html( config.icons.hide );
		}

		// Return values
		return {
			$element: $wrapper,
			toggle: toggle,
			show: show,
			hide: hide
		}

	};

	// Init Bar on window.load
	$(window).load( function() {
		window.MailChimpTopBar = new Bar( $(document.getElementById('mailchimp-top-bar') ), window.mctb );
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
