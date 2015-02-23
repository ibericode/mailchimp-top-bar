<?php
use MailChimp\TopBar\Plugin;

defined( 'ABSPATH' ) or exit;
?>
<div class="wrap" id="mc4wp-admin">

	<h1>MailChimp Top Bar</h1>

	<h2 class="nav-tab-wrapper" id="mctb-tabs">
		<a class="nav-tab <?php if( $tab === 'settings' ) echo 'nav-tab-active'; ?>" href="<?php echo admin_url( 'admin.php?page=mailchimp-for-wp-top-bar&tab=settings'); ?>"><?php _e( 'Bar Settings', 'mailchimp-for-wp' ); ?></a>
		<a class="nav-tab <?php if( $tab === 'appearance' ) echo 'nav-tab-active'; ?>" href="<?php echo admin_url( 'admin.php?page=mailchimp-for-wp-top-bar&tab=appearance'); ?>"><?php _e( 'Appearance', 'mailchimp-for-wp' ); ?></a>
		<a class="nav-tab <?php if( $tab === 'messages' ) echo 'nav-tab-active'; ?>" href="<?php echo admin_url( 'admin.php?page=mailchimp-for-wp-top-bar&tab=messages'); ?>"><?php _e( 'Messages', 'mailchimp-for-wp' ); ?></a>
	</h2>

	<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">

		<h2 style="display: none;"></h2>
		<?php settings_fields( Plugin::OPTION_NAME ); ?>
		<?php settings_errors(); ?>


		<div id="message-list-requires-fields" class="error" style="display: none;">
			<p><?php printf( __( 'The selected MailChimp list requires more fields than just a <strong>%s</strong> field. Please <a href="%s">log into your MailChimp account</a> and make sure only the <strong>%s</strong> field is marked as required.', 'mailchimp-top-bar' ), 'EMAIL', 'https://admin.mailchimp.com/lists/', 'EMAIL' ); ?></p>
		</div>

		<div id="message-bar-is-disabled" class="error" style="display: none;">
			<p>
				<?php _e( 'You have disabled the bar. It will not show up on your site until you enable it again.', 'mailchimp-top-bar' ); ?>
			</p>
		</div>

		<!-- Bar Settings -->
		<div class="tab <?php if( $tab === 'settings' ) echo 'tab-active'; ?>" id="tab-settings">

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

						<select name="<?php echo $this->name_attr( 'list' ); ?>" id="select-mailchimp-list">
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
		<div class="tab <?php if( $tab === 'appearance' ) echo 'tab-active'; ?>" id="tab-appearance">

			<h2><?php _e( 'Appearance', 'mailchimp-top-bar' ); ?></h2>

			<div class="row">
				<div class="col-2">
					<table class="form-table">

						<tr valign="top">
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
				<div class="col-2">
					<table class="form-table">

						<tr valign="top">
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
		<div class="tab <?php if( $tab === 'messages' ) echo 'tab-active'; ?>" id="tab-messages">

			<h2><?php _e( 'Messages', 'mailchimp-top-bar' ); ?></h2>
			<p><?php printf( __( 'All these settings are optional and will by default inherit from <a href="%s">%s &raquo; %s</a>', 'mailchimp-top-bar' ), admin_url( 'admin.php?page=mailchimp-for-wp-form-settings' ), 'MailChimp for WordPress', 'Form Settings' ); ?>.</p>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Success', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_success'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_success' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_success', false ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Invalid email address', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_invalid_email'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_invalid_email' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_invalid_email', false ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Already subscribed', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_already_subscribed'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_already_subscribed' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_already_subscribed', false ) ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label><?php _e( 'Other errors' ,'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_error'); ?>" placeholder="<?php echo esc_attr( $opts->get( 'text_error' ) ); ?>"  value="<?php echo esc_attr( $opts->get( 'text_error', false ) ); ?>" /></td>
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
						<input type="text" name="<?php echo $this->name_attr( 'redirect' ); ?>" placeholder="<?php echo esc_url( $opts->get('redirect' ) ); ?>" value="<?php echo esc_url( $opts->get('redirect', false ) ); ?>" class="regular-text" />
						<p class="help"><?php _e( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'mailchimp-for-wp' ); ?></p>
					</td>
				</tr>

			</table>
		</div>


		<?php submit_button(); ?>
	</form>

</div>