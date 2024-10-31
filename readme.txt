=== Secure AXS ===
Contributors: (motaz_shazly)
Donate link: https://www.57357.org/donation/
Tags: security, secure, login, access, brute, force, attack, spam, recaptcha, register, sign, captcha, brute force attack, block, axs, spam, nocaptcha, no-captcha
Requires at least: 3.8
Tested up to: 5.7
Stable tag: 1.3.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Change default login to a custom branded URL you define to prevent spam login, bot registration, and brute-force with protection of Google reCAPTCHA.

== Description ==

Secure AXS changes default WordPress login URL to the url you define from settings page to prevent brute force attacks, spam logins, and bot or automated registrations. The plugin blocks access to default login url, generates a custom branded login panel (Which you can change colors and images), without creating a custom page on your website.

Additionally, the plugin offers the ultimate protection with the integration of latest and most sophisticated version of Google reCAPTCHA, where it's required on login and sign up.

<h3>Plugin Features</h3>

* Define new login url easily from settings page.
* Protect against spam login, bot registration or signup, with the integration of Google reCaptcha.
* Secure AXS is compatible with any permalink setup including the default.
* Choose to allow users with the role "Editor" to access plugin settings.
* Fully branded login page with colors and login logo of your choice.
* Plugin doesn't create new pages on your website for displaying the new login panel.
* Plugin is compatible with other major security & cache plugins.


<h3>IMPORTANT</h3>

You <strong>MUST</strong> save your free Google reCAPTCHA API keys to the plugin settings to activate reCAPTCHA protection for the plugin to work properly, you can obtain your free key from <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the directory 'secure-axs' to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. Go to <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a> to claim your free Google reCPATCHA API to use in plugin settings panel to activate reCAPTCHA protection.
5. upon activating, default login url becomes http://www.yourname.com/axs-login where 'www.yourname.com' is your domain name.


== Frequently Asked Questions ==

= I activated the plugin and can't seem to access the site? =

Activating the plugin on your Wordpress site changes default login to http://www.yoursite.com/axs-login or http://www.yoursite.com/?axs-login if your permalink setup is set to default, where "www.yoursite.com" is the name of your domain.

= What is Google reCAPTCHA? =

reCAPTCHA is a free service that protects your website from spam and abuse. reCAPTCHA uses an advanced risk analysis engine and adaptive CAPTCHAs to keep automated software from engaging in abusive activities on your site. It does this while letting your valid users pass through with ease.


= I Changed the Permalink setup and now the login panel doesn't work, how to access? =

Changing the permalink settings to or from default selection causes the secure login url to change accordingly, if your permalink setup was default and you changed it to SEO friendly URL, remove the "?" previously existed in your login url (i.e http://yourname.com/?axs-login would be http://yourname.com/axs-login), add the "?" after http://yourname.com/ if you reverted to default permalink. If that doesn't help, please feel free to open a support ticket in the relevant forum.

= Will keeping the new default login url prevent brute force attacks? =

Yes. However the best practice is to change the URL to something unique which prevents any attempts of brute force attacks.

== Screenshots ==

1. Branded WordPress login page using Secure AXS plugin.
2. New location of plugin menu item on dashboard.
3. Plugin Settings Panel.

== Changelog ==
= 1.3.4 =
* Fixed a minor PHP undefined constant warning.
* Tested with 5.0.3

= 1.3.3 =
* Automatically added preset API keys for the plugin to run smoothly on activation, you still should change these with your API keys.
* Tested with 4.7.1


= 1.3.2 =
* Fix WordPress media box to work with updated versions of jQuery and WordPress.


= 1.3.1 =
* Fix two PHP warnings.
* Tested with version 4.5.2.

= 1.3.0 =
* Introducing Google reCAPTCHA to the custom login panel.
* Introducing Google reCAPTCHA to registration process.
* New: Plugin now requires free Google reCAPTCHA API keys which can be obtained from https://www.google.com/recaptcha/admin
* New: 100% compatibility with open membership policy (where "Anyone can register" is checked).
* Improvement: Notices generated based on plugin settings changes.
* Improvement: Complete rewrite of code structure to clean code and optimize performance.



= 1.2.1 =
* Important Fix: Resetting password is redirecting to homepage without resetting password.

= 1.2.0 =
* Fix: Security nonce code was getting cached when some cache plugins are active on the website causing the security check to fail on login.
* Fix: How logos with wide landscape orientation was displaying shifted to the right.
* New: Ability to add background image to custom login page.
* Menu Reorder: Moved the plugin settings page to dashboard parent menu.
* Tested with 4.4.2

= 1.1.9 =
* Fix: When trying to access password protected pages, user gets redirected to homepage instead.

= 1.1.8 =
* New: Plugin Shows a warning on the action of permalink settings change to revise access url, when changing from or to default option of permalink setup, the secure axs login changes slightly accordingly.
* Fix: Reactivating the plugin reset previously saved settings.
* Fix: Login panel shows broken image if no image is selected.
* Improvement: Improved compatibility with older installations and different permalink setups including the default option.
* Improvement: Link logo to homepage at login panel, allow a better display of logo for different image sizes.
* Tested with 4.4.1

= 1.1 =
* Fix: In some installations, 'Select Image' input may not fire the WordPress media box due to lack of some dependencies. please update the plugin to fix it.

= 1.0 =
* First stable release of the plugin.

== Upgrade Notice ==
= 1.3.0 =
This upgrade is a must, with the integration if Google reCAPTCHA, it's now offering the ultimate protection against spam, brute force attacks and bot registrations.
