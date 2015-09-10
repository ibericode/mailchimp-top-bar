<?php

namespace MailChimp\TopBar;

class Tracker {

	/**
	 * The tracked data.
	 * Contains an array of MailChimp list ID's => the time of sign-up.
	 *
	 * @var array|mixed
	 */
	protected $data = array();

	/**
	 * How long should tracked sign-ups be counted as valid?
	 *
	 * @var int
	 */
	protected $expires;

	/**
	 * Do we have anything to track?
	 *
	 * @var bool
	 */
	protected $dirty = false;

	/**
	 * @const string
	 */
	const COOKIE_ID = '_mctb';

	/**
	 * @param int $expires
	 */
	public function __construct( $expires = 7889231 ) {
		$this->expires = $expires;
		$this->data = $this->parse_data();
	}

	/**
	 * @return array
	 */
	public function parse_data() {

		if( ! isset( $_COOKIE[ self::COOKIE_ID ] ) ) {
			return array();
		}

		$cookie_data = stripslashes( $_COOKIE[ self::COOKIE_ID ] );

		return json_decode( $cookie_data, true );
	}

	/**
	 * @param int $expiration_time Time of expiration.
	 * @return bool True if expired, false otherwise or on failure.
	 */
	public function has_expired( $time ) {
		return ( $time + $this->expires ) <= time();
	}

	/**
	 * @param string $list_id
	 */
	public function track( $list_id ) {
		$this->data[ $list_id ] = time();
		$this->dirty = true;
	}

	/**
	 * @param string $list_id
	 * @return bool
	 */
	public function is_tracked( $list_id ) {
		return ( isset( $this->data[ $list_id ] ) && ! $this->has_expired( $this->data[ $list_id ] ) );
	}

	/**
	 * Clean tracking data (removes expired items)
	 */
	public function clean() {
		foreach( $this->data as $list_id => $time ) {
			if( $this->has_expired( $time ) ) {
				unset( $this->data[ $list_id ] );
				$this->dirty = true;
			}
		}
	}

	/**
	 * Save the tracked data to a cookie.
	 */
	public function save() {
		$this->clean();

		if( $this->dirty ) {
			setcookie( self::COOKIE_ID, json_encode( $this->data ), time() + $this->expires, '/' );
		}
	}
}