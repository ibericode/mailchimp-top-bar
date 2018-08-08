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
			
			for( var j=0; j<list.merge_fields.length; j++ ) {
				var field = list.merge_fields[j];
				if( field.tag !== 'EMAIL' && field.required ) {
					msgRequiresFields.style.display = '';
					return;
				}
			}
		}
	}

	// init colorpickers
	$context.find('.mc4wp-color').wpColorPicker();

	// if a list changes, check which fields are required
	$selectList.change( maybeShowRequiredFieldsNotice );

	// check right away
	maybeShowRequiredFieldsNotice();

})(window.jQuery);