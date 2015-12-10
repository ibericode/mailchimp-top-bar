<?php

// check for MailChimp for WordPress (version 3.0 or higher)
if( defined( 'MC4WP_VERSION' ) && version_compare( MC4WP_VERSION, '3.0', '>=' ) ) {
	return true;
}

// Show notice to user
add_action( 'admin_notices',  function() {

	// only show to user with caps
	if( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	add_thickbox();
	$url = network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=mailchimp-for-wp&TB_iframe=true&width=600&height=550' );
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php printf( __( 'Please install or update <a href="%s" class="thickbox">%s</a> in order to use %s.', 'mailchimp-top-bar' ), $url, '<strong>MailChimp for WordPress</strong> (version 3.0 or higher)', 'MailChimp Top Bar' ); ?></p>
	</div>
<?php
} );

// tell plugin not to proceed
return false;
