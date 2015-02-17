<?php

namespace MailChimp\TopBar;

class Bar {

	/**
	 * @var array
	 */
	private $options;

	/**
	 * @var bool
	 */
	private $success = false;

	/**
	 * @var string
	 */
	private $error_type = '';

	/**
	 * @var bool
	 */
	private $submitted = false;

	/**
	 * Constructor
	 */
	public function __construct( array $options ) {
		$this->options = $options;

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * @return bool
	 */
	public function init() {

		if( ! $this->should_show_bar() ) {
			return false;
		}

		$this->add_hooks();
		$this->listen();
		return true;
	}

	/**
	 * Should the bar be shown?
	 *
	 * @return bool
	 */
	public function should_show_bar() {

		// don't show if bar is disabled
		if( ! $this->options['enabled'] ) {
			return false;
		}

		// todo: add logic to hide bar on certain selected pages

		/**
		 * @deprecated 1.1
		 * @use `mctb_show_bar`
		 */
		$return = apply_filters( 'mctp_show_bar', true );


		/**
		 * @filter `mctb_show_bar`
		 * @expects boolean
		 *
		 * Set to true if the bar should be loaded for this request, false if not.
		 */
		return apply_filters( 'mctb_show_bar', $return );
	}

	/**
	 * Add the hooks
	 */
	public function add_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'wp_head', array( $this, 'output_css'), 90 );
		add_action( 'wp_footer', array( $this, 'output_html' ) );
	}

	/**
	 * Listens for actions to take
	 */
	public function listen() {

		if( isset( $_POST['_mctb'] ) && $_POST['_mctb'] == 1 ) {
			$this->success = $this->process();
		}
	}

	/**
	 * Process a form submission
	 * @return boolean
	 */
	private function process() {

		$this->submitted = true;

		if( ! $this->validate() ) {
			return false;
		}

		$email = sanitize_text_field( $_POST['email'] );

		// subscribe email to selected list
		$api = mc4wp_get_api();
		$merge_vars = apply_filters( 'mctb_merge_vars', array() );
		$email_type = apply_filters( 'mctb_email_type', 'html' );

		$result = $api->subscribe( $this->options['list'], $email, $merge_vars, $email_type, $this->options['double_optin'] );

		do_action( 'mc4wp_subscribe', $email, $this->options['list'], $merge_vars, ( $result === true ), 'form', 'top-bar' );

		// return true if success..
		if( $result === true ) {
			return true;
		}

		$this->error_type = $result;
		return false;
	}

	/**
	 * Validate the form submission
	 * @return boolean
	 */
	private function validate() {

		// make sure `email_confirm` field is given but not filled (honeypot)
		if( ! isset( $_POST['email_confirm'] ) || '' !== $_POST['email_confirm'] ) {
			$this->error_type = 'spam';
			return false;
		}

		// make sure `_mctb_token` is given and valid
		if( ! $this->validate_token() ) {
			$this->error_type = 'spam';
			return false;
		}

		// simple user agent check
		$user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '';
		if( strlen( $user_agent ) < 2 ) {
			$this->error_type = 'spam';
			return false;
		}

		// check if email is given and valid
		if( ! isset( $_POST['email'] ) || ! is_string( $_POST['email'] ) || ! is_email( $_POST['email'] ) ) {
			$this->error_type = 'invalid_email';
			return false;
		}

		return apply_filters( 'mctb_validate', true );
	}

	/**
	 * @return bool
	 */
	private function validate_token() {

		if( ! isset( $_POST['_mctb_token' ] ) || '' === $_POST['_mctb_token'] ) {
			return false;
		}

		if( ! isset( $_SERVER['REQUEST_URI'] ) ) {
			return true;
		}

		$token = ( strlen( $_SERVER['REQUEST_URI'] ) * 11 ) . ( ( substr_count( $_SERVER['REQUEST_URI'], '/' ) + 1 ) * 111 );

		return( $_POST['_mctb_token'] === $token );
	}

	/**
	 * Loads the required scripts & styles
	 */
	public function load_assets() {
		$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( 'mailchimp-top-bar', $this->asset_url( "/css/bar{$min}.css" ), array(), Plugin::VERSION );
		wp_enqueue_script( 'mailchimp-top-bar', $this->asset_url( "/js/bar{$min}.js" ), array( 'jquery' ), Plugin::VERSION, true );

		$data = array(
			'cookieLength' => $this->options['cookie_length'],
			'icons' => array(
				'hide' => '&#x25B2;',
				'show' => '&#x25BC;'
			)
		);

		/**
		 * @filter `mctb_bar_config`
		 * @expects array
		 *
		 * Can be used to filter the following values
		 *  - cookieLength: The length of the cookie
		 *  - icons: Array with `hide` and `show` keys. Holds the hide/show icon strings.
		 */
		$data = apply_filters( 'mctb_bar_config', $data );

		wp_localize_script( 'mailchimp-top-bar', 'mctb', $data );
	}

	/**
	 * @return string
	 */
	private function get_css_class() {
		$classes = array( '' );

		// add class when bar is sticky
		if( $this->options['sticky'] ) {
			$classes[] = 'mctb-sticky';
		}

		// add class describing size of the bar
		$classes[] = "mctb-{$this->options['size']}";

		return join( ' ', $classes );
	}

	/**
	 * Output the CSS settings for the bar
	 */
	public function output_css() {
		echo '<style type="text/css">';

		if( '' !== $this->options['color_bar'] ) {
			echo "#mailchimp-top-bar .mctb-bar, .mctb-response { background: {$this->options['color_bar']}; }";
		}

		if( '' !== $this->options['color_text'] ) {
			echo "#mailchimp-top-bar label { color: {$this->options['color_text']}; }";
		}

		if( '' !== $this->options['color_button'] ) {
			echo "#mailchimp-top-bar .mctb-button { background: {$this->options['color_button']}; border-color: {$this->options['color_button']}; }";
			echo "#mailchimp-top-bar .mctb-email:focus { border-color: {$this->options['color_button']}; }";
		}

		if( '' !== $this->options['color_button_text'] ) {
			echo "#mailchimp-top-bar .mctb-button { color: {$this->options['color_button_text']}; }";
		}

		echo '</style>';
	}


	/**
	 * Output the HTML for the opt-in bar
	 */
	public function output_html() {

		?><div id="mailchimp-top-bar" class="<?php echo $this->get_css_class(); ?>">
			<!-- MailChimp Top Bar v<?php echo Plugin::VERSION; ?> - https://wordpress.org/plugins/mailchimp-top-bar/ -->
			<div class="mctb-bar" style="display: none">
				<?php echo $this->get_response_message(); ?>
				<form method="post">
					<label><?php echo strip_tags( $this->options['text_bar'], '<strong><em><u>' ); ?></label>
					<input type="email" name="email" placeholder="<?php echo esc_attr( $this->options['text_email_placeholder'] ); ?>" class="mctb-email"  />
					<input type="text"  name="email_confirm" placeholder="Confirm your email" value="" class="mctb-email-confirm" />
					<input type="submit" value="<?php echo esc_attr( $this->options['text_button'] ); ?>" class="mctb-button" />
					<input type="hidden" name="_mctb" value="1" />
				</form>
			</div><span class="mctb-close">&#x25BC;</span><!-- / MailChimp Top Bar --></div><?php
	}

	/**
	 * @return string
	 */
	protected function get_response_message() {

		if( ! $this->submitted ) {
			return '';
		}

		if( $this->success ) {
			$message = $this->options['text_success'];
		} else if( $this->error_type === 'already_subscribed' ) {
			$message = $this->options['text_already_subscribed'];
		} else if( $this->error_type === 'invalid_email' ) {
			$message = $this->options['text_invalid_email'];
		} else {
			$message = $this->options['text_error'];
		}

		return sprintf( '<div class="mctb-response"><label>%s</label></div>', $message );
	}

	/**
	 * @param $url
	 *
	 * @return string
	 */
	protected function asset_url( $url ) {
		return plugins_url( '/assets' . $url, Plugin::FILE );
	}

}