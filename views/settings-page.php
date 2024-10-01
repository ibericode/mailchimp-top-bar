<?php
use MailChimp\TopBar\Options;
use MailChimp\TopBar\Plugin;

defined( 'ABSPATH' ) or exit;

$tabs = array(
	'settings'      => strip_tags(__( "Bar Setting", "mailchimp-top-bar" )),
	'appearance'    => strip_tags(__( "Appearance", "mailchimp-top-bar" )),
	'messages'      => strip_tags(__( "Messages", "mailchimp-top-bar" )),
	'advanced'      => strip_tags(__( "Advanced", "mailchimp-top-bar" )),
);
?>
<?php /* ensure wp-picker does not push other elements around */ ?>
<style type="text/css">
.wp-picker-holder,
.wp-picker-input-wrap {
	position: absolute;
}
</style>
<div class="wrap" id="mc4wp-admin">

	<h1 class="page-title">Mailchimp Top Bar</h1>

	<h2 class="nav-tab-wrapper" id="mctb-tabs">
		<?php foreach( $tabs as $tab => $title ) {
			$class = ( $current_tab === $tab ) ? 'nav-tab-active' : '';
			echo sprintf( '<a class="nav-tab nav-tab-%s %s" data-tab="%s" href="%s">%s</a>', $tab, $class, $tab, admin_url( 'admin.php?page=mailchimp-for-wp-top-bar&tab=' . $tab ), $title );
		} ?>
	</h2>

	<form method="post" action="<?php echo esc_attr(admin_url( 'options.php' )); ?>">

		<h2 style="display: none;"></h2>
		<?php settings_fields('mailchimp_top_bar'); ?>
		<?php settings_errors(); ?>

		<div id="message-list-requires-fields" class="notice notice-warning" style="display: none;">
			<p><?php printf(wp_kses(__( 'The selected Mailchimp audience requires more fields than just an <strong>%s</strong> field. Please <a href="%s">log into your Mailchimp account</a> and make sure only the <strong>%s</strong> field is marked as required.', 'mailchimp-top-bar' ), array( 'a' => array( 'href' => array()), 'strong' => array())), 'EMAIL', 'https://admin.mailchimp.com/audience/', 'EMAIL' ); ?></p>
			<p class="description"><?php printf(wp_kses(__( 'After making changes to your Mailchimp audience, <a href="%s">click here</a> to renew your list configuration.', 'mailchimp-top-bar' ), array('a' => array('href' => array()))), esc_attr( add_query_arg( array( '_mc4wp_action' => 'empty_lists_cache', '_wpnonce' => wp_create_nonce( '_mc4wp_action' ) ) ) ) ); ?></p>
		</div>

		<?php $config = array( 'element' => $this->name_attr( 'enabled' ), 'value' => 0 ); ?>
		<div id="message-bar-is-disabled" class="notice notice-warning" data-showif="<?php echo esc_attr( json_encode( $config ) ); ?>">
			<p>
				<?php esc_html_e( 'You have disabled the bar. It will not show up on your site until you enable it again.', 'mailchimp-top-bar' ); ?>
			</p>
		</div>

		<!-- Bar Settings -->
		<div class="mc4wp-tab <?php if( $current_tab === 'settings' ) echo 'mc4wp-tab-active'; ?>" id="tab-settings">

			<h2><?php esc_html_e( 'Bar Settings', 'mailchimp-for-wp'); ?></h2>

			<table class="form-table">

				<tr valign="top">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Enable Bar?', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'enabled' ); ?>" value="1" <?php checked( $options[ 'enabled' ], 1 ); ?> /> <?php esc_html_e( 'Yes' ); ?>
						</label> &nbsp;
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'enabled' ); ?>" value="0" <?php checked( $options[ 'enabled' ], 0 ); ?> /> <?php esc_html_e( 'No' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'A quick way to completely disable the bar.', 'mailchimp-top-bar' ); ?></p>

					</td>
				</tr>

				<tr valign="top">
					<th scope="mc4wp-row"><label><?php esc_html_e( 'Mailchimp Audience', 'mailchimp-for-wp' ); ?></label></th>
					<td>
						<?php if( empty( $lists ) ) {
							printf(wp_kses(__( 'No audiences found, <a href="%s">are you connected to Mailchimp</a>?', 'mailchimp-for-wp'), array('a' => array('href' => array()))), admin_url( 'admin.php?page=mailchimp-for-wp' ) ); ?>
						<?php } ?>

						<select name="<?php echo $this->name_attr( 'list' ); ?>" class="mc4wp-list-input" id="select-mailchimp-list">
							<option disabled <?php selected( $options[ 'list' ], '' ); ?>><?php esc_html_e( 'Select a list..', 'mailchimp-top-bar' ); ?></option>
							<?php foreach( $lists as $list ) { ?>
								<option value="<?php echo esc_attr( $list->id ); ?>" <?php selected( $options[ 'list' ], $list->id ); ?>><?php echo esc_html( $list->name ); ?></option>
							<?php } ?>
						</select>
						<p class="description"><?php esc_html_e( 'Select the Mailchimp audience to which visitors should be subscribed.' ,'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Bar Text', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'text_bar' ); ?>" value="<?php echo esc_attr( $options[ 'text_bar' ] ); ?>" class="widefat" />
						<p class="description"><?php esc_html_e( 'The text to appear before the email field.', 'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Button Text', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'text_button' ); ?>" value="<?php echo esc_attr( $options[ 'text_button' ] ); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e( 'The text on the submit button.', 'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>

				<tr valign="top">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Email Placeholder Text', 'mailchimp-top-bar' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'text_email_placeholder' ); ?>" value="<?php echo esc_attr( $options[ 'text_email_placeholder' ] ); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e( 'The initial placeholder text to appear in the email field.', 'mailchimp-top-bar' ); ?></p>
					</td>
				</tr>
			</table>
		</div>

		<!-- Appearance Tab -->
		<div class="mc4wp-tab <?php if( $current_tab === 'appearance' ) echo 'mc4wp-tab-active'; ?>" id="tab-appearance">

			<h2><?php esc_html_e( 'Appearance', 'mailchimp-top-bar' ); ?></h2>

			<div class="mc4wp-row">
				<div class="mc4wp-col mc4wp-col-2">
					<table class="form-table">

						<tr valign="top">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Bar Position', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<select name="<?php echo $this->name_attr( 'position' ); ?>" id="select-bar-position">
									<option value="top" <?php selected( $options[ 'position' ], 'top' ); ?>><?php esc_html_e( 'Top', 'mailchimp-top-bar' ); ?></option>
									<option value="bottom" <?php selected( $options[ 'position' ], 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'mailchimp-top-bar' ); ?></option>
								</select>
							</td>
						</tr>

						<tr valign="top" class="bar-size-options">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Bar Size', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<select name="<?php echo $this->name_attr( 'size' ); ?>">
									<option value="small" <?php selected( $options[ 'size' ], 'small' ); ?>><?php esc_html_e( 'Small', 'mailchimp-top-bar' ); ?></option>
									<option value="medium" <?php selected( $options[ 'size' ], 'medium' ); ?>><?php esc_html_e( 'Medium', 'mailchimp-top-bar' ); ?></option>
									<option value="big" <?php selected( $options[ 'size' ], 'big' ); ?>><?php esc_html_e( 'Big', 'mailchimp-top-bar' ); ?></option>
								</select>
							</td>
						</tr>

						<tr valign="top">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Bar Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_bar' ); ?>" value="<?php echo esc_attr( $options[ 'color_bar' ] ); ?>" class="mc4wp-color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Text Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_text' ); ?>" value="<?php echo esc_attr( $options[ 'color_text' ] ); ?>" class="mc4wp-color">
							</td>
						</tr>

					</table>
				</div>
				<div class="mc4wp-col mc4wp-col-2">
					<table class="form-table">

						<?php $config = array( 'element' =>  $this->name_attr( 'position' ), 'value' => 'top' ); ?>
						<tr valign="top" class="sticky-bar-options" data-showif="<?php echo esc_attr( json_encode( $config ) ); ?>">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Sticky Bar?', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<label>
									<input type="radio" name="<?php echo $this->name_attr( 'sticky' ); ?>" value="1" <?php checked( $options[ 'sticky' ], 1 ); ?> /> <?php esc_html_e( 'Yes' ); ?>
								</label> &nbsp;
								<label>
									<input type="radio" name="<?php echo $this->name_attr( 'sticky' ); ?>" value="0" <?php checked( $options[ 'sticky' ], 0 ); ?> /> <?php esc_html_e( 'No' ); ?>
								</label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Button Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_button' ); ?>" value="<?php echo esc_attr( $options[ 'color_button' ] ); ?>" class="mc4wp-color">
							</td>
						</tr>

						<tr valign="top">
							<th scope="mc4wp-row">
								<label>
									<?php esc_html_e( 'Button Text Color', 'mailchimp-top-bar' ); ?>
								</label>
							</th>
							<td>
								<input type="text" name="<?php echo $this->name_attr( 'color_button_text' ); ?>" value="<?php echo esc_attr( $options[ 'color_button_text' ] ); ?>" class="mc4wp-color">
							</td>
						</tr>

					</table>
				</div>
			</div>
			<br style="clear: both;" />
		</div>

		<!-- Form Messages -->
		<div class="mc4wp-tab <?php if( $current_tab === 'messages' ) echo 'mc4wp-tab-active'; ?>" id="tab-messages">

			<h2><?php esc_html_e( 'Messages', 'mailchimp-top-bar' ); ?></h2>

			<table class="form-table">
				<tr valign="top">
					<th scope="mc4wp-row"><label><?php esc_html_e( 'Success', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_subscribed'); ?>" placeholder="<?php echo esc_attr( $options[ 'text_subscribed' ] ); ?>"  value="<?php echo esc_attr( $options[ 'text_subscribed' ] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="mc4wp-row"><label><?php esc_html_e( 'Invalid email address', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_invalid_email'); ?>" placeholder="<?php echo esc_attr( $options[ 'text_invalid_email' ] ); ?>"  value="<?php echo esc_attr( $options[ 'text_invalid_email' ] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="mc4wp-row"><label><?php esc_html_e( 'Already subscribed', 'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_already_subscribed'); ?>" placeholder="<?php echo esc_attr( $options[ 'text_already_subscribed' ] ); ?>"  value="<?php echo esc_attr( $options[ 'text_already_subscribed' ] ); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="mc4wp-row"><label><?php esc_html_e( 'Other errors' ,'mailchimp-for-wp' ); ?></label></th>
					<td><input type="text" class="widefat" name="<?php echo $this->name_attr('text_error'); ?>" placeholder="<?php echo esc_attr( $options[ 'text_error' ] ); ?>"  value="<?php echo esc_attr( $options[ 'text_error' ] ); ?>" /></td>
				</tr>
				<tr>
					<th></th>
					<td>
						<p class="description"><?php printf(esc_html__('HTML tags like %s are allowed in the message fields.', 'mailchimp-for-wp' ), '<code>' . esc_html( '<strong><em><a>' ) . '</code>' ); ?></p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Redirect to URL after successful sign-ups', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="<?php echo $this->name_attr( 'redirect' ); ?>" placeholder="<?php echo esc_attr( $options[ 'redirect' ] ); ?>" value="<?php echo esc_attr( $options[ 'redirect' ] ); ?>" class="widefat" />
						<p class="description"><?php echo wp_kses(__( 'Leave empty for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.', 'mailchimp-for-wp'), array('code' => array())); ?></p>
					</td>
				</tr>

			</table>
		</div>

        <!-- Advanced -->
		<div class="mc4wp-tab <?php if( $current_tab === 'advanced' ) echo 'mc4wp-tab-active'; ?>" id="tab-advanced">
			<h2><?php esc_html_e( 'Advanced', 'mailchimp-top-bar' ); ?></h2>

			<table class="form-table">
				<tr valign="top" class="double-optin-options">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Double opt-in?', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'double_optin' ); ?>" value="1" <?php checked( $options[ 'double_optin' ], 1 ); ?> /> <?php esc_html_e( 'Yes' ); ?>
						</label> &nbsp;
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'double_optin' ); ?>" value="0" <?php checked( $options[ 'double_optin' ], 0 ); ?> /> <?php esc_html_e( 'No' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Select "yes" if you want people to confirm their email address before being subscribed (recommended)', 'mailchimp-for-wp' ); ?>
						</p>
					</td>
				</tr>

				<?php if( ! class_exists( 'MC4WP_API_V3' ) ) { ?>
					<?php $config = array( 'element' => $this->name_attr( 'double_optin' ), 'value' => 0 ); ?>
					<tr valign="top" class="send-welcome-options" data-showif="<?php echo esc_attr( json_encode( $config ) ); ?>">
						<th scope="mc4wp-row">
							<label>
								<?php esc_html_e( 'Send Welcome Email?', 'mailchimp-for-wp' ); ?>
							</label>
						</th>
						<td>
							<label>
								<input type="radio" name="<?php echo $this->name_attr( 'send_welcome' ); ?>" value="1" <?php checked( $options[ 'send_welcome' ], 1 ); ?> /> <?php esc_html_e( 'Yes' ); ?>
							</label> &nbsp;
							<label>
								<input type="radio" name="<?php echo $this->name_attr( 'send_welcome' ); ?>" value="0" <?php checked( $options[ 'send_welcome' ], 0 ); ?> /> <?php esc_html_e( 'No' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Select "yes" if you want to send your lists Welcome Email if a subscribe succeeds (only when double opt-in is disabled).', 'mailchimp-for-wp' ); ?>
							</p>
						</td>
					</tr>
				<?php } // end if MC4WP_API_v3 exists ?>

				<tr valign="top">
					<th scope="mc4wp-row">
						<label>
							<?php esc_html_e( 'Update existing subscribers?', 'mailchimp-for-wp' ); ?>
						</label>
					</th>
					<td>
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'update_existing' ); ?>" value="1" <?php checked( $options[ 'update_existing' ], 1 ); ?> /> <?php esc_html_e( 'Yes' ); ?>
						</label> &nbsp;
						<label>
							<input type="radio" name="<?php echo $this->name_attr( 'update_existing' ); ?>" value="0" <?php checked( $options[ 'update_existing' ], 0 ); ?> /> <?php esc_html_e( 'No' ); ?>
						</label>
						<p class="description">
							<?php esc_html_e( 'Select "yes" if you want to update existing subscribers.', 'mailchimp-for-wp' ); ?>
							<?php printf(wp_kses(__( 'This is really only useful if you have <a href="%s">added additional fields (besides just email)</a>.', 'mailchimp-top-bar' ), array('a' => array('href' => array()))), 'https://www.mc4wp.com/kb/add-name-field-to-mailchimp-top-bar/' ); ?>
						</p>
					</td>
				</tr>

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e( 'Stop loading bar after it is used?', 'mailchimp-top-bar' ); ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="<?php echo $this->name_attr( 'disable_after_use' ); ?>" value="1" <?php checked( $options[ 'disable_after_use' ], 1 ); ?> /> <?php esc_html_e( 'Yes' ); ?>
                        </label> &nbsp;
                        <label>
                            <input type="radio" name="<?php echo $this->name_attr( 'disable_after_use' ); ?>" value="0" <?php checked( $options[ 'disable_after_use' ], 0 ); ?> /> <?php esc_html_e( 'No' ); ?>
                        </label>
                        <p class="description">
                            <?php esc_html_e( 'Select "yes" if you want to completely stop loading the bar after it is successfully used to subscribe.', 'mailchimp-for-wp' ); ?>
                        </p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e( 'Do not show on pages', 'mailchimp-top-bar' ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo $this->name_attr( 'disable_on_pages' ); ?>" value="<?php echo esc_attr( $options[ 'disable_on_pages' ] ); ?>" class="regular-text" placeholder="<?php esc_html_e('Example: checkout, contact'); ?>" />
                        <p class="description"><?php esc_html_e( "Enter a comma separated list of pages to hide the bar on. Accepts page ID's or slugs.", 'mailchimp-top-bar' ); ?></p>
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
