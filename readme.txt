=== MC4WP: Mailchimp Top Bar – Email Subscribe Notification Bar ===
Contributors: Ibericode, DvanKooten, hchouhan, lapzor
Donate link: https://www.mc4wp.com/
Tags: mailchimp, notification bar, email signup, subscribe bar, top bar
Requires at least: 4.9
Tested up to: 7.0
Stable tag: 1.7.6
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 7.4

Add a customizable Mailchimp top bar that turns WordPress visitors into email subscribers without interrupting their browsing.

== Description ==

Grow your Mailchimp audience with a customizable email signup bar at the top or bottom of your WordPress site. The bar stays visible while visitors browse, helping your call to action get noticed without blocking your content.

Mailchimp Top Bar is an add-on for [MC4WP: Mailchimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/). It connects the bar to the Mailchimp audience of your choice.

== Features ==

* **Capture more subscribers:** Keep your email signup call to action visible while visitors browse.
* **Match your brand:** Customize the bar text, button text, and colors from the plugin settings.
* **Choose its position:** Display the subscribe bar at the top or bottom of the screen.
* **Let visitors dismiss it:** Visitors can hide the bar when they are not interested.
* **Keep pages fast:** The front-end JavaScript is under 2 kB when compressed.

== About the Plugin Author ==

[Danny van Kooten](https://www.dannyvankooten.com/) has been building WordPress plugins since 2010, starting with WordPress 3.0.

He is the founder of [ibericode](https://www.ibericode.com/), the small software company behind popular WordPress plugins including [Mailchimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/), [Boxzilla Pop-ups](https://wordpress.org/plugins/boxzilla/) and [Koko Analytics](https://wordpress.org/plugins/koko-analytics/).

== Installation ==

Mailchimp Top Bar requires the [MC4WP: Mailchimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/).

= Automatic installation =

1. In your WordPress dashboard, go to *Plugins > Add New*.
2. Search for **Mailchimp Top Bar**.
3. Click *Install Now*, then activate the plugin.
4. Go to **Mailchimp for WP > Mailchimp Settings** and enter [your Mailchimp API key](https://admin.mailchimp.com/account/api).
5. Go to **Mailchimp for WP > Top Bar** and select a Mailchimp audience.
6. Customize the text, colors, and position as needed.

= Manual installation =

1. Download the plugin ZIP file.
2. Go to *Plugins > Add New > Upload Plugin*.
3. Upload the ZIP file, install it, and activate the plugin.
4. Configure the API key and audience under **Mailchimp for WP**.

== Frequently Asked Questions ==

= Can I show the Mailchimp top bar on specific pages only? =

Yes. Use the `mctb_show_bar` filter to control where the bar appears. This example shows it on blog posts only:

`
add_filter( 'mctb_show_bar', function( $show ) {
    return is_single();
} );
`

This example shows it only on the contact page:

`
add_filter( 'mctb_show_bar', function( $show ) {
    return is_page( 'contact' );
} );
`

See the [WordPress Conditional Tags documentation](https://developer.wordpress.org/themes/basics/conditional-tags/) for other conditions.

= Can I capture names in the notification bar? =

Yes. Add a name field and pass its value to the selected Mailchimp audience:

`
add_action( 'mctb_before_submit_button', function() {
    echo '<input type="text" name="NAME" placeholder="Your name" />';
} );

add_filter( 'mctb_subscriber_data', function( $subscriber ) {
    if ( ! empty( $_POST['NAME'] ) ) {
        $subscriber->merge_fields['NAME'] = sanitize_text_field( $_POST['NAME'] );
    }

    return $subscriber;
} );
`

See [adding a name field to Mailchimp Top Bar](https://www.mc4wp.com/kb/add-name-field-to-mailchimp-top-bar/) for more details.

= How can I hide the email signup bar on mobile devices? =

Add this CSS to hide the bar on screens narrower than 600 pixels:

`
@media ( max-width: 600px ) {
    &#35;mailchimp-top-bar {
        display: none !important;
    }
}
`

= Does Mailchimp Top Bar affect site performance? =

The plugin keeps its front-end footprint small. Its JavaScript is under 2 kB when compressed, and the stylesheet loads without blocking page rendering.

= Where can I get support? =

Open a topic in the [WordPress.org support forum](https://wordpress.org/support/plugin/mailchimp-top-bar).

== Screenshots ==

1. A Mailchimp top bar capturing email signups without blocking the page content.
2. The settings page for customizing the Mailchimp notification bar colors, text, and behavior.

== Changelog ==

= 1.7.6 = 

- Improved email address validation.
- Address warnings reported by WordPress Coding Standards.

[View the full changelog on GitHub](https://github.com/ibericode/mailchimp-top-bar/blob/main/CHANGELOG.md)
