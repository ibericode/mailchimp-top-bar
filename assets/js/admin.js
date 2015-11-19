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
	var $doubleOptinRow = $('.double-optin-options');
	var $sendWelcomeRow = $('.send-welcome-options');

	/**
	 * Only show sticky bar option if position = top (bottom is always sticky)
	 */
	$( document.getElementById('select-bar-position' )).change(function() {
		$('.sticky-bar-options').toggle($(this).prop('checked'));
	});

	// toggle "Send Welcome Email" row based on double opt-in value
	// todo: use data-showif from core plugin
	$doubleOptinRow.find(':input').change(function() {
		var doubleOptinEnabled = $(this).val() == 1 || $(this).data('inherited-value') == 1;
		$sendWelcomeRow.toggle(!doubleOptinEnabled);
	});

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
		// use core tab functionality
		if(window.mc4wp && window.mc4wp.tabs) { return; }

		var $tabs = $context.find('.tab');
		var $tabNav = $context.find('.nav-tab');
		var $refererField = $context.find('input[name="_wp_http_referer"]');

		function switchTab() {

			var urlParams = parseQuery( this.href );
			if( typeof(urlParams.tab) === "undefined" ) {
				return;
			}

			// hide all tabs & remove active class
			$tabs.hide();
			$tabNav.removeClass('nav-tab-active');

			// add `nav-tab-active` to this tab
			$(this).addClass('nav-tab-active');

			// show target tab
			var targetId = "tab-" + urlParams.tab;
			document.getElementById(targetId).style.display = 'block';

			// update hash
			if( history.pushState ) {
				history.pushState( '', '', this.href );
			}

			// update referer field
			$refererField.val(this.href);

			// prevent page jump
			return false;
		}

		// add tab listener
		$tabNav.click(switchTab);

	})();

	// init colorpickers
	$context.find('.color').wpColorPicker();

	// if a list changes, check which fields are required
	$selectList.change( checkRequiredFields );
	$enableBar.change( checkIfBarIsEnabled );

	// trigger change event to check for required fields right away
	$selectList.change();
	$enableBar.change();

	function parseQuery(qstr) {
		var query = {};
		var a = qstr.split('&');
		for (var i in a) {
			var b = a[i].split('=');
			query[decodeURIComponent(b[0])] = decodeURIComponent(b[1]);
		}

		return query;
	}

})(window.jQuery);