<?php

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

/**
 * Deactivates the plugin
 *
 * @return bool
 */
function mailchimp_top_bar_deactivate_self() {

	if( ! current_user_can( 'activate_plugins' ) ) {
		return false;
	}

	// deactivate self
	deactivate_plugins( plugin_basename( MAILCHIMP_TOP_BAR_FILE ) );

	// get rid of "Plugin activated" notice
	if( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}

	// show notice to user
	add_action( 'admin_notices', 'mailchimp_top_bar_php_requirement_notice' );

	return true;
}

/**
 * Outputs a notice telling the user that the plugin deactivated itself
 */
function mailchimp_top_bar_php_requirement_notice() {

	// load translations
	load_plugin_textdomain( 'mailchimp-top-bar', false, 'mailchimp-top-bar/languages' );

	?>
	<div class="updated">
		<p><?php _e( 'MailChimp Top Bar did not activate because it requires your server to run PHP 5.3 or higher.', 'mailchimp-top-bar' ); ?></p>
	</div>
	<?php
}

// Hook into `admin_init`
add_action( 'admin_init', 'mailchimp_top_bar_deactivate_self' );
