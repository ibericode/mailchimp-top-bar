(function($) {

	// vars
	var $barWrapper = $(document.getElementById('mailchimp-top-bar'));
	var $bar = $barWrapper.find('.mctp-bar');
	var $close = $barWrapper.find('.mctp-close');

	// Bar functions & state
	var bar = {};
	bar.visible = false;

	/**
	 * Initializes bar
	 *
	 * - Moves bar element through DOM
	 * - Checks if cookie is set
	 */
	bar.init = function() {

		$barWrapper.insertBefore( document.body.firstChild );

		// if cookie is set, hide the bar.
		if( readCookie("mctb_bar_hidden") != 1 ) {
			$close.html('&#x25B2;');
			$bar.show();
			bar.visible = true;
		}

	};

	/**
	 * Toggle visibility of the bar
	 */
	bar.toggle = function( ) {

		// do nothing if bar is undergoing animation
		if( $bar.is(':animated') ) {
			return;
		}

		$bar.slideToggle();

		if( bar.visible ) {
			// hiding bar
			createCookie( "mctb_bar_hidden", 1, mctb.cookieLength );
			$close.html('&#x25BC;');
		} else {
			// showing bar
			eraseCookie( 'mctb_bar_hidden' );
			$close.html('&#x25B2;');
		}

		bar.visible = !bar.visible;
	};

	// event listeners
	$(window).load( bar.init );
	$close.click( bar.toggle );

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
