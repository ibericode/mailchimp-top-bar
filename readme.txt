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

Mailchimp Top Bar is a plugin by [ibericode](https:/www.ibericode.com/), a company from The Netherlands that you may know from other plugins like [Mailchimp for WordPress](https://www.mc4wp.com/), [Boxzilla Pop-ups](https://www.boxzillaplugin.com) and [Koko Analytics](https://www.kokoanalytics.com/).

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


= 1.7.4 =

- Bump required WordPress version to 7.4 or higher.
- Modernize code base by using latest PHP features and removing legacy compatibility code.


= 1.7.3 =

- Minor performance or memory usage related improvements.
- Compatibility check with latest WordPress version.


= 1.7.1 =

- Update dependencies and WordPress compatibility.
- Decrease timestamp check to one second ago.


= 1.7.0 =

- Bump required PHP version to 7.3 or higher.
- Bump required WordPress version to 4.9 or higher.
- Remove compatibility code for Mailchimp for WordPress versions before 3.0.
- Add visitor IP to sign-ups through Top Bar.
- Fix response not showing up after first trying with an invalid email address.
- Minor performance improvements troughout the code by explicitly specifying the global namespace on core PHP functions.


= 1.6.2 =

- Fix button text setting not updating after saving settings.


= 1.6.1 =

- Escape return value of `add_query_arg` before outputting, fixing a potential XSS issue. Thanks to vgo0 for the responsible disclosure.
- Escape or kses return values of all gettext calls.
- Improved sanitization of all plugin settings.
- Minor server side performance improvements by getting rid of some unneccessary string copies or sprintf calls.


= 1.6.0 =

- JS file now has `defer` attribute so it is not render blocking.
- Stylesheet is now inserted through JS, so it is not render blocking.
- Animations now entirely handled using CSS.
- JS file is now 20% smaller because of the above (2.6 kB gzipped).


= 1.5.6 =

- Minor JS improvements to shrink ~500 bytes off script file.
- Prepare admin tab navigation for upcoming [Mailchimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/) release.


= 1.5.5 =

- Always use minified asset file, regardless of `SCRIPT_DEBUG` setting.
- Add nonce to all URL's using `_mc4wp_action` parameter.


= 1.5.4 =

- Update classnames to work with MailChimp for WordPress version 4.8.4 (and up).
- Minor JS optimizations.


= 1.5.3 =

- Fix typo in help text.
- Show bar server-side to speed-up height calculation.


= 1.5.2 =

- Add setting to disable bar (stop loading it altogether) after it is used.
- Increase default cookie lifetime to 1 year.


= 1.5.1 =

- Fade response element using CSS animations for better performance.
- Various minor performance improvements.


= 1.5.0 =

Compatibility with [Mailchimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/) version 4.6.


= 1.4.1 =

**Changes**

- Change name to MC4WP: Mailchimp Top Bar.


= 1.4.0 =

**Improvements**

- Add (advanced) setting to quickly disable the top bar on certain pages.


= 1.3.2 =

**Fixes**

- Required fields notice on selected list was not showing because of invalid list property.

**Improvements**

- Prefix internal CSS classes for improved compatibility with other themes or plugins applying global admin styles.


= 1.3.1 =

**Improvements**

- 30% reduction in script file size because of removed JS dependency.
- Stop setting unused cookie when Top Bar form is used to subscribe.
- Add mctb_after_submit_button action hook.
- Improve animation performance.


= 1.3 =

**Improvements**

- Form now submits over AJAX, no longer reloading the entire page.
- Added `for` attribute to label elements, thanks [gabriel-kaam](https://github.com/gabriel-kaam).
- Added `mctb_replace_interests` filter hook.

= 1.2.16 =

Various minor code improvements.


= 1.2.15 =

**Improvements**

- Improved responsiveness when bar has additional input fields.
- Add `required` attribute to email input.


= 1.2.14 =

**Fixes**

- Top padding for small screens with admin bar.

**Improvements**

- Better bar responsiveness when window dimensions change on the fly (eg resizing a window or changing device orientation mode). (Thanks [tech4him1](https://github.com/tech4him1)!)


= 1.2.13 =

**Fixes**

- Error in animating body padding back to its original value.


= 1.2.12 =

**Fixes**

- Bar would crash when clicking toggle icon during bar animation.

**Improvements**

- Function scope generated JavaScript file to prevent Browserify clashes with other loaded scripts.
- Make sure script works even though it's loaded in the head section.
- Preparations for upcoming Mailchimp for WordPress v4.0 release.

**Additions**

- Added Spanish language files, thanks to [Ángel Guzmán Maeso](http://shakaran.net/)
- Added `mctb_data` filter, to filter form data before it is processed.

**Deprecated**

- Deprecated `mctb_merge_vars` filter.


= 1.2.11 =

**Improvements**

- Completely removed optional jQuery dependency. The plugin now uses JavaScript animations, resulting in a much smoother experience.

= 1.2.10 =

**Fixes**

- Closed bar would still overlap underlying elements (like fixed top menu's).


= 1.2.9 =

**Fixes**

Top Bar was invisible on some themes because of `z-index` being too low.


= 1.2.8 =

**Improvements**

- Make sure top bar doesn't appear on top of WP admin bar.
- Hardened CSS styles for improved theme compatability.


= 1.2.7 =

**Improvements**

- Miscellaneous code improvements

**Additions**

- Add support for new [debug log](https://www.mc4wp.com/kb/how-to-enable-log-debugging/) in Mailchimp for WordPress 3.1


= 1.2.6 =

 **Additions**

 - Option to "update existing subscribers" in Mailchimp, which is useful if you have added fields.

 **Improvements**

 - Toggle icon now has a background color, for increased visibility.
 - Toggle icon now stacks above or below bar on small screens.

= 1.2.5 =

The plugin now requires [Mailchimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/) version 3.0 or higher.

**Fixes**

- Fixed column alignment in Appearance tab, thanks [Chantal Coolsma](https://github.com/chantalcoolsma)!

**Improvements**

- Improved admin notice when dependencies are not installed.


= 1.2.4 =

- Compatibility for [the upcoming Mailchimp for WordPress 3.0 release](https://www.mc4wp.com/blog/breaking-backwards-compatibility-in-version-3-0/) tomorrow.
- Added `mctb_subscribed` filter.

= 1.2.3 =

**Improvements**

- Minor refactoring in the way the plugin is bootstrapped.

= 1.2.2 =

**Fixes**

- Honeypot field being auto-completed in some browsers.
- Honeypot field was accessible by pressing "tab" key.
- Hardened security for cookie that tracks sign-up attempts.

= 1.2.1 =

**Fixes**

- Response message was not showing for some themes.

**Improvements**

- Better mobile responsiveness


= 1.2 =

**Improvements**

- The bar will now auto-dismiss after every successful sign-up.
- Placeholders will now work in Internet Explorer 7, 8 & 9 as well.

**Additions**

- Added options for double opt-in and sending Mailchimp's "welcome email".
- Added `mctb_before_label` action allowing you to add HTML before the label-element.
- Added `mctb_before_email_field` action allowing you to add HTML before the email field.
- Added `mctb_before_submit_button` action allowing you to add HTML before the submit button.
- Added `mctb_form_action` filter allowing you to set a custom form action.

= 1.1.3 =

**Fixes**

- Fixes fatal error when visiting settings page on some servers

= 1.1.2 =

**Improvements**

- Fixes height of response message
- CSS improvements for compatibility with various popular themes

= 1.1.1 =

**Fixes**

- Fixes unclickable admin bar (or fixed navigation menu's).

**Improvements**

- Various improvements to bar CSS so it can be easily overridden.
- Fix vertical alignment of toggle icon.

= 1.1 =

**Improvements**

- Bar no longer requires jQuery script, saving an additional HTTP request and 100kb

**Additions**

- Position option: top or bottom
- New filter: `mctb_mailchimp_list` (set lists to subscribe to)
- Lithuanian translation, thanks to [Aleksandr Charkov](https://github.com/dec0n)

= 1.0.8 =

**Fixes**

- Compatibility with [Mailchimp for WordPress Lite v2.3](https://wordpress.org/plugins/mailchimp-for-wp/) and [Mailchimp for WordPress Pro v2.7](https://www.mc4wp.com/).

= 1.0.7 =

**Fixes**

- `mctb_show_bar` filter was not functioning properly with some themes.
- Form always errored when using WPML with String Translations.

**Improvements**

- Toggle icon is no longer shown for users without JavaScript.

= 1.0.6 =

**Fixes**

- Compatibility issues with latest version of Enfold theme
- Conflict with other plugins shipping _very old_ versions of Composer

**Improvements**

- Allow simple inline tags in the bar text


= 1.0.5 =

**Fixes**

- Bar not loading in some themes after latest update
- Colors not working because of missing leading `#` value. Color settings are now validated before saving them.

= 1.0.4 =

**Fixes**

- Styling issues with Enfold theme.

**Additions**

- Settings page now uses a tabbed interface.
- You can now set a "redirect url" in the bar settings
- All form response messages can now be customised for the bar form

= 1.0.3 =

**Improvements**

- Bar will now show "already subscribed" message from Mailchimp for WordPress when a person is already on the selected list.
- Response message will now show and fadeout after 3 seconds.
- Various usability improvements for the settings screen.
- Improved spam detection.
- Major JS performance improvements.

**Additions**

- Multiple new anti-spam measures
- WPML compatibility


= 1.0.2 =

**Improvements**

- Better CSS reset for elements inside the bar
- Other minor CSS improvements

**Additions**

- Top Bar sign-ups are now shown in the log for [Mailchimp for WordPress Pro](https://www.mc4wp.com/).

= 1.0.1 =

**Fixes**

- The plugin will no longer overlap header menu's or other elements

**Additions**

- You can now set the bar as "sticky", meaning it will stick to the op your window, even when scrolling.
- You can now choose the size of the bar, small/medium/big.
- Added Dutch translation files.

**Improvements**

- The menu item will now show above the item asking you to upgrade to Mailchimp for WordPress Pro.

Please update the [Mailchimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/) before updating to this version.

= 1.0 =

Initial release

== Upgrade Notice ==

= 1.2.6 =

Improvements to toggle icon for small screens & dark backgrounds.
