(function($) {

	var Bar = function( $wrapper, config ) {

		// Vars & State
		var $bar = $wrapper.find('.mctp-bar');
		var $icon = $wrapper.find('.mctp-close');
		var visible = false;

		// Functions
		function toggle() {

			// do nothing if bar is undergoing animation
			if( $bar.is(':animated') ) {
				return;
			}

			$bar.slideToggle();

			if( visible ) {
				// hiding bar
				createCookie( "mctb_bar_hidden", 1, config.cookieLength );
				$icon.html(config.icons.show);
			} else {
				// showing bar
				eraseCookie( 'mctb_bar_hidden' );
				$icon.html(config.icons.hide);
			}

			visible = !visible;
		}

		// Code to run upon object instantiation

		// Move element to begin of <body>
		$wrapper.insertBefore( document.body.firstChild );

		// Listen to `click` events on the icon
		$icon.click( toggle );

		// Return values
		return {
			$element: $wrapper,
			toggle: toggle
		}

	};

	// Init Bar on window.load
	$(window).load( function() {
		window.MailChimpTopBar = new Bar( $(document.getElementById('mailchimp-top-bar') ), mctb );
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
