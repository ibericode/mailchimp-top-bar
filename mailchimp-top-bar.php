<?php
/*
Plugin Name: MC4WP: Mailchimp Top Bar
Plugin URI: https://www.mc4wp.com/#utm_source=wp-plugin&utm_medium=mailchimp-top-bar&utm_campaign=plugins-page
Description: Adds a Mailchimp opt-in bar to the top of your site.
Version: 1.6.1
Author: ibericode
Author URI: https://ibericode.com/
Text Domain: mailchimp-top-bar
Domain Path: /languages
License: GPL v3
Requires PHP: 5.3

Mailchimp Top Bar
Copyright (C) 2015-2023, Danny van Kooten, hi@dannyvankooten.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

defined( 'ABSPATH' ) or exit;

add_action( 'plugins_loaded', function() {
	// check for MailChimp for WordPress (version 3.0 or higher)
	if ( !defined( 'MC4WP_VERSION' ) || version_compare( MC4WP_VERSION, '3.0', '<' ) ) {
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
		});
		return;
	}


	define('MAILCHIMP_TOP_BAR_FILE', __FILE__);
	define('MAILCHIMP_TOP_BAR_DIR', __DIR__);
	define('MAILCHIMP_TOP_BAR_VERSION', '1.6.1');

	require __DIR__ . '/src/functions.php';

	if( ! is_admin() ) {
		// frontend code
		require __DIR__ . '/src/Bar.php';
		$bar = new MailChimp\TopBar\Bar();
		$bar->add_hooks();
	} elseif( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		// ajax code

	} else {
		// admin code
		require __DIR__ . '/src/Admin.php';
		$admin = new Mailchimp\TopBar\Admin();
		$admin->add_hooks();
	}

}, 30 );
