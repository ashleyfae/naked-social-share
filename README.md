# Naked Social Share

Simple, unstyled social share icons for theme designers.

Naked Social Share allows you to insert plain, unstyled social share buttons for Twitter, Facebook, Pinterest, StumbleUpon, and Google+ after each post. The icons come with no styling, so that you -- the designer -- can style the buttons to match your theme.

There are a few simple options in the settings panel:

* Load default styles - This includes a simple stylesheet that applies a few bare minimum styles to the buttons.
* Load Font Awesome - Naked Social Share uses Font Awesome for the social share icons.
* Disable JavaScript - There is a small amount of JavaScript used to make the buttons open in a new popup window when clicked.
* Automatically add buttons - You can opt to automatically add the social icons below blog posts or pages.
* Twitter handle - Add your Twitter handle to include a "via @YourHandle" message in the Tweet.
* Social media sites - Change the order the buttons appear in and disable any you don't want.

If you want to display the icons manually in your theme, do so by placing this code inside your theme file where you want the icons to appear:

`<?php naked_social_share_buttons(); ?>`

## Installation

1. Upload `naked-social-share` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust the settings in Settings -> Naked Social Share
4. If you want to display the buttons manually in your theme somewhere, insert this into your theme file where you want the buttons to appear: `<?php naked_social_share_buttons(); ?>`

## Frequently Asked Questions

**How can I add the icons to my theme manually?**

Open up your theme file (for example, `single.php`) and place this code exactly where you want the icons to appear: `<?php naked_social_share_buttons(); ?>`

**Why aren't my share counters updating?**

The share counters are cached for 3 hours to improve loading times and to avoid making API calls on every single page load.

**How can I extend the plugin to add a new site?**

You can add a new site using filters and actions from the plugin. Here's an example showing how to create an add-on plugin to add 'Email' as a social site option: https://gist.github.com/nosegraze/73e950885fdbbecb20fe

**How can I change the font awesome icons?**

You can do this by creating a new add-on plugin and using the Naked Social Share filters. Here's an example for changing the Twitter icon:

```php
function nss_addon_twitter_icon( $icon_html ) {
	return '<i class="fa fa-twitter-square"></i>';
}
add_filter( 'naked_social_share_twitter_icon', 'nss_addon_twitter_icon' );
```

For more details, see this page: https://gist.github.com/nosegraze/f00b5101466752213e2d

## Changelog

See readme.txt