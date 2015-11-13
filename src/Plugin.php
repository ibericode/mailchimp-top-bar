<?php

namespace MailChimp\TopBar;

class Plugin {

	/**
	 * @const VERSION
	 */
	const VERSION = MAILCHIMP_TOP_BAR_VERSION;

	/**
	 * @const FILE
	 */
	const FILE = MAILCHIMP_TOP_BAR_FILE;

	/**
	 * @const DIR
	 */
	const DIR = MAILCHIMP_TOP_BAR_DIR;

	/**
	 * @const OPTION_NAME Option name
	 */
	const OPTION_NAME = 'mailchimp_top_bar';

	/**
	 * @var array
	 */
	private $options = array();

	/**
	 * @var
	 */
	private static $instance;

	/**
	 * @return Plugin
	 */
	public static function instance() {

		if( ! self::$instance ) {
			self::$instance = new Plugin;
		}

		return self::$instance;
	}

	/**
	 * Let's go...
	 *
	 * Runs at `plugins_loaded` priority 30.
	 */
	public function init() {

		// load plugin options
		$this->options = $this->load_options();

		// Load area-specific code
		if( ! is_admin() ) {

			// frontend code

			// show bar, if it's enabled
			$bar = new Bar( $this->options );
			$bar->add_hooks();


		} elseif( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			// ajax code

		} else {

			// admin code
			$admin = new Admin\Manager( $this->options );
			$admin->add_hooks();
		}
	}

	/**
	 * @return Options
	 */
	public function load_options() {
		return new Options( self::OPTION_NAME );
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

}