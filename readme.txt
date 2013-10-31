=== ROT13 Encoder/Decoder ===
Contributors: kjvtough
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=5F58ELJ9R3PVL&lc=CA&item_name=ROT13%20Encoder%20Wordpress%20Plugin%20Donation&currency_code=CAD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: rot13, cipher, hide, hidden, obfuscate, spoiler, warning, trigger, punchline, solution, answer, encrypt, decrypt, encode, decode, post, page, content, comment
Requires at least: 2.0.2
Tested up to: 3.7.1
Stable tag: 1.2
License: GPL2

Plugin to encode and display content using the ROT13 cipher. 

== Description ==

The ROT13 Encoder/Decoder plugin allows bloggers and commenters to "encrypt" parts of their writing output with the [ROT13 cipher](http://en.wikipedia.org/wiki/ROT13) in order to conceal content from other readers who don't wish to read them.

Some cases where you may want to ROT13 content:

* Spoilers on a fan website when some visitors may not have caught up to the content in question.
* Discussing sensitive topics where a trigger warning may be required.

Features:

* Allow readers to decode by single- or double-clicking the ROT13'd text.
* Decoded content can be displayed inline or a tooltip-style popup.
* Visitors can also use ROT13 in their comments: e.g.  `[rot13]Spoilerific content example[/rot13]`  

A TinyMCE menu button is added to the editor to help bloggers select text in their posts to ROT13.  

== Installation ==
**Note:** `XXX` refers to the current version release.
= Automatic method =
1. Click 'Add New' on the 'Plugins' page.
2. Upload `rot13-encoder-XXX.zip` using the file uploader on the page

= Manual method =
1. Unzip `rot13-encoder-XXX.zip` and upload the `rot13-encoder` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

Hopefully everything here is straightforward, but if now, post in the support forums and I'll see what I can do.

== Screenshots ==

1. Settings screen for the plugin. Here, the settings show that ROT13'd content will be decoded in place when the user single-clicks on it, and visitors can use the `[rot13]` shortcode in their comments.
2. Blog post with some ROT13'd content decrypted in a pop-up. 
3. Editor screen. Use <code>[rot13]</code> and <code>[/rot13]</code> to enclose the content to be ROT13'd, or select your text and click the ROT13 Encoder button. 
4. If activated, visitors to your blog can also ROT13 their comments.
5. A comment with some ROT13'd content.

== Changelog ==

= 1.0 =
* Initial release.

= 1.1 =
* Fixed some default settings
* Improved documentation

= 1.2
* Color picker on settings screen now uses the built-in Iris jQuery plugin.
* Use of `wp_localize_script` means no more passing values to Javascript files via query strings (DOING IT RIGHT!).
* Allowing commenters the use of ROT13 is now an option instead of automatic.

== Upgrade Notice ==

= 1.0 =
* Initial release. No upgrade required.

= 1.2 =
* Farbtastic jQuery color picker previously used is deprecated by Wordpress. Please upgrade.