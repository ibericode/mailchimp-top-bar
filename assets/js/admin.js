(function($) {
	'use strict';

	/*
	 * Variables
	 */
	var $context = $( document.getElementById( 'mc4wp-admin' ) );
	var $selectList = $( document.getElementById( 'select-mailchimp-list') );
	var msgRequiresFields = document.getElementById('message-list-requires-fields');

	/*
	 * Functions
	 */
	function maybeShowRequiredFieldsNotice() {

		// hide message
		msgRequiresFields.style.display = 'none';

		var lists = mc4wp.settings.getSelectedLists();
		for( var i=0; i<lists.length; i++) {
			var list = lists[i];

			for( var j=0; j<list.merge_vars.length; j++ ) {
				var merge_var = list.merge_vars[j];
				if( merge_var.tag !== 'EMAIL' && merge_var.required ) {
					msgRequiresFields.style.display = '';
					return;
				}
			}
		}
	}

	// init colorpickers
	$context.find('.color').wpColorPicker();

	// if a list changes, check which fields are required
	$selectList.change( maybeShowRequiredFieldsNotice );

	// check right away
	maybeShowRequiredFieldsNotice();

})(window.jQuery);