<?php

namespace MailChimp\TopBar;

class Options {

	/**
	 * @var string
	 */
	private $options_key = '';

	/**
	 * @var array Array of options, without inherited values
	 */
	private $options = array();

	/**
	 * @var array Array of options with inherited values
	 */
	private $inherited_options = array();

	/**
	 * Constructor
	 * @param string $options_key
	 */
	public function __construct( $options_key ) {
		$this->options_key = $options_key;
		$this->options = $this->load_options();
		$this->inherited_options = $this->load_inherited_options();
	}

	/**
	 * Get an option value
	 *
	 * @param      $key
	 * @param bool $inherit
	 *
	 * @return mixed
	 */
	public function get( $key, $inherit = true ) {
		if( $inherit ) {
			return $this->inherited_options[ $key ];
		} else {
			return $this->options[ $key ];
		}
	}

	/**
	 * @return array
	 */
	private function get_defaults() {
		return array(
			'list' => '',
			'enabled' => 1,
			'show_to_administrators' => 1,
			'cookie_length' => 90,
			'color_bar' => '#ffcc00',
			'color_text' => '#222222',
			'color_button' => '#222222',
			'color_button_text' => '#ffffff',
			'size' => 'medium',
			'sticky' => 1,
			'text_email_placeholder' => __( 'Your email address..', 'mailchimp-top-bar' ),
			'text_bar' => __( 'Sign-up now - don\'t miss the fun!', 'mailchimp-top-bar' ),
			'text_button' => __( 'Subscribe', 'mailchimp-top-bar' ),
			'redirect' => '',
			'position' => 'top',
			'double_optin' => '',
			'text_subscribed' => '',
			'text_error' => '',
			'text_invalid_email' => '',
			'text_already_subscribed' => '',
		);
	}

	/**
	 * @return array
	 */
	private function load_options() {
		$options = (array) get_option( $this->options_key, array() );
		$options = array_merge( $this->get_defaults(), $options );
		return $options;
	}

	/**
	 * @return array
	 */
	private function load_inherited_options() {

		$parent_options = mc4wp_get_options( 'form' );
		$inherited_options = $this->options;

		// specify keys which should inherit if empty
		$inheritance_keys = array(
			'redirect',
			'double_optin',
			'text_subscribed',
			'text_error',
			'text_invalid_email',
			'text_already_subscribed',
		);

		// Use parent value if option value is empty
		foreach( $inheritance_keys as $key ) {
			if( $inherited_options[ $key ] === '' ) {
				$inherited_options[ $key ] = $parent_options[ $key ];
			}
		}

		return $inherited_options;
	}



}