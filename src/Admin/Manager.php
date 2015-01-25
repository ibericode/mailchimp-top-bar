<?php

namespace MailChimp\TopBar\Admin;

use MailChimp\TopBar\Plugin;

class Manager {

	const SETTINGS_CAP = 'manage_options';

	/**
	 * @var array $options
	 */
	private $options;

	/**
	 * Constructor
	 * @param array $options
	 */
	public function __construct( array $options ) {

		$this->options = $options;

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'menu' ) );
	}

	/**
	 * Runs on `admin_init`
	 */
	public function init() {

		// only run for administrators
		if( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		// register settings
		register_setting( Plugin::OPTION_NAME, Plugin::OPTION_NAME, array( $this, 'sanitize_settings' ) );

		// listen for wphs requests, user is authorized by now
		$this->listen();

		// run upgrade routine
		$this->upgrade_routine();

		add_filter( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * Upgrade routine, only runs when needed
	 */
	private function upgrade_routine() {

		$db_version = get_option( 'mailchimp_top_bar_version', 0 );

		// only run if db version is lower than actual code version
		if ( ! version_compare( $db_version, Plugin::VERSION, '<' ) ) {
			return false;
		}

		// nothing here yet..

		update_option( 'mailchimp_top_bar_version', Plugin::VERSION );
		return true;
	}

	/**
	 * Listen for stuff..
	 */
	private function listen() {

	}

	/**
	 * Register menu pages
	 */
	public function menu() {
		add_submenu_page( 'mailchimp-for-wp', __( 'MailChimp Top Bar', 'mailchimp-top-bar' ), __( 'Top Bar', 'mailchimp-top-bar' ), self::SETTINGS_CAP, 'mailchimp-for-wp-top-bar', array( $this, 'show_settings_page' ) );
	}

	/**
	 * Load assets if we're on the settings page of this plugin
	 *
	 * @return bool
	 */
	public function load_assets() {

		if( ! isset( $_GET['page'] ) || $_GET['page'] !== 'mailchimp-for-wp-top-bar' ) {
			return false;
		}

		$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'mailchimp-top-bar-admin', $this->asset_url( "/css/admin{$min}.css" ) );

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'mailchimp-top-bar-admin', $this->asset_url( "/js/admin{$min}.js" ), array( 'jquery', 'wp-color-picker' ), Plugin::VERSION, true );

		return true;
	}

	/**
	 * Outputs the settings page
	 */
	public function show_settings_page() {

		$mailchimp = new \MC4WP_MailChimp();
		$lists = $mailchimp->get_lists();

		if( $this->options['list'] !== '' ) {
			$list = $mailchimp->get_list( $this->options['list'] );
			$list_requires_extra_fields = $this->list_requires_extra_fields( $list );
		}

		require Plugin::DIR . '/views/settings-page.php';
	}

	/**
	 * @param $list
	 *
	 * @return bool
	 */
	private function list_requires_extra_fields( $list ) {

		foreach( $list->merge_vars as $field ) {
			if( $field->tag !== 'EMAIL' && $field->req ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $url
	 *
	 * @return string
	 */
	protected function asset_url( $url ) {
		return plugins_url( '/assets' . $url, Plugin::FILE );
	}

	/**
	 * @param $option_name
	 *
	 * @return string
	 */
	protected function name_attr( $option_name ) {
		return Plugin::OPTION_NAME . '[' . $option_name . ']';
	}

	/**
	 * @param array $dirty
	 *
	 * @return array $clean
	 */
	public function sanitize_settings( array $dirty ) {

		// todo: perform some actual sanitization
		$clean = $dirty;

		return $clean;
	}


}