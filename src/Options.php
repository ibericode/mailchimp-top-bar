<?php

namespace MailChimp\TopBar;

class Options {

	/**
	 * @var string
	 */
	protected $options_key = '';

	/**
	 * @var array Array of options, without inherited values
	 */
	protected $options = array();

	/**
	 * Constructor
	 * @param string $options_key
	 */
	public function __construct( $options_key ) {
		$this->options_key = $options_key;
		$this->options = $this->load_options();
	}

	/**
	 * Get an option value
	 *
	 * @param      $key
	 *
	 * @return mixed
	 */
	public function get( $key ) {
		return $this->options[ $key ];
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
			'text_subscribed' => __( "Thanks, you're in! Please check your email inbox for a confirmation.", 'mailchimp-top-bar' ),
			'text_error' => __( "Oops. Something went wrong.", 'mailchimp-top-bar' ),
			'text_invalid_email' => __( 'That email seems to be invalid.', 'mailchimp-top-bar' ),
			'text_already_subscribed' => __( "You are already subscribed. Thank you!", 'mailchimp-top-bar' ),
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

}