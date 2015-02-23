(function($) {
	'use strict';

	/*
	 * Variables
	 */
	var $context = $( document.getElementById( 'mc4wp-admin' ) );
	var $selectList = $( document.getElementById( 'select-mailchimp-list') );
	var $enableBar = $( document.querySelectorAll( 'input[name="mailchimp_top_bar[enabled]"]' ) );
	var msgRequiresFields = document.getElementById('message-list-requires-fields');
	var msgBarIsDisabled = document.getElementById('message-bar-is-disabled');

	/*
	 * Functions
	 */
	function checkRequiredFields() {

		// hide message
		msgRequiresFields.style.display = 'none';

		var list = getMailChimpList(this.value);
		if (!list) {
			return;
		}

		// loop through merge vars to find required fields
		for (var i = 0; i < list.merge_vars.length; i++) {
			if (list.merge_vars[i].tag !== 'EMAIL' && list.merge_vars[i].req) {
				// show message
				msgRequiresFields.style.display = '';
				return;
			}
		}
	}

	function checkIfBarIsEnabled() {
		if( this.checked && this.value === "0" ) {
			msgBarIsDisabled.style.display = '';
		} else {
			msgBarIsDisabled.style.display = 'none';
		}
	}

	/**
	 * @param list_id
	 * @returns {*}
	 */
	function getMailChimpList( list_id ) {
		return mctb.lists[ list_id ] || null;
	}


	// Tabs
	(function() {

		var $tabs = $context.find('.tab');
		var $tabNav = $context.find('.nav-tab');

		function switchTab() {

			var link = this;

			// hide all tabs & remove active class
			$tabs.hide();
			$tabNav.removeClass('nav-tab-active');

			// add `nav-tab-active` to this tab
			$(link).addClass('nav-tab-active');

			// show target tab
			var targetId = link.getAttribute('href');
			var $target = $(targetId);
			$target.show();

			// update hash
			location.hash = "tab=" + targetId.substring(1);

			// prevent page jump
			return false;
		}

		function checkForTabHash() {
			if(window.location.hash && window.location.hash.substring(0,5) === '#tab=') {
				$tabNav.filter('a[href="#'+ window.location.hash.substring(5) +'"]').click();
			}
		}

		// hide all tabs, except first
		$tabs.not(':first').hide();

		// add tab listener
		$tabNav.click(switchTab);

		// listen to changes or check current state
		$(window).on('hashchange', checkForTabHash );
		checkForTabHash();

	})();

	// init colorpickers
	$context.find('.color').wpColorPicker();

	// if a list changes, check which fields are required
	$selectList.change( checkRequiredFields );
	$enableBar.change( checkIfBarIsEnabled );

	// trigger change event to check for required fields right away
	$selectList.change();
	$enableBar.change();

})(window.jQuery);