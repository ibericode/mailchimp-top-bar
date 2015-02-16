(function($) {

	var $body = $("body");

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
		function show( manual ) {
			if( $bar.is( ':animated' ) || visible ) {
				return false;
			}

			if( manual ) {
				// Add bar height to <body> padding
				var bodyPadding = parseFloat( $("body").css('padding-top') ) + $bar.outerHeight();
				$body.animate({ 'padding-top': bodyPadding });
				$bar.slideDown();
				eraseCookie( 'mctb_bar_hidden' );
			} else {
				// Add bar height to <body> padding
				$body.css( 'padding-top', parseFloat( $("body").css('padding-top') ) + $bar.outerHeight() );
				$bar.show();
			}

			$icon.html(config.icons.hide);
			visible = true;

			return true;
		}

		/**
		 * Hide the bar
		 *
		 * @returns {boolean}
		 */
		function hide(manual) {
			if( $bar.is( ':animated' ) || ! visible ) {
				return false;
			}

			if( manual ) {
				$bar.slideUp();
				$body.animate({ 'padding-top': 0 });
				createCookie( "mctb_bar_hidden", 1, config.cookieLength );
			} else {
				$bar.hide();
				$body.css('padding-top', 0);
			}

			visible = false;
			$icon.html(config.icons.show);

			return true;
		}

		function addTimestampField() {
			var timestamp = document.createElement('input');
			timestamp.setAttribute('name', '_mctb_timestamp');
			timestamp.setAttribute('type', 'hidden');
			timestamp.setAttribute('value', Math.round(new Date().getTime() / 1000));
			$bar.find('form').get(0).appendChild( timestamp );
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
