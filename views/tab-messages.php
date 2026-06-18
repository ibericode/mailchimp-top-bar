<?php defined('ABSPATH') || exit; ?>

<div class="mc4wp-tab <?php echo ($current_tab === "messages") ? 'mc4wp-tab-active' : ''; ?>" id="tab-messages">

    <h2><?php esc_html_e(
        "Messages",
        "mailchimp-top-bar",
    ); ?></h2>

    <table class="form-table">
        <tr valign="top">
            <th scope="mc4wp-row"><label><?php esc_html_e(
                "Success",
                "mailchimp-top-bar",
            ); ?></label></th>
            <td><input type="text" class="widefat" name="<?php echo esc_attr($this->name_attr(
                "text_subscribed",
            )); ?>" placeholder="<?php echo esc_attr(
                $options["text_subscribed"],
            ); ?>"  value="<?php echo esc_attr($options["text_subscribed"]); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="mc4wp-row"><label><?php esc_html_e(
                "Invalid email address",
                "mailchimp-top-bar",
            ); ?></label></th>
            <td><input type="text" class="widefat" name="<?php echo esc_attr($this->name_attr(
                "text_invalid_email",
            )); ?>" placeholder="<?php echo esc_attr(
                $options["text_invalid_email"],
            ); ?>"  value="<?php echo esc_attr($options["text_invalid_email"]); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="mc4wp-row"><label><?php esc_html_e(
                "Already subscribed",
                "mailchimp-top-bar",
            ); ?></label></th>
            <td><input type="text" class="widefat" name="<?php echo esc_attr($this->name_attr(
                "text_already_subscribed",
            )); ?>" placeholder="<?php echo esc_attr(
                $options["text_already_subscribed"],
            ); ?>"  value="<?php echo esc_attr(
                $options["text_already_subscribed"],
            ); ?>" /></td>
        </tr>
        <tr valign="top">
            <th scope="mc4wp-row"><label><?php esc_html_e(
                "Other errors",
                "mailchimp-top-bar",
            ); ?></label></th>
            <td><input type="text" class="widefat" name="<?php echo esc_attr($this->name_attr(
                "text_error",
            )); ?>" placeholder="<?php echo esc_attr(
                $options["text_error"],
            ); ?>"  value="<?php echo esc_attr($options["text_error"]); ?>" /></td>
        </tr>
        <tr>
            <th></th>
            <td>
                <p class="description"><?php printf(
                    /* translators: %s: List of allowed HTML tags. */
                    esc_html__(
                        "HTML tags like %s are allowed in the message fields.",
                        "mailchimp-top-bar",
                    ),
                    "<code>" . esc_html("<strong><em><a>") . "</code>",
                ); ?></p>
            </td>
        </tr>
        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Redirect to URL after successful sign-ups",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo esc_attr($this->name_attr(
                    "redirect",
                )); ?>" placeholder="<?php echo esc_attr(
                    $options["redirect"],
                ); ?>" value="<?php echo esc_attr($options["redirect"]); ?>" class="widefat" />
                <p class="description"><?php echo wp_kses(
                    __(
                        "Leave empty for no redirect. Otherwise, use complete (absolute) URLs, including <code>http://</code>.",
                        "mailchimp-top-bar",
                    ),
                    [
                        "code" => [],
                    ],
                ); ?></p>
            </td>
        </tr>

    </table>
</div>