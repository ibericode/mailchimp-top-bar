<?php
use MailChimp\TopBar\Plugin;

defined( 'ABSPATH' ) or exit;
?>
<div class="wrap" id="mc4wp-admin">

	<h1 style="line-height: 48px;">MailChimp Top Bar</h1>

	<form method="post" action="<?php echo admin_url( 'options.php' ); ?>">

		<?php settings_fields( Plugin::OPTION_NAME ); ?>

		<h2><?php _e( 'Bar Settings', 'mailchimp-for-wp'); ?></h2>

		<table class="form-table">

			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $this->name_attr( 'enabled' ); ?>">
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
			</tr>

			<tr valign="top">
				<th scope="row"><label for="<?php echo $this->name_attr( 'list' ); ?>"><?php _e( 'MailChimp List', 'mailchimp-for-wp' ); ?></label></th>
				<td>
					<?php if( empty( $lists ) ) {
						printf( __( 'No lists found, <a href="%s">are you connected to MailChimp</a>?', 'mailchimp-for-wp' ), admin_url( 'admin.php?page=mailchimp-for-wp' ) ); ?>
					<?php } ?>

					<select name="<?php echo $this->name_attr( 'list' ); ?>" class="widefat">
						<option disabled <?php selected( $this->options['list'], '' ); ?>><?php _e( 'Select a list..', 'mailchimp-top-bar' ); ?></option>
						<?php foreach( $lists as $list ) { ?>
							<option value="<?php echo esc_attr( $list->id ); ?>" <?php selected( $this->options['list'], $list->id ); ?>><?php echo esc_html( $list->name ); ?></option>
						<?php } ?>
					</select>

				</td>
				<td class="desc"><?php _e( 'Select the list to which visitors should be subscribed.' ,'mailchimp-top-bar' ); ?></td>
			</tr>

		</table>

		<h2><?php _e( 'Form Styling', 'mailchimp-for-wp'); ?></h2>

		<table class="form-table">

			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $this->name_attr( 'color_bar' ); ?>">
						<?php _e( 'Bar Color', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'color_bar' ); ?>" value="<?php echo esc_attr( $this->options['color_bar'] ); ?>" class="color">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $this->name_attr( 'color_text' ); ?>">
						<?php _e( 'Text Color', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'color_text' ); ?>" value="<?php echo esc_attr( $this->options['color_text'] ); ?>" class="color">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $this->name_attr( 'color_button' ); ?>">
						<?php _e( 'Button Color', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'color_button' ); ?>" value="<?php echo esc_attr( $this->options['color_button'] ); ?>" class="color">
				</td>
			</tr>

			<tr valign="top">
				<th scope="row">
					<label for="<?php echo $this->name_attr( 'color_button_text' ); ?>">
						<?php _e( 'Button Text Color', 'mailchimp-top-bar' ); ?>
					</label>
				</th>
				<td>
					<input type="text" name="<?php echo $this->name_attr( 'color_button_text' ); ?>" value="<?php echo esc_attr( $this->options['color_button_text'] ); ?>" class="color">
				</td>
			</tr>

		</table>

		<?php submit_button(); ?>
	</form>

</div>