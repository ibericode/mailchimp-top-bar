=== MC4WP: Mailchimp Top Bar – Email Subscribe Notification Bar ===
Contributors: Ibericode, DvanKooten, hchouhan, lapzor
Donate link: https://www.mc4wp.com/
Tags: mailchimp, notification bar, top bar, call to action, subscribe bar
Requires at least: 4.9
Tested up to: 7.0
Stable tag: 1.7.5
License: GPL-3.0-or-later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires PHP: 7.4

Boost email signups with a customizable Mailchimp top bar. Display an unobtrusive notification bar to capture leads and grow your audience.

== Description ==

Boost your email signups and grow your audience effortlessly with a Mailchimp subscribe bar. This plugin adds a beautiful, customizable notification bar to the top or bottom of your WordPress site, ensuring your call to action gets noticed without disrupting the user experience.

As an official add-on for the popular [Mailchimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/), it integrates seamlessly with your existing setup to help you capture more leads.

== Why Use a Mailchimp Top Bar? ==

A notification bar is one of the most effective ways to increase your conversion rates. Because it sticks to the top or bottom of the screen, it remains visible as visitors browse your content. 

* **Grow your audience:** Capture visitor attention immediately and boost your email list conversions.
* **Unobtrusive user experience:** Visitors can easily dismiss the email capture bar if they aren't interested.
* **Seamless integration:** Subscribes users directly to the Mailchimp audience of your choice.

== Simple Customization ==

You don't need to be a developer to make the Mailchimp top bar match your brand. 

* **Easy to configure:** Just select your Mailchimp audience and you are ready to go.
* **Visual customization:** Edit the bar text, button text, and colors directly from the plugin settings.
* **Flexible positioning:** Display the subscribe bar at either the top or bottom of the visitor's screen.
* **Lightweight performance:** The plugin is highly optimized, consisting of just a single 2.6 kB JavaScript file, ensuring your site remains fast.

== More Information ==

* [MC4WP: Mailchimp for WordPress on WordPress.org](https://wordpress.org/plugins/mailchimp-for-wp/)
* [MC4WP: Mailchimp for WordPress website](https://www.mc4wp.com/)

== About the Author ==

Mailchimp Top Bar is a plugin by [ibericode](https://www.ibericode.com/), a company from The Netherlands that you may know from other plugins like [Mailchimp for WordPress](https://www.mc4wp.com/), [Boxzilla Pop-ups](https://www.boxzillaplugin.com) and [Koko Analytics](https://www.kokoanalytics.com/).

== Installation ==

= Mailchimp for WordPress =

Since this plugin depends on the [Mailchimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/), you will need to install that first.

= Installing Mailchimp Top Bar =

1. In your WordPress admin panel, go to *Plugins > New Plugin*, search for **Mailchimp Top Bar** and click "*Install now*"
1. Alternatively, download the plugin and upload the contents of `mailchimp-top-bar.zip` to your plugins directory, which usually is `/wp-content/plugins/`.
1. Activate the plugin
1. Set [your Mailchimp API key](https://admin.mailchimp.com/account/api) in **Mailchimp for WP > Mailchimp Settings**.
1. Select a Mailchimp audience to subscribe to in **Mailchimp for WP > Top Bar**.
1. _(Optional)_ Customize the look & position of your opt-in bar.

== Frequently Asked Questions ==

= Can I use this Mailchimp subscribe bar on specific pages only? =

Yes, you can easily disable the notification bar on certain pages using a filter. For example, if you only want to load the Top Bar on your blog post pages, you can add this to your theme:

`
add_filter( 'mctb_show_bar', function( $show ) {
    return is_single();
} );
`

Another example, this only loads the email capture bar on your "contact" page:

`
add_filter( 'mctb_show_bar', function( $show ) {
    return is_page('contact');
} );
`

Have a look at the [Conditional Tags](https://codex.wordpress.org/Conditional_Tags) page for all accepted functions.

= Is it possible to capture names in the notification bar? =

Absolutely! You can add a name field to your Mailchimp top bar by using a simple code snippet to display the field and send the data to your audience.

`
add_action( 'mctb_before_submit_button', function() {
    echo '<input type="text" name="NAME" placeholder="Your name" />';
});

add_filter( 'mctb_subscriber_data', function( $subscriber ) {
    if( ! empty( $_POST['NAME'] ) ) {
        $subscriber->merge_fields['NAME'] = sanitize_text_field( $_POST['NAME'] );
    }

    return $subscriber;
});
`

For more details, see our knowledge base article on [adding a name field to Mailchimp Top Bar](https://www.mc4wp.com/kb/add-name-field-to-mailchimp-top-bar/).

= How can I hide the email capture bar on mobile devices? =

If you prefer not to show the notification bar on smaller screens, you can hide it using custom CSS. Adding the following CSS to your site will hide the bar on all screens smaller than 600px:

`
@media( max-width: 600px ) {
    &#35;mailchimp-top-bar { display: none !important; }
}
`

= Where can I get support for this Mailchimp form? =

If you run into any issues or have questions about configuring your subscribe bar, please open a topic on the [WordPress.org plugin support forums](https://wordpress.org/support/plugin/mailchimp-top-bar). 

== Screenshots ==

1. A sleek Mailchimp top bar in action, capturing email signups without disrupting the user experience.
2. The easy-to-use settings page where you can customize your subscribe notification bar colors and behavior.

== Changelog ==

= 1.7.5 =

- Allow WP Core to handle dependency on core Mailchimp for WordPress plugin.
- Minor defensive coding improvements.

[View the full changelog on GitHub](https://github.com/ibericode/mailchimp-top-bar/blob/main/CHANGELOG.md)
