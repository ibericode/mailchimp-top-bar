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
	public $options = array();

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

	public function __construct() {
		// load plugin options
		$this->options = new Options( self::OPTION_NAME );
	}

}