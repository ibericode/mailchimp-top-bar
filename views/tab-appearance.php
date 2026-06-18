<?php defined('ABSPATH') || exit; ?>

<div class="mc4wp-tab <?php echo ($current_tab === "appearance") ? 'mc4wp-tab-active' : ''; ?>" id="tab-appearance">
    <h2><?php esc_html_e(
        "Appearance",
        "mailchimp-top-bar",
    ); ?></h2>

    <div class="mc4wp-row">
        <div class="mc4wp-col mc4wp-col-2">
            <table class="form-table">

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Bar Position",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <select name="<?php echo esc_attr($this->name_attr(
                            "position",
                        )); ?>" id="select-bar-position">
                            <option value="top" <?php selected(
                                $options["position"],
                                "top",
                            ); ?>><?php esc_html_e(
                                "Top",
                                "mailchimp-top-bar",
                            ); ?></option>
                            <option value="bottom" <?php selected(
                                $options["position"],
                                "bottom",
                            ); ?>><?php esc_html_e(
                                "Bottom",
                                "mailchimp-top-bar",
                            ); ?></option>
                        </select>
                    </td>
                </tr>

                <tr valign="top" class="bar-size-options">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Bar Size",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <select name="<?php echo esc_attr($this->name_attr(
                            "size",
                        )); ?>">
                            <option value="small" <?php selected(
                                $options["size"],
                                "small",
                            ); ?>><?php esc_html_e(
                                "Small",
                                "mailchimp-top-bar",
                            ); ?></option>
                            <option value="medium" <?php selected(
                                $options["size"],
                                "medium",
                            ); ?>><?php esc_html_e(
                                "Medium",
                                "mailchimp-top-bar",
                            ); ?></option>
                            <option value="big" <?php selected(
                                $options["size"],
                                "big",
                            ); ?>><?php esc_html_e(
                                "Big",
                                "mailchimp-top-bar",
                            ); ?></option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Bar Color",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo esc_attr($this->name_attr(
                            "color_bar",
                        )); ?>" value="<?php echo esc_attr(
                            $options["color_bar"],
                        ); ?>" class="mc4wp-color">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Text Color",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo esc_attr($this->name_attr(
                            "color_text",
                        )); ?>" value="<?php echo esc_attr(
                            $options["color_text"],
                        ); ?>" class="mc4wp-color">
                    </td>
                </tr>

            </table>
        </div>
        <div class="mc4wp-col mc4wp-col-2">
            <table class="form-table">

                <?php $config = [
                    "element" => $this->name_attr("position"),
                    "value" => "top",
                        ]; ?>
                <tr valign="top" class="sticky-bar-options" data-showif="<?php echo esc_attr(
                    json_encode($config),
                ); ?>">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Sticky Bar?",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="radio" name="<?php echo esc_attr($this->name_attr(
                                "sticky",
                            )); ?>" value="1" <?php checked(
                                $options["sticky"],
                                1,
                            ); ?> /> <?php esc_html_e("Yes", "mailchimp-top-bar"); ?>
                        </label> &nbsp;
                        <label>
                            <input type="radio" name="<?php echo esc_attr($this->name_attr(
                                "sticky",
                            )); ?>" value="0" <?php checked(
                                $options["sticky"],
                                0,
                            ); ?> /> <?php esc_html_e("No", "mailchimp-top-bar"); ?>
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Button Color",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo esc_attr($this->name_attr(
                            "color_button",
                        )); ?>" value="<?php echo esc_attr(
                            $options["color_button"],
                        ); ?>" class="mc4wp-color">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="mc4wp-row">
                        <label>
                            <?php esc_html_e(
                                "Button Text Color",
                                "mailchimp-top-bar",
                            ); ?>
                        </label>
                    </th>
                    <td>
                        <input type="text" name="<?php echo esc_attr($this->name_attr(
                            "color_button_text",
                        )); ?>" value="<?php echo esc_attr(
                            $options["color_button_text"],
                        ); ?>" class="mc4wp-color">
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>