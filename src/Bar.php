<?php

namespace MailChimp\TopBar;

use Exception;
use MC4WP_Debug_Log;

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
	 *
	 * @param Options $options
	 */
	public function __construct( Options $options ) {
		$this->options = $options;
	}

	/**
	 * Add the hooks
	 */
	public function add_hooks() {
		add_action( 'wp', array( $this, 'init' ) );
	}

	/**
	 * Add template related hooks
	 */
	public function add_template_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );
		add_action( 'wp_head', array( $this, 'output_css'), 90 );
		add_action( 'wp_footer', array( $this, 'output_html' ), 1 );
	}

	/**
	 * @return bool
	 */
	public function init() {

		if( ! $this->should_show_bar() ) {
			return false;
		}

		$this->add_template_hooks();
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
		if( ! $this->options->get('enabled') ) {
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
		$log = $this->get_log();

		if( ! $this->validate() ) {

			if( $log ) {
				$log->info( sprintf( 'Top Bar > Submitted with errors: %s', $this->error_type ) );
			}

			return false;
		}

		/**
		 * Filters the list to which Top Bar subscribed
		 *
		 * @param string $list_id
		 */
		$mailchimp_list_id = apply_filters( 'mctb_mailchimp_list', $this->options->get( 'list' ) );

		// check if a MailChimp list was given
		if( empty( $mailchimp_list_id ) ) {
			$this->error_type = 'error';

			if( $log ) {
				$log->warning( 'Top Bar > No MailChimp lists were selected' );
			}

			return false;
		}

		$email = sanitize_text_field( $_POST['email'] );

		// subscribe email to selected list
		$api = mc4wp_get_api();

		/**
		 * Filters fields which are sent to MailChimp from Top Bar requests.
		 *
		 * @param array $merge_vars
		 */
		$merge_vars = apply_filters( 'mctb_merge_vars', array() );
		$email_type = apply_filters( 'mctb_email_type', 'html' );

		// TODO: add `mc4wp_merge_vars` and `mc4wp_lists` filter here?
		$result = $api->subscribe( $mailchimp_list_id, $email, $merge_vars, $email_type, $this->options->get( 'double_optin' ), $this->options->get( 'update_existing' ), true, $this->options->get( 'send_welcome' ) );

		/*
		 * @deprecated
		 *
		 * This is only still here for backwards compatibility with MailChimp for WP 2.x
		 */
		do_action( 'mc4wp_subscribe', $email, $mailchimp_list_id, $merge_vars, ( $result === true ), 'form', 'top-bar' );

		// return true if success..
		if( $result ) {

			/**
			 * Fires for every successful sign-up using Top Bar.
			 *
			 * @param string $mailchimp_list_id
			 * @param string $email
			 * @param array $merge_vars
			 */
			do_action( 'mctb_subscribed', $mailchimp_list_id, $email, $merge_vars );

			// track sign-up attempt
			$tracker = new Tracker( 365 * DAY_IN_SECONDS );
			$tracker->track( $mailchimp_list_id );
			$tracker->save();

			// log sign-up attempt
			if( $log ) {
				$log->info( sprintf( 'Top Bar > Successfully subscribed %s', $email ) );
			}

			// should we redirect
			if( '' !== $this->options->get( 'redirect' ) ) {
				wp_redirect( $this->options->get( 'redirect' ) );
				exit;
			}

			return true;
		}

		// An API error occured... Oh noes!
		if( $api->get_error_code() === 214 ) {
			$this->error_type = 'already_subscribed';

			if( $log ) {
				$log->warning( sprintf( 'Top Bar > %s is already subscribed to the selected list(s)', $email ) );
			}
		} else {
			$this->error_type = 'error';

			if( $log ) {
				$log->error( sprintf( 'Top Bar > MailChimp API error: %s', $api->get_error_message() ) );
			}
		}

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

		// make sure `_mctb_timestamp` is at least 1.5 seconds ago
		if( ! isset( $_POST['_mctb_timestamp'] ) || time() < ( intval( $_POST['_mctb_timestamp'] ) + 1.5 ) ) {
			$this->error_type = 'spam';
			return false;
		}

		// don't work for users without JavaScript (since bar is hidden anyway, must be a bot)
		if( isset( $_POST['_mctb_no_js'] ) ) {
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
	 * Loads the required scripts & styles
	 */
	public function load_assets() {
		$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		wp_enqueue_style( 'mailchimp-top-bar', $this->asset_url( "/css/bar{$min}.css" ), array(), MAILCHIMP_TOP_BAR_VERSION );
		wp_enqueue_script( 'mailchimp-top-bar', $this->asset_url( "/js/bar{$min}.js" ), array(), MAILCHIMP_TOP_BAR_VERSION, true );

		$bottom = ( $this->options->get( 'position' ) === 'bottom' );

		$data = array(
			'cookieLength' => $this->options->get('cookie_length'),
			'icons' => array(
				'hide' => ( $bottom ) ? '&#x25BC;' : '&#x25B2;',
				'show' =>  ( $bottom ) ? '&#x25B2;' : '&#x25BC;'
			),
			'position' => $this->options->get( 'position' ),
			'is_submitted' => $this->submitted,
			'is_success' => $this->success
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

		// enqueue placeholders.js from MailChimp for WordPress core plugin
		if( isset( $GLOBALS['is_IE'] ) && $GLOBALS['is_IE'] ) {
			wp_enqueue_script( 'mc4wp-placeholders' );
		}
	}

	/**
	 * @return string
	 */
	private function get_css_class() {
		$classes = array( 'mctb' );

		// add class when bar is sticky
		if( $this->options->get( 'sticky' ) ) {
			$classes[] = 'mctb-sticky';
		}

		// add unique css class for position (bottom|top)
		if( $this->options->get( 'position' ) ) {
			$classes[] = sprintf( 'mctb-position-%s', $this->options->get( 'position' ) );
		}

		// add class describing size of the bar
		$classes[] = "mctb-{$this->options->get( 'size' )}";

		return join( ' ', $classes );
	}

	/**
	 * Output the CSS settings for the bar
	 */
	public function output_css() {
		$bar_color = $this->options->get( 'color_bar' );
		$button_color = $this->options->get('color_button');
		$text_color = $this->options->get( 'color_text' );
		$button_text_color = $this->options->get( 'color_button_text' );

		echo '<style type="text/css">' . PHP_EOL;

		if( ! empty( $bar_color) ) {
			echo ".mctb-bar, .mctb-response, .mctb-close { background: {$bar_color} !important; }" . PHP_EOL;
		}

		if( ! empty( $text_color ) ) {
			echo ".mctb-bar, .mctb-label, .mctb-close { color: {$text_color} !important; }" . PHP_EOL;
		}

		if( ! empty( $button_color ) ) {
			echo ".mctb-button { background: {$button_color} !important; border-color: {$button_color} !important; }" . PHP_EOL;
			echo ".mctb-email:focus { outline-color: {$button_color} !important; }" . PHP_EOL;
		}

		if( ! empty( $button_text_color ) ) {
			echo ".mctb-button { color: {$button_text_color} !important; }" . PHP_EOL;
		}

		echo '</style>';
	}


	/**
	 * Output the HTML for the opt-in bar
	 */
	public function output_html() {

		$form_action = apply_filters( 'mctb_form_action', null );

		?>
		<div id="mailchimp-top-bar" class="<?php echo $this->get_css_class(); ?>">
			<!-- MailChimp Top Bar v<?php echo MAILCHIMP_TOP_BAR_VERSION; ?> - https://wordpress.org/plugins/mailchimp-top-bar/ -->
			<div class="mctb-bar" style="display: none">
				<?php echo $this->get_response_message(); ?>
				<form method="post" <?php if( is_string( $form_action ) ) { printf( 'action="%s"', esc_attr( $form_action ) ); } ?>>
					<?php do_action( 'mctb_before_label' ); ?>
					<label class="mctb-label"><?php echo $this->options->get( 'text_bar' ); ?></label>
					<?php do_action( 'mctb_before_email_field' ); ?>
					<input type="email" name="email" placeholder="<?php echo esc_attr( $this->options->get( 'text_email_placeholder' ) ); ?>" class="mctb-email"  />
					<input type="text"  name="email_confirm" placeholder="Confirm your email" value="" autocomplete="off" tabindex="-1" class="mctb-email-confirm" />
					<?php do_action( 'mctb_before_submit_button' ); ?>
					<input type="submit" value="<?php echo esc_attr( $this->options->get('text_button') ); ?>" class="mctb-button" />
					<input type="hidden" name="_mctb" value="1" />
					<input type="hidden" name="_mctb_no_js" value="1" />
					<input type="hidden" name="_mctb_timestamp" value="<?php echo time(); ?>" />
				</form>
			</div>
			<!-- / MailChimp Top Bar -->
		</div>
		<?php
	}

	/**
	 * @return string
	 */
	protected function get_response_message() {

		if( ! $this->submitted ) {
			return '';
		}

		if( $this->success ) {
			$message = $this->options->get('text_subscribed');
		} else if( $this->error_type === 'already_subscribed' ) {
			$message = $this->options->get('text_already_subscribed');
		} else if( $this->error_type === 'invalid_email' ) {
			$message = $this->options->get('text_invalid_email');
		} else {
			$message = $this->options->get('text_error');
		}

		return sprintf( '<div class="mctb-response"><label class="mctb-response-label">%s</label></div>', $message );
	}

	/**
	 * @param $url
	 *
	 * @return string
	 */
	protected function asset_url( $url ) {
		return plugins_url( '/assets' . $url, MAILCHIMP_TOP_BAR_FILE );
	}

	/**
	 * Returns the debug logger or null, if MailChimp for WordPress 3.1 is not installed.
	 *
	 * @return MC4WP_Debug_Log|null
	 */
	protected function get_log() {

		try {
			$log = mc4wp('log');
		} catch( Exception $e ) {
			return null;
		}

		return $log;
	}

}
