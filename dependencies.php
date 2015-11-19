<?php

// check for mailchimp for wordpress pro 2.x
if( defined( 'MC4WP_VERSION' ) && version_compare( MC4WP_VERSION, '2.5.6', '>=' ) ) {
	return true;
}

// check for mailchimp for wordpress lite 2.x
if( defined( 'MC4WP_LITE_VERSION' ) && version_compare( MC4WP_LITE_VERSION, '2.2.4', '>=' ) ) {
	return true;
}

// check for MailChimp for WordPress core 3.x
if( defined( 'MC4WP_VERSION' ) && version_compare( MC4WP_VERSION, '3.0', '>=' ) ) {
	return true;
}

// Show notice to user
add_action( 'admin_notices',  function() {
	?>
	<div class="updated">
		<p><?php printf( __( 'Please install or update <a href="%s">%s</a> in order to use %s.', 'mailchimp-top-bar' ), 'https://wordpress.org/plugins/mailchimp-for-wp/', 'MailChimp for WordPress', 'MailChimp Top Bar' ); ?></p>
	</div>
<?php
} );

// tell plugin not to proceed
return false;