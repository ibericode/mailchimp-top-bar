<?php

namespace MailChimp\TopBar;

use InvalidArgumentException;

class Options {

	/**
	 * @var
	 */
	public $key;

	/**
	 * @var array Array of options, without inherited values
	 */
	private $options = array();

	/**
	 * Constructor
	 * @param string $options_key
	 */
	public function __construct( $options_key ) {
		$this->key = $options_key;
		$this->options = $this->load();
	}

	/**
	 * Get an option value
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return mixed
	 * @throw InvalidArgumentException
	 */
	public function get( $key, $default = null ) {

		if( isset( $this->options[ $key ] ) ) {
			return $this->options[ $key ];
		}

		if( isset( $default ) ) {
			return $default;
		}

		throw new InvalidArgumentException( "{$key} is not a valid option key." );
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
			'double_optin' => 1,
			'send_welcome' => 0,
			'update_existing' => 0,
			'text_subscribed' => __( "Thanks, you're in! Please check your email inbox for a confirmation.", 'mailchimp-top-bar' ),
			'text_error' => __( "Oops. Something went wrong.", 'mailchimp-top-bar' ),
			'text_invalid_email' => __( 'That email seems to be invalid.', 'mailchimp-top-bar' ),
			'text_already_subscribed' => __( "You are already subscribed. Thank you!", 'mailchimp-top-bar' ),
		);
	}

	/**
	 * @return array
	 */
	private function load() {
		$defaults = $this->get_defaults();
		$options = (array) get_option( $this->key, array() );
		$options = array_merge( $defaults, $options );

		// for BC with MailChimp Top Bar v1.2.3, always fill text option keys
		$text_keys = array(
			'text_subscribed',
			'text_error',
			'text_invalid_email',
			'text_already_subscribed'
		);

		foreach( $text_keys as $text_key ) {
			if( empty( $options[ $text_key ] ) && ! empty( $defaults[ $text_key ] ) ) {
				$options[ $text_key ] = $defaults[ $text_key ];
			}
		}

		return $options;
	}

}