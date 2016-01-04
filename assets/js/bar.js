(function() {
	'use strict';

	var bodyEl = document.body;
	var $ = window.jQuery;

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
		var iconEl = document.createElement('span');
		var responseEl = wrapperEl.querySelector('.mctb-response');
		var visible = false;
		var originalBodyPadding = 0,
			barHeight = 0,
			bodyPadding = 0;

		var isBottomBar = ( config.position === 'bottom' );
		var hasjQuery = ( typeof($) === "function" );

		// Functions

		function init() {

			// remove "no_js" field
			var noJsField = barEl.querySelector('input[name="_mctb_no_js"]');
			noJsField.parentElement.removeChild(noJsField);

			// calculate real bar height
			var origBarPosition = barEl.style.position;
			barEl.style.display = 'block';
			barEl.style.position = 'relative';
			barHeight = barEl.clientHeight;

			// save original bodyPadding
			if( isBottomBar ) {
				wrapperEl.insertBefore( iconEl, barEl );
				originalBodyPadding = ( parseInt( bodyEl.style.paddingBottom )  || 0 );
			} else {
				wrapperEl.insertBefore( iconEl, barEl.nextElementSibling );
				originalBodyPadding = ( parseInt( bodyEl.style.paddingTop )  || 0 );
			}

			// get real bar hegiht (if it were shown)
			bodyPadding = ( originalBodyPadding + barHeight ) + "px";

			// fade response 4 seconds after showing bar
			window.setTimeout(fadeResponse, 4000);

			// fix response height
			if( responseEl ) {
				responseEl.style.lineHeight = barHeight + "px";
			}

			// Configure icon
			iconEl.setAttribute('class', 'mctb-close');
			iconEl.innerHTML = config.icons.show;
			addEvent(iconEl, 'click', toggle);

			// would the close icon fit inside the bar?
			var elementsWidth = 0;
			for( var i=0; i<barEl.firstElementChild.children.length; i++ ) {
				elementsWidth+= barEl.firstElementChild.children[i].clientWidth;
			}
			if( elementsWidth + iconEl.clientWidth + 200 < barEl.clientWidth ) {
				wrapperEl.className += ' mctb-icon-inside-bar';

				// since icon is now absolutely positioned, we need to set a min height
				wrapperEl.style.minHeight = iconEl.clientHeight + "px";
			}

			// hide bar again, we're done measuring
			barEl.style.display = 'none';
			barEl.style.position = origBarPosition;

			// Show the bar straight away?
			if( readCookie( "mctb_bar_hidden" ) != 1 ) {
				show()
			}
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

			// use animation if jQuery is loaded
			if( manual && hasjQuery ){
				$(barEl).slideDown(300);

				// animate body padding
				if( isBottomBar ) {
					$(bodyEl).animate({'padding-bottom': bodyPadding});
				} else {
					$(bodyEl).animate({'padding-top': bodyPadding});
				}
			} else {
				// Add bar height to <body> padding
				barEl.style.display = 'block';
				if( isBottomBar ) {
					bodyEl.style.paddingBottom = bodyPadding;
				} else {
					bodyEl.style.paddingTop = bodyPadding;
				}
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
			if( ! visible ) {
				return false;
			}

			if( manual ) {
				createCookie( "mctb_bar_hidden", 1, config.cookieLength );
			}

			if( manual && hasjQuery ){
				$(barEl).slideUp(300);

				// animate body padding
				if(isBottomBar ) {
					$(bodyEl).animate({ 'padding-bottom': originalBodyPadding + "px" });
				} else {
					$(bodyEl).animate({ 'padding-top': originalBodyPadding +"px" });
				}
			} else {
				barEl.style.display = 'none';
				if( isBottomBar ) {
					document.body.style.paddingBottom = originalBodyPadding + "px";
				} else {
					document.body.style.paddingTop = originalBodyPadding + "px";
				}
			}

			visible = false;
			iconEl.innerHTML = config.icons.show;

			return true;
		}

		/**
		 * Fade out the response message
		 */
		function fadeResponse() {
			if( responseEl ) {
				// fade out element
				fadeOut(responseEl);

				// auto-dismiss bar if we're good!
				if( config.is_submitted && config.is_success ) {
					window.setTimeout( function() { hide( true );}, 1000 );
				}
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
