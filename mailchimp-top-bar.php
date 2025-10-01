<?php

/*
Plugin Name: MC4WP: Mailchimp Top Bar
Plugin URI: https://www.mc4wp.com/
Description: Adds a Mailchimp opt-in bar to the top of your site.
Version: 1.7.2
Author: ibericode
Author URI: https://ibericode.com/
Text Domain: mailchimp-top-bar
Domain Path: /languages
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Mailchimp Top Bar
Copyright (C) 2015, Danny van Kooten, hi@dannyvankooten.com

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


defined('ABSPATH') or exit;

add_action('plugins_loaded', function () {
    // check for PHP 7.3 or higher
    if (PHP_VERSION_ID < 70300) {
        return;
    }

    // check for MailChimp for WordPress (version 3.0 or higher)
    if (!defined('MC4WP_VERSION') || version_compare(MC4WP_VERSION, '3.0', '<')) {
        require __DIR__ . '/src/admin-notice-install-deps.php';
        return;
    }


    define('MAILCHIMP_TOP_BAR_FILE', __FILE__);
    define('MAILCHIMP_TOP_BAR_DIR', __DIR__);
    define('MAILCHIMP_TOP_BAR_VERSION', '1.7.2');

    require __DIR__ . '/src/functions.php';

    if (is_admin()) {
        require __DIR__ . '/src/Admin.php';
        $admin = new Mailchimp\TopBar\Admin();
        $admin->add_hooks();
    } else {
        require __DIR__ . '/src/Bar.php';
        $bar = new MailChimp\TopBar\Bar();
        add_action('wp', [$bar, 'init']);
    }
}, 30);
