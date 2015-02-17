<?php
use MailChimp\TopBar\Plugin;

defined( 'ABSPATH' ) or exit;
?>
<div class="wrap" id="mc4wp-admin">

	<h1 style="line-height: 48px;">MailChimp Top Bar</h1>

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
						<input type="radio" name="<?php echo $this->name_attr( 'enabled' ); ?>" value="1" <?php checked( $this->options['enabled'], 1 ); ?> /> <?php _e( 'Yes' ); ?>
					</label>
					<label>
						<input type="radio" name="<?php echo $this->name_attr( 'enabled' ); ?>" value="0" <?php checked( $this->options['enabled'], 0 ); ?> /> <?php _e( 'No' ); ?>
					</label>
				</td>
				<td class="desc">
					<?php _e( 'A quick way to completely disable the bar.', 'mailchimp-top-bar' ); ?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label><?php _e( 'MailChimp List', 'mailchimp-for-wp' ); ?></label></th>
				<td>
					<?php if( empty( $lists ) ) {
						printf( __( 'No lists found, <a href="%s">are you connected to MailChimp</a>?', 'mailchimp-for-wp' ), admin_url( 'admin.php?page=mailchimp-for-wp' ) ); ?>
					<?php } ?>

					<select name="<?php echo $this->name_attr( 'list' ); ?>" id="select-mailchimp-list" class="widefat">
						<option disabled <?php selected( $this->options['list'], '' ); ?>><?php _e( 'Select a list..', 'mailchimp-top-bar' ); ?></option>
						<?php foreach( $lists as $list ) { ?>
							<option value="<?php echo esc_attr( $list->id ); ?>" <?php selected( $this->options['list'], $list->id ); ?>><?php echo esc_html( $list->name ); ?></option>
						<?php } ?>
					</select>
				</td>
				<td class="desc"><?php _e( 'Select the list to which visitors should be subscribed.' ,'mailchimp-top-bar' ); ?></td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label>
						<?php _e( 'Bar Text', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'text_bar' ); ?>" value="<?php echo esc_attr( $this->options['text_bar'] ); ?>" class="widefat" />
				</td>
				<td class="desc">
					<?php _e( 'The text to appear before the email field.', 'mailchimp-top-bar' ); ?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label>
						<?php _e( 'Button Text', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'text_button' ); ?>" value="<?php echo esc_attr( $this->options['text_button'] ); ?>" class="widefat" />
				</td>
				<td class="desc">
					<?php _e( 'The text on the submit button.', 'mailchimp-top-bar' ); ?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label>
						<?php _e( 'Email Placeholder Text', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'text_email_placeholder' ); ?>" value="<?php echo esc_attr( $this->options['text_email_placeholder'] ); ?>" class="widefat" />
				</td>
				<td class="desc">
					<?php _e( 'The initial placeholder text to appear in the email field.', 'mailchimp-top-bar' ); ?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<p><?php printf( __( 'Success and error messages can be managed in <a href="%s">%s &raquo; %s</a>', 'mailchimp-top-bar' ), admin_url( 'admin.php?page=mailchimp-for-wp-form-settings' ), 'MailChimp for WordPress', 'Form Settings' ); ?></p>
				</td>
			</tr>



		</table>

		<h2><?php _e( 'Appearance', 'mailchimp-for-wp'); ?></h2>

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
								<option value="small" <?php selected( $this->options['size'], 'small' ); ?>><?php _e( 'Small', 'mailchimp-top-bar' ); ?></option>
								<option value="medium" <?php selected( $this->options['size'], 'medium' ); ?>><?php _e( 'Medium', 'mailchimp-top-bar' ); ?></option>
								<option value="big" <?php selected( $this->options['size'], 'big' ); ?>><?php _e( 'Big', 'mailchimp-top-bar' ); ?></option>
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
							<input type="text" name="<?php echo $this->name_attr( 'color_bar' ); ?>" value="<?php echo esc_attr( $this->options['color_bar'] ); ?>" class="color">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Text Color', 'mailchimp-top-bar' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->name_attr( 'color_text' ); ?>" value="<?php echo esc_attr( $this->options['color_text'] ); ?>" class="color">
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
								<input type="radio" name="<?php echo $this->name_attr( 'sticky' ); ?>" value="1" <?php checked( $this->options['sticky'], 1 ); ?> /> <?php _e( 'Yes' ); ?>
							</label>
							<label>
								<input type="radio" name="<?php echo $this->name_attr( 'sticky' ); ?>" value="0" <?php checked( $this->options['sticky'], 0 ); ?> /> <?php _e( 'No' ); ?>
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
							<input type="text" name="<?php echo $this->name_attr( 'color_button' ); ?>" value="<?php echo esc_attr( $this->options['color_button'] ); ?>" class="color">
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label>
								<?php _e( 'Button Text Color', 'mailchimp-top-bar' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="<?php echo $this->name_attr( 'color_button_text' ); ?>" value="<?php echo esc_attr( $this->options['color_button_text'] ); ?>" class="color">
						</td>
					</tr>

				</table>
			</div>
		</div>

		<br style="clear: both;" />


		<?php submit_button(); ?>
	</form>

</div>