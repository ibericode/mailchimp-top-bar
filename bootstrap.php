<?php

namespace MailChimp\TopBar;

defined( 'ABSPATH' ) or exit;

require_once __DIR__ . '/src/Options.php';
$options = new Options( 'mailchimp_top_bar' );

if( ! is_admin() ) {
	// frontend code
    require_once __DIR__ . '/src/Tracker.php';
    require_once __DIR__ . '/src/Bar.php';
	$bar = new Bar( $options );
	$bar->add_hooks();
} elseif( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	// ajax code

} else {
	// admin code
    require_once __DIR__ . '/src/Admin/Manager.php';
    $admin = new Admin\Manager( $options );
	$admin->add_hooks();
}
