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


	// init colorpickers
	$context.find('.color').wpColorPicker();

	// if a list changes, check which fields are required
	$selectList.change( checkRequiredFields );
	$enableBar.change( checkIfBarIsEnabled );

	// trigger change event to check for required fields right away
	$selectList.change();
	$enableBar.change();

})(window.jQuery);