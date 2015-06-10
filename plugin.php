<?php

namespace MailChimp\TopBar;

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

final class Plugin {

	/**
	 * @const VERSION
	 */
	const VERSION = '1.1';

	/**
	 * @const FILE
	 */
	const FILE = MAILCHIMP_TOP_BAR_FILE;

	/**
	 * @const DIR
	 */
	const DIR = __DIR__;

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
	 * Constructor
	 */
	private function __construct() {

		require __DIR__ . '/vendor/autoload.php';

		// Load plugin files on a later hook
		add_action( 'plugins_loaded', array( $this, 'load' ), 30 );
	}

	/**
	 * Let's go...
	 *
	 * Runs at `plugins_loaded` priority 30.
	 */
	public function load() {

		// check dependencies and only continue if installed
		$dependencyCheck = new DependencyCheck();
		if( ! $dependencyCheck->dependencies_installed ) {
			return false;
		}

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

$GLOBALS['MailChimp_Top_Bar'] = Plugin::instance();