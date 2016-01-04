<?php

namespace MailChimp\TopBar\Admin;

/**
 * Class Upgrader
 * @package MailChimp\TopBar\Admin
 *
 * @todo switch to Migrations class in MailChimp for WP core
 */
class Upgrader {

	/**
	 * @var int
	 */
	protected $database_version = 0;

	/**
	 * @var int
	 */
	protected $code_version = 0;

	/**
	 * @var bool
	 */
	protected $installing = false;

	/**
	 * @param $database_version
	 * @param $code_version
	 */
	public function __construct( $database_version, $code_version ) {
		$this->database_version = $database_version;
		$this->code_version = $code_version;
		$this->installing = ( $database_version === 0 );
	}

	public function run() {

		// update to new option key
		if( ! $this->installing && $this->upgrading_to( '1.0.8' ) ) {
			$options = get_option( 'mailchimp_top_bar', array() );

			if( isset( $options['text_success'] ) ) {
				$options['text_subscribed'] = $options['text_success'];
				unset( $options['text_success'] );
				update_option( 'mailchimp_top_bar', $options );
			}
		}

		update_option( 'mailchimp_top_bar_version', $this->code_version );
	}

	/**
	 * @param $version
	 *
	 * @return mixed
	 */
	protected function upgrading_to( $version ) {
		return version_compare( $this->database_version, $version, '<' );
	}

}