<?php defined('ABSPATH') || exit; ?>

<div class="mc4wp-tab <?php echo ($current_tab === "advanced") ? 'mc4wp-tab-active' : ''; ?>" id="tab-advanced">
    <h2><?php esc_html_e(
        "Advanced",
        "mailchimp-top-bar",
    ); ?></h2>

    <table class="form-table">
        <tr valign="top" class="double-optin-options">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Double opt-in?",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "double_optin",
                    )); ?>" value="1" <?php checked(
                        $options["double_optin"],
                        1,
                    ); ?> /> <?php esc_html_e("Yes", "mailchimp-top-bar"); ?>
                </label> &nbsp;
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "double_optin",
                    )); ?>" value="0" <?php checked(
                        $options["double_optin"],
                        0,
                    ); ?> /> <?php esc_html_e("No", "mailchimp-top-bar"); ?>
                </label>
                <p class="description">
                    <?php esc_html_e(
                        'Select "yes" if you want people to confirm their email address before being subscribed (recommended)',
                        "mailchimp-top-bar",
                    ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Update existing subscribers?",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "update_existing",
                    )); ?>" value="1" <?php checked(
                        $options["update_existing"],
                        1,
                    ); ?> /> <?php esc_html_e("Yes", "mailchimp-top-bar"); ?>
                </label> &nbsp;
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "update_existing",
                    )); ?>" value="0" <?php checked(
                        $options["update_existing"],
                        0,
                    ); ?> /> <?php esc_html_e("No", "mailchimp-top-bar"); ?>
                </label>
                <p class="description">
                    <?php esc_html_e(
                        'Select "yes" if you want to update existing subscribers.',
                        "mailchimp-top-bar",
                    ); ?>
                    <?php printf(
                        wp_kses(
                            /* translators: %s: URL explaining how to add extra fields to top bar form. */
                            __(
                                'This is really only useful if you have <a href="%s">added additional fields (besides just email)</a>.',
                                "mailchimp-top-bar",
                            ),
                            ["a" => ["href" => []]],
                        ),
                        esc_url("https://www.mc4wp.com/kb/add-name-field-to-mailchimp-top-bar/"),
                    ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Stop loading bar after it is used?",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "disable_after_use",
                    )); ?>" value="1" <?php checked(
                        $options["disable_after_use"],
                        1,
                    ); ?> /> <?php esc_html_e("Yes", "mailchimp-top-bar"); ?>
                </label> &nbsp;
                <label>
                    <input type="radio" name="<?php echo esc_attr($this->name_attr(
                        "disable_after_use",
                    )); ?>" value="0" <?php checked(
                        $options["disable_after_use"],
                        0,
                    ); ?> /> <?php esc_html_e("No", "mailchimp-top-bar"); ?>
                </label>
                <p class="description">
                    <?php esc_html_e(
                        'Select "yes" if you want to completely stop loading the bar after it is successfully used to subscribe.',
                        "mailchimp-top-bar",
                    ); ?>
                </p>
            </td>
        </tr>

        <tr valign="top">
            <th scope="mc4wp-row">
                <label>
                    <?php esc_html_e(
                        "Do not show on pages",
                        "mailchimp-top-bar",
                    ); ?>
                </label>
            </th>
            <td>
                <input type="text" name="<?php echo esc_attr($this->name_attr(
                    "disable_on_pages",
                )); ?>" value="<?php echo esc_attr(
                    $options["disable_on_pages"],
                ); ?>" class="regular-text" placeholder="<?php esc_html_e("Example: checkout, contact", "mailchimp-top-bar"); ?>" />
                <p class="description"><?php esc_html_e(
                    "Enter a comma separated list of pages to hide the bar on. Accepts page ID's or slugs.",
                    "mailchimp-top-bar",
                ); ?></p>
            </td>
        </tr>
    </table>
</div>