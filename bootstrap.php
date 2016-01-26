<?php

namespace MailChimp\TopBar;

defined( 'ABSPATH' ) or exit;

$options = new Options( 'mailchimp_top_bar' );

if( ! is_admin() ) {
	// frontend code
	$bar = new Bar( $options );
	$bar->add_hooks();
} elseif( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	// ajax code

} else {
	// admin code
	$admin = new Admin\Manager( $options );
	$admin->add_hooks();
}
