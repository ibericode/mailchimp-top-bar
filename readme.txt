=== MailChimp Top Bar ===
Contributors: DvanKooten, iMazed
Donate link: https://dannyvankooten.com/donate/
Tags: mailchimp,top bar,opt-in,sign-up,subscribe,conversion,call to action
Requires at least: 3.8
Tested up to: 4.1.1
Stable tag: 1.0.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a MailChimp opt-in form in a top bar to your WordPress site.

== Description ==

Adds a beautiful, customizable opt-in bar to the top of your WordPress site. This bar is guaranteed to get the attention of your visitor and
increase your MailChimp subscribers.

> This plugin is an add-on for the [MailChimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/).
> To use it, you need at least **MailChimp for WordPress v2.2.3** or **MailChimp for WordPress Premium v2.5.5**.

= MailChimp Top Bar, at a glance.. =

MailChimp Top Bar adds a simple yet beautiful and customizable opt-in bar to the top of your WordPress site.

Using this bar, people can subscribe to any MailChimp list of your choice.

- A real conversion booster.
- Unobtrusive, visitors can dismiss the bar.
- Easy to install & configure, just select a MailChimp list and you're good to.
- Customizable, you can edit the top bar text and colors from the plugin settings.
- Lightweight, this plugin aims to do one thing, and does it well.

= Development of MailChimp Top Bar =

Bug reports (and Pull Requests) for [MailChimp Top Bar are welcomed on GitHub](https://github.com/dannyvankooten/wp-mailchimp-top-bar). Please note that GitHub is _not_ a support forum.

**More information**

- [MailChimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/)
- Developers; follow or contribute to the [MailChimp Top Bar plugin on GitHub](https://github.com/dannyvankooten/wp-mailchimp-top-bar)
- Other [WordPress plugins](https://dannyvankooten.com/wordpress-plugins/#utm_source=wp-plugin-repo&utm_medium=link&utm_campaign=more-info-link) by [Danny van Kooten](https://dannyvankooten.com#utm_source=wp-plugin-repo&utm_medium=link&utm_campaign=more-info-link)
- [@DannyvanKooten](https://twitter.com/dannyvankooten) on Twitter

== Installation ==

= MailChimp for WordPress =

Since this plugin depends on the [MailChimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/), you will need to install that first.

= Installing MailChimp Top Bar =

1. In your WordPress admin panel, go to *Plugins > New Plugin*, search for **MailChimp Top Bar** and click "*Install now*"
1. Alternatively, download the plugin and upload the contents of `mailchimp-top-bar.zip` to your plugins directory, which usually is `/wp-content/plugins/`.
1. Activate the plugin
1. Set [your MailChimp API key](https://admin.mailchimp.com/account/api) in **MailChimp for WP > MailChimp Settings**.
1. Select a list to subscribe to in **MailChimp for WP > Top Bar**.
1. Customize the look of your top bar

== Frequently Asked Questions ==

= How to disable the bar on certain pages? =

For now, you will have to use a filter to disable the bar on certain pages.

`
add_filter( 'mctp_show_bar', function() {
	// this will only show the bar on blog post pages.
	return is_singular( 'post' );
} );
`

Have a look at the [Conditional Tags](https://codex.wordpress.org/Conditional_Tags) page for all accepted functions.

= I think I found a bug. What now? =

Please report it on [GitHub issues](https://github.com/dannyvankooten/wp-mailchimp-top-bar/issues) if it's not in the list of known issues.

= I have another question =

Please open a topic on the [WordPress.org plugin support forums](https://wordpress.org/support/plugin/mailchimp-top-bar).


== Screenshots ==

1. The MailChimp Top Bar in action on the [MailChimp for WordPress site](https://mc4wp.com).
2. The settings page of the MailChimp Top Bar plugin.

== Changelog ==

= 1.0.5 - February 25, 2015 =

**Fixes**

- Bar not loading in some themes after latest update
- Colors not working because of missing leading `#` value. Color settings are now validated before saving them.

= 1.0.4 - February 23, 2015 =

**Fixes**

- Styling issues with Enfold theme.

**Additions**

- Settings page now uses a tabbed interface.
- You can now set a "redirect url" in the bar settings
- All form response messages can now be customised for the bar form

= 1.0.3 - February 17, 2015 =

**Improvements**

- Bar will now show "already subscribed" message from MailChimp for WordPress when a person is already on the selected list.
- Response message will now show and fadeout after 3 seconds.
- Various usability improvements for the settings screen.
- Improved spam detection.
- Major JS performance improvements.

**Additions**

- Multiple new anti-spam measures
- WPML compatibility


= 1.0.2 - February 12, 2015 =

**Improvements**

- Better CSS reset for elements inside the bar
- Other minor CSS improvements

**Additions**

- Top Bar sign-ups are now shown in the log for [MailChimp for WordPress Pro](https://mc4wp.com/).

= 1.0.1 - February 4, 2015 =

**Fixes**

- The plugin will no longer overlap header menu's or other elements

**Additions**

- You can now set the bar as "sticky", meaning it will stick to the op your window, even when scrolling.
- You can now choose the size of the bar, small/medium/big.
- Added Dutch translation files.

**Improvements**

- The menu item will now show above the item asking you to upgrade to MailChimp for WordPress Pro.

Please update the [MailChimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/) before updating to this version.

= 1.0 - January 28, 2015 =

Initial release

== Upgrade Notice ==

= 1.0.5 =

Bugfix release, fixes issue where the bar did not appear in some themes.