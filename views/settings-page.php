<?php

if (! defined('ABSPATH')) {
    exit;
}

$tabs = [
    "settings" => esc_html__("Bar Setting", "mailchimp-top-bar"),
    "appearance" => esc_html__("Appearance", "mailchimp-top-bar"),
    "messages" => esc_html__("Messages", "mailchimp-top-bar"),
    "advanced" => esc_html__("Advanced", "mailchimp-top-bar"),
];

/* ensure wp-picker does not push other elements around */
?>
<style type="text/css">
.wp-picker-holder,
.wp-picker-input-wrap {
    position: absolute;
}
</style>
<div class="wrap" id="mc4wp-admin">

    <h1 class="page-title">Mailchimp Top Bar</h1>

    <h2 class="nav-tab-wrapper" id="mctb-tabs">
        <?php foreach ($tabs as $tab => $title) {
            $class = $current_tab === $tab ? "nav-tab-active" : "";
            echo sprintf(
                '<a class="nav-tab nav-tab-%s %s" data-tab="%s" href="%s">%s</a>',
                esc_attr($tab),
                esc_attr($class),
                esc_attr($tab),
                esc_url(
                    admin_url(
                        "admin.php?page=mailchimp-for-wp-top-bar&tab=" . $tab,
                    ),
                ),
                esc_html($title),
            );
        } ?>
    </h2>

    <form method="post" action="<?php echo esc_url(admin_url("options.php")); ?>">

        <h2 style="display: none;"></h2>
        <?php settings_fields("mailchimp_top_bar"); ?>
        <?php settings_errors(); ?>
     
        <div id="message-list-requires-fields" class="notice notice-warning" style="display: none;">
            <p><?php printf(
                wp_kses(
                    /* translators: 1: Merge field tag, 2: Mailchimp audience URL, 3: Merge field tag. */
                    __(
                        'The selected Mailchimp audience requires more fields than just an <strong>%1$s</strong> field. Please <a href="%2$s">log into your Mailchimp account</a> and make sure only the <strong>%3$s</strong> field is marked as required.',
                        "mailchimp-top-bar",
                    ),
                    ["a" => ["href" => []], "strong" => []],
                ),
                "EMAIL",
                esc_url("https://admin.mailchimp.com/audience/"),
                "EMAIL",
            ); ?></p>
            <p class="description"><?php printf(
                wp_kses(
                    /* translators: %s: URL to refresh cached Mailchimp list configuration. */
                    __(
                        'After making changes to your Mailchimp audience, <a href="%s">click here</a> to renew your list configuration.',
                        "mailchimp-top-bar",
                    ),
                    ["a" => ["href" => []]],
                ),
                esc_url(
                    add_query_arg([
                        "_mc4wp_action" => "empty_lists_cache",
                        "_wpnonce" => wp_create_nonce("_mc4wp_action"),
                    ]),
                ),
            ); ?></p>
        </div>

        <?php $config = [
            "element" => $this->name_attr("enabled"),
            "value" => 0,
        ]; ?>
        <div id="message-bar-is-disabled" class="notice notice-warning" data-showif="<?php echo esc_attr(
            json_encode($config),
        ); ?>">
            <p>
                <?php esc_html_e(
                    "You have disabled the bar. It will not show up on your site until you enable it again.",
                    "mailchimp-top-bar",
                ); ?>
            </p>
        </div>

        <!-- Bar Settings -->
        <?php require __DIR__ . '/tab-settings.php'; ?>

        <!-- Appearance Tab -->
        <?php require __DIR__ . '/tab-appearance.php'; ?>

        <!-- Form Messages -->
        <?php require __DIR__ . '/tab-messages.php'; ?>

        <!-- Advanced -->
        <?php require __DIR__ . '/tab-advanced.php'; ?>

        <?php submit_button(); ?>
    </form>

    <?php do_action("mc4wp_admin_footer"); ?>
</div>