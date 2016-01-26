<?php
use MailChimp\TopBar\Options;
use MailChimp\TopBar\Plugin;

defined( 'ABSPATH' ) or exit;

$tabs = array(
	'settings'      => __( "Bar Setting", "mailchimp-for-wp" ),
	'appearance'    => __( "Appearance", "mailchimp-for-wp" ),
	'messages'      => __( "Messages", "mailchimp-for-wp" ),
	'advanced'      => __( "Advanced", "mailchimp-for-wp" ),
);

/** @var Options $opts */
?>
<div class="wrap" id="mc4wp-admin">

	<h1 class="page-title">MailChimp Top Bar</h1>

	<h2 class="nav-tab-wrapper" id="mctb-tabs">
		<?php foreach( $tabs as $tab => $title ) {
			$class = ( $current_tab === $tab ) ? 'nav-tab-active' : '';
			echo sprintf( '<a class="nav-tab nav-tab-%s %s" href="%s">%s</a>', $tab, $class, admin_url( 'admin.php?page=mailchimp-for-wp-top-bar&tab=' . $tab ), $title );
		} ?>
	</h2>

	<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">

		<h2 style="display: none;"></h2>
		<?php settings_fields( $opts->key ); ?>
		<?php settings_errors(); ?>

		<div id="message-list-requires-fields" class="notice notice-warning" style="display: none;">
			<p><?php printf( __( 'The selected MailChimp list requires more fields than just an <strong>%s</strong> field. Please <a href="%s">log into your MailChimp account</a> and make sure only the <strong>%s</strong> field is marked as required.', 'mailchimp-top-bar' ), 'EMAIL', 'https://admin.mailchimp.com/lists/', 'EMAIL' ); ?></p>
			<p class="help"><?php printf( __( 'After making changes to your MailChimp list, <a href="%s">click here</a> to renew your list configuration.', 'mailchimp-top-bar' ), add_query_arg( array( '_mc4wp_action' => 'empty_lists_cache' ) ) ); ?></p>
		</div>

		<?php $config = array( 'element' => $this->name_attr( 'enabled' ), 'value' => 0 ); ?>
		<div id="message-bar-is-disabled" class="notice notice-warning" data-showif="<?php echo esc_attr( json_encode( $config ) ); ?>">
			<p>
				<?php _e( 'You have disabled the bar. It will not show up on your site until you enable it again.', 'mailchimp-top-bar' ); ?>
			</p>
		</div>

		<!-- Bar Settings -->
		<div class="tab <?php if( $current_tab === 'settings' ) echo 'tab-active'; ?>" id="tab-settings">

			<h2><?php _e( 'Bar Settings', 'mailchimp-for-wp'); ?></h2>

			<table class="form-table">

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Enable Bar?', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'enabled' ); ?>" value="1" <?php checked( $opts->get( 'enabled' ), 1 ); ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'enabled' ); ?>" value="0" <?php checked( $opts->get( 'enabled' ), 0 ); ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help"><?php _e( 'A quick way to completely disable the bar.', 'mailchimp-top-bar' ); ?></p>

					</td>
				</tr>

				<tr valign="top">
					<th scope="row"><label><?php _e( 'MailChimp List', 'mailchimp-for-wp' ); ?></label></th>
					<td>
						<?php if( empty( $lists ) ) {
							printf( __( 'No lists found, <a href="%s">are you connected to MailChimp</a>?', 'mailchimp-for-wp' ), admin_url( 'admin.php?page=mailchimp-for-wp' ) ); ?>
						<?php } ?>

						<select name="<?php echo $this->name_attr( 'list' ); ?>" class="mc4wp-list-input" id="select-mailchimp-list">
							<option disabled <?php selected( $opts->get( 'list' ), '' ); ?>><?php _e( 'Select a list..', 'mailchimp-top-bar' ); ?></option>
							<?php foreach( $lists as $list ) { ?>
								<option value="<?php echo esc_attr( $list->id ); ?>" <?php selected( $opts->get( 'list' ), $list->id ); ?>><?php echo esc_html( $list->name ); ?></option>
							<?php } ?>
						</select>
						<p class="help"><?php _e( 'Select the list to which visitors should be subscribed.' ,'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Bar Text', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'text_bar' ); ?>" value="<?php echo esc_attr( $opts->get( 'text_bar' ) ); ?>" class="regular-text" />
						<p class="help"><?php _e( 'The text to appear before the email field.', 'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Button Text', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'text_button' ); ?>" value="<?php echo esc_attr( $opts->get( 'text_button' ) ); ?>" class="regular-text" />
						<p class="help"><?php _e( 'The text on the submit button.', 'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Email Placeholder Text', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'text_email_placeholder' ); ?>" value="<?php echo esc_attr( $opts->get( 'text_email_placeholder' ) ); ?>" class="regular-text" />
						<p class="help"><?php _e( 'The initial placeholder text to appear in the email field.', 'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Appearance Tab -->
		<div class="tab <?php if( $current_tab === 'appearance' ) echo 'tab-active'; ?>" id="tab-appearance">

			<h2><?php _e( 'Appearance', 'mailchimp-top-bar' ); ?></h2>

			<div class="row">
				<div class="col col-2">
					<table class="form-table">

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Bar Position', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<select name="<?php echo $this->name_attr( 'position' ); ?>" id="select-bar-position">
									<option value="top" <?php selected( $opts->get( 'position' ), 'top' ); ?>><?php _e( 'Top', 'mailchimp-top-bar' ); ?></option>
									<option value="bottom" <?php selected( $opts->get( 'position' ), 'bottom' ); ?>><?php _e( 'Bottom', 'mailchimp-top-bar' ); ?></option>
								</select>
							</td>
						</tr>

						<tr valign="top" class="bar-size-options" style="">
							<th scope="row">
								<label>
									<?php _e( 'Bar Size', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<select name="<?php echo $this->name_attr( 'size' ); ?>">
									<option value="small" <?php selected( $opts->get( 'size' ), 'small' ); ?>><?php _e( 'Small', 'mailchimp-top-bar' ); ?></option>
									<option value="medium" <?php selected( $opts->get( 'size' ), 'medium' ); ?>><?php _e( 'Medium', 'mailchimp-top-bar' ); ?></option>
									<option value="big" <?php selected( $opts->get( 'size' ), 'big' ); ?>><?php _e( 'Big', 'mailchimp-top-bar' ); ?></option>
								</select>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Bar Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_bar' ); ?>" value="<?php echo esc_attr( $opts->get( 'color_bar' ) ); ?>" class="color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Text Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_text' ); ?>" value="<?php echo esc_attr( $opts->get( 'color_text' ) ); ?>" class="color">
							</td>
						</tr>

					</table>
				</div>
				<div class="col col-2">
					<table class="form-table">

						<?php $config = array( 'element' =>  $this->name_attr( 'position' ), 'value' => 'top' ); ?>
						<tr valign="top" class="sticky-bar-options" data-showif="<?php echo esc_attr( json_encode( $config ) ); ?>">
							<th scope="row">
								<label>
									<?php _e( 'Sticky Bar?', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<label>
									<input type="radio" name="<?php echo $this->name_attr( 'sticky' ); ?>" value="1" <?php checked( $opts->get( 'sticky' ), 1 ); ?> /> <?php _e( 'Yes' ); ?>
								</label>
								<label>
									<input type="radio" name="<?php echo $this->name_attr( 'sticky' ); ?>" value="0" <?php checked( $opts->get( 'sticky' ), 0 ); ?> /> <?php _e( 'No' ); ?>
								</label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Button Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_button' ); ?>" value="<?php echo esc_attr( $opts->get( 'color_button' ) ); ?>" class="color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="row">
								<label>
									<?php _e( 'Button Text Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_button_text' ); ?>" value="<?php echo esc_attr( $opts->get( 'color_button_text' ) ); ?>" class="color">
							</td>
						</tr>

					</table>
				</div>
			</div>
			<br style="clear: both;" />
		</div>

		<!-- Form Messages -->
		<div class="tab <?php if( $current_tab === 'messages' ) echo 'tab-active'; ?>" id="tab-messages">

			<h2><?php _e( 'Messages', 'mailchimp-top-bar' ); ?></h2>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Success', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_subscribed'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_subscribed' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_subscribed' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Invalid email address', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_invalid_email'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_invalid_email' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_invalid_email' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Already subscribed', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_already_subscribed'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_already_subscribed' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_already_subscribed' ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Other errors' ,'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_error'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_error' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_error' ) ); ?>" /></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<p class="help"><?php printf( __( 'HTML tags like %s are allowed in the message fields.', 'mailchimp-for-wp' ), '<code>' . esc_html( '<strong><em><a>' ) . '</code>' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Redirect to URL after successful sign-ups', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'redirect' ); ?>" placeholder="<?php echo esc_url( $opts->get( 'redirect' ) ); ?>" value="<?php echo esc_url( $opts->get( 'redirect' ) ); ?>" class="widefat" />
						<p class="help"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'mailchimp-for-wp' ); ?></p>
					</td>
				</tr>

			</table>
		</div>

		<div class="tab <?php if( $current_tab === 'advanced' ) echo 'tab-active'; ?>" id="tab-advanced">
			<h2><?php _e( 'Advanced', 'mailchimp-top-bar' ); ?></h2>

			<table class="form-table">
				<tr valign="top" class="double-optin-options">
					<th scope="row">
						<label>
							<?php _e( 'Double opt-in?', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'double_optin' ); ?>" value="1" <?php checked( $opts->get( 'double_optin' ), 1 ); ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'double_optin' ); ?>" value="0" <?php checked( $opts->get( 'double_optin' ), 0 ); ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help">
							<?php _e( 'Select "yes" if you want people to confirm their email address before being subscribed (recommended)', 'mailchimp-for-wp' ); ?>
						</p>
					</td>
				</tr>

				<?php $config = array( 'element' => $this->name_attr( 'double_optin' ), 'value' => 0 ); ?>
				<tr valign="top" class="send-welcome-options" data-showif="<?php echo esc_attr( json_encode( $config ) ); ?>">
					<th scope="row">
						<label>
							<?php _e( 'Send Welcome Email?', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'send_welcome' ); ?>" value="1" <?php checked( $opts->get( 'send_welcome' ), 1 ); ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'send_welcome' ); ?>" value="0" <?php checked( $opts->get( 'send_welcome' ), 0 ); ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help">
							<?php _e( 'Select "yes" if you want to send your lists Welcome Email if a subscribe succeeds (only when double opt-in is disabled).', 'mailchimp-for-wp' ); ?>
						</p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="row">
						<label>
							<?php _e( 'Update existing subscribers?', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'update_existing' ); ?>" value="1" <?php checked( $opts->get( 'update_existing' ), 1 ); ?> /> <?php _e( 'Yes' ); ?>
						</label>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'update_existing' ); ?>" value="0" <?php checked( $opts->get( 'update_existing' ), 0 ); ?> /> <?php _e( 'No' ); ?>
						</label>
						<p class="help">
							<?php _e( 'Select "yes" if you want to update existing subscribers with the data that is sent.', 'mailchimp-for-wp' ); ?>
							<?php printf( __( 'This is really only useful if you have <a href="%s">added additional fields (besides just email)</a>.', 'mailchimp-top-bar' ), 'https://mc4wp.com/kb/add-name-field-to-mailchimp-top-bar/' ); ?>
						</p>
					</td>
				</tr>
			</table>
		</div>


		<?php submit_button(); ?>
	</form>

	<?php
	/**
	 * @ignore
	 */
	do_action( 'mc4wp_admin_footer' );
	?>

</div>