=== Content Protector ===
Contributors: kjvtough
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=5F58ELJ9R3PVL&lc=CA&item_name=Content%20Protector%20Wordpress%20Plugin%20Donation&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: protect, lock, CAPTCHA, password, hide, content, secret, AJAX, cookie, post, page, secure, Contact Form 7
Requires at least: 2.0.2
Tested up to: 4.3.1
Stable tag: 2.3
License: GPL2

Plugin to protect content on a Page or Post, where users require a password to access that content.

== Description ==
The Content Protector plugin allows users to password-protect a portion of a Page or Post.  This is done by adding a shortcode that you wrap
around the content you want to protect.  Your users are shown an access form in which to enter a password; if it's correct, the protected content
will get displayed.

Features

* Set up multiple protected sections on a single Post
* Display the protected content inline via AJAX or by reloading the page
* Set cookies so users won't need to re-enter the password on every visit, and share authorization with groups of protected sections.
* Apply custom CSS to your forms
* Choose from a variety of encryption methods for your passwords (depending on your server configuration)
* Supports Contact Form 7 in AJAX mode (i.e., use Content Protector to protect a form built in Contact Form 7)
* Set custom passwords or use a CAPTCHA to authorize your visitors

A TinyMCE dialog is included to help users build the shortcode. See the Screenshots tab for more info.

== Installation ==
**Note:** `XXX` refers to the current version release.
= Automatic method =
1. Click 'Add New' on the 'Plugins' page.
1. Upload `content-protector-XXX.zip` using the file uploader on the page

= Manual method =
1. Unzip `content-protector-XXX.zip` and upload the `content-protector` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Coming soon.  In the meantime, check out the <a href="http://wordpress.org/support/plugin/content-protector">support forum</a> and ask away.

== Screenshots ==

1. The Form Instructions tab on the Content Protector Settings page.
2. The Form CSS tab on the Content Protector Settings page.
3. TinyMCE dialog for generating Content Protector shortcodes.
4. A Content Protector shortcode wrapped around some top-secret content.
5. The access form Content Protector creates for your authorized users to enter the password.
6. If the password is wrong, an error message is displayed, along with the access form so they can try again.
7. A correct password results in a success message being displayed, along with the unlocked content.
8. If you've set a cookie, the success message is only shown on initial authorization. This is how the unlocked content will be shown until the cookie expires.
9. A Content Protector access form that uses a CAPTCHA.  You can customize the image under Settings -> Content Protector.

== Changelog ==
= 2.5 =
* New setting to manage encrypted passwords transient storage.
* New settings for Password/CAPTCHA Fields character lengths.
* Improved option initialization and cleanup routines.
* `content-protector-ajax.js` now loads in the footer.
* WPML/Polylang compatibility (beta).
* jQuery UI theme updated to 1.11.4

= 2.4 =
* Skipped

= 2.3 =
* Settings admin page now limited to users with `manage_options` permission (i.e., admin users only).
* Fixed bug where when using AJAX and CAPTCHA together, CAPTCHA image didn't reload on incorrect password.
* New settings: use either a text or password field for entering passwords/CAPTCHAs, and set placeholder text for those fields.
* Added `autocomplete="off"` to the access form.
* Streamlined i18n for date/time pickers (Use values available in Wordpress settings and `$wp_locale` when available, combined *-i18n.js files into one).

= 2.2.1 =
* Fixed AJAX bug where shortcode couldn't be found if already enclosed in another shortcode.
* Clarified error message if AJAX method cannot find shortcode.
* Changed calls from `die()` to `wp_die()`.

= 2.2 =
* Removed `content-protector-admin-tinymce.js` (No need anymore; required JS variables now hooked directly into editor). Fixes incompatibility with OptimizePress.

= 2.1.1 =
* Added custom filter `content_protector_content` to emulate `apply_filter( 'the_content', ... )` functionality for form and CAPTCHA instructions.

= 2.1 =
* Rich text editors for form and CAPTCHA instructions.
* NEW Template/Conditional Tag: `content_protector_is_logged_in()` (See Usage for details).
* Performance improvements via Transients API.

= 2.0 =
* New CAPTCHA feature! Check out the CAPTCHA tab on Settings -> Content Protector for details.
* Improved i18n.
* Various minor bug fixes.

= 1.4.1 =
* Dashicons support for WP 3.8 + added. Support for old-style icons in Admin/TinyMCE is deprecated.
* Unified dashicons among all of my plugins.

= 1.4 =
* Added "Display Success Message" option.

= 1.3 =
* Added "Shared Authorization" feature.
* Renamed "Password Settings" to "General Settings".

= 1.2.2 =
* Added support for Contact Form 7 when using AJAX.

= 1.2.1 =
* Fixed label repetition on "Cookie expires after" drop-down menu.

= 1.2 =
* Various CSS settings now controllable from the admin panel.
* Palettes on Settings color controls are now loaded from colors read from the active Theme's stylesheet.  This
should help in choosing colors that fit in with the active Theme.
* Spinner image now preloaded.
* Some language strings changed.

= 1.1 =
* AJAX loading message now customizable.

= 1.0.1 =
* Added required images for jQuery UI theme.
* Fixed some i18n strings.

= 1.0 =
* Initial release.

== Upgrade Notice ==
= 2.3 =
New features and bug fixes. Please upgrade.

= 2.1.1 =
Added custom filter `content_protector_content` to emulate `apply_filter( 'the_content', ... )` functionality for form and CAPTCHA instructions. Please upgrade.

= 2.1 =
New features. Please upgrade.

= 2.0 =
New features and bug fixes. Please upgrade.

= 1.2.1 =
Fixed label repetition on "Cookie expires after" drop-down menu. Please upgrade.

= 1.0.1 =
Added required images for JQuery UI theme and fixed some i18n strings.

= 1.0 =
Initial release.

== Usage ==

NOTE: The shortcode can be built using the built-in TinyMCE dialog.  When in doubt, use the dialog to create correctly formed shortcodes.

= Shortcode =

`[content_protector password="{string}" identifier="{string}" cookie_expires="{string|int}" ajax="{true|{string}}"]...[/content_protector]`

* `password` - Specifies the password that unlocks the protected content. Upper- and lower-case Latin alphabet letters (A-Z and a-z), numbers (0-9), and "." and "/" only.  Set `password` to "CAPTCHA" to add a CAPTCHA to your access form.
* `identifier` <em>(Optional)</em> - Used to differentiate between multiple instances of protected content
* `cookie_expires` <em>(Optional)</em> - If set, put a cookie on the user's computer so the user doesn't need to re-enter the password when revisiting the page.
* `ajax` <em>(Optional)</em> - Load the protected content using AJAX instead of reloading the page. Set to "true" to activate, but you must also set the `identifier` attribute in order to use this.

= Template/Conditional Tag =

`content_protector_is_logged_in( $password = "", $identifier = "", $post_id = "", $cookie_expires = "" )`

* `$password`, `$cookie_expires`, and `$identifier` are defined the same as their analogous attributes above. `$post_id` is the Post ID.
* Returns `true` if the user is currently authorized to access the content protected by a Content Protector shortcode matching those parameters.
* All arguments are <strong>required</strong>.

= Notes =

1. `cookie_expires` can be either a string or an integer. If it's an integer, it's processed as the number of seconds before the cookie expires; set it to "0" to make the cookie
expire when the browser is closed.  If it's a string, it can be either a duration (e.g., "2 weeks") or a human-readable date/time description
with timezone identifier (e.g., "January 1, 2014 12:01 AM America/New York"). The plugin uses PHP's <a href="http://www.php.net/manual/en/function.strtotime.php">strtotime</a>
function to process dates/times, so anything it can understand can be used depending on your server configuration.
2. While the use of `identifier` is optional, you *must* set it if you want to apply custom CSS or use AJAX with a specific access form, or to use Shared Authorization.
3. While you don't need to set `identifier` if you want to want to set a cookie for specific protected content, editing that content in the future will invalidate any
cookies set for it (this may actually be desired behaviour, depending on what you're trying to do).
4. Basically, when in doubt, set the `identifier` attribute.  You'll thank yourself later.
5. When you set an identifier for protected content, the identifier gets appended onto the existing DOM IDs in its access form.  For example if you set `identifier="Bob"`
in a shortcode, the ID for that form element will be `#content-protector-access-form-Bob`
6. Any identifiers you set on shortcodes you use in a specific post should be unique to that post (see Note 5).
