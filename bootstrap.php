<?php
/*
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

namespace MailChimp\TopBar;

defined( 'ABSPATH' ) or exit;

require __DIR__ . '/src/functions.php';

if( ! is_admin() ) {
	// frontend code
   require_once __DIR__ . '/src/Bar.php';
	$bar = new Bar();
	$bar->add_hooks();
} elseif( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	// ajax code

} else {
	// admin code
    require_once __DIR__ . '/src/Admin/Manager.php';
    $admin = new Admin\Manager();
	$admin->add_hooks();
}
