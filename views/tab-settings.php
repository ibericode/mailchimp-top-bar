<?php defined('ABSPATH') || exit; ?>

<div class="mc4wp-tab <?php echo ($current_tab === "settings") ? 'mc4wp-tab-active' : ''; ?>" id="tab-settings">
    <h2><?php esc_html_e("Bar Settings", "mailchimp-top-bar"); ?></h2>
    <table class="form-table">

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Enable Bar?",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "enabled",
                    )); ?>" value="1" <?php checked(
                        $options["enabled"],
                        1,
                    ); ?> /> <?php esc_html_e("Yes", "mailchimp-top-bar"); ?>
                </label> &nbsp;
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "enabled",
                    )); ?>" value="0" <?php checked(
                        $options["enabled"],
                        0,
                    ); ?> /> <?php esc_html_e("No", "mailchimp-top-bar"); ?>
                </label>
                <p class="description"><?php esc_html_e(
                    "A quick way to completely disable the bar.",
                    "mailchimp-top-bar",
                ); ?></p>

            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row"><label><?php esc_html_e("Mailchimp Audience", "mailchimp-top-bar"); ?></label></th>
            <td>
                <?php
                if (empty($lists)) :
                    printf(
                        wp_kses(
                            /* translators: %s: URL to Mailchimp for WP plugin settings page. */
                            __(
                                'No audiences found, <a href="%s">are you connected to Mailchimp</a>?',
                                "mailchimp-top-bar",
                            ),
                            ["a" => ["href" => []]],
                        ),
                        esc_url(admin_url("admin.php?page=mailchimp-for-wp")),
                    );
                endif;
                ?>

                <select name="<?php echo esc_attr($this->name_attr(
                    "list",
                )); ?>" class="mc4wp-list-input" id="select-mailchimp-list">
                    <option disabled <?php selected($options["list"], ""); ?>><?php esc_html_e("Select a list..", "mailchimp-top-bar"); ?></option>
                    <?php foreach ($lists as $list) { ?>
                        <option value="<?php echo esc_attr($list->id); ?>" <?php selected($options["list"], $list->id); ?>><?php echo esc_html($list->name); ?></option>
                    <?php } ?>
                </select>
                <p class="description"><?php esc_html_e("Select the Mailchimp audience to which visitors should be subscribed.", "mailchimp-top-bar"); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Bar Text",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo esc_attr($this->name_attr(
                    "text_bar",
                )); ?>" value="<?php echo esc_attr(
                    $options["text_bar"],
                ); ?>" class="widefat" />
                <p class="description"><?php esc_html_e(
                    "The text to appear before the email field.",
                    "mailchimp-top-bar",
                ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Button Text",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo esc_attr($this->name_attr(
                    "text_button",
                )); ?>" value="<?php echo esc_attr(
                    $options["text_button"],
                ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e(
                    "The text on the submit button.",
                    "mailchimp-top-bar",
                ); ?></p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Email Placeholder Text",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo esc_attr($this->name_attr(
                    "text_email_placeholder",
                )); ?>" value="<?php echo esc_attr(
                    $options["text_email_placeholder"],
                ); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e(
                    "The initial placeholder text to appear in the email field.",
                    "mailchimp-top-bar",
                ); ?></p>
            </td>
        </tr>
    </table>
</div>