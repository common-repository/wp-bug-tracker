=== WP Error Fix ===
Contributors: vasyl_m
Donate link: http://phpsnapshot.com/donate
Tags: wordpress error, bug track, development tool, fix, hotfix, plugin, error fix
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 1.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Tracks WordPress errors related to Plugins, Themes and Core and provide Fixes.

== Description ==

WP Error Fix tracks and fixes errors on your WordPress website by providing solutions
for free so you do not have to pay your developer. 

Also WP Error Fix records all Errors, Warnings and Notices and prepares next
possible visual reports:

* Linear Graph of total number of Errors, Warnings and Notices grouped by Date;
* Pie Graph of negative impact that each plugin or theme has on your blog;
* Interactive list of errors for debugging purposes.

WP Error Fix is a good tool to use during development or daily maintenance purposes.

== Installation ==

1. Upload `wp-bug-tracker` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. And you are ready to go.

== Frequently Asked Questions ==

= What does Available Solutions mean? =

You have errors printed on your website, we have solutions! Make sure that you
reported all these errors to our server and we will prepare solutions for you.
Please notice! We can prepare solutions only for plugins and themes which are
publicly available for download (from WordPress Repository or other website).
If you need a solution for custom plugins or themes, please contact us via email
support@phpsnapshot.com

= I have errors printed on my website. What should I do? =

Please make sure that errors are reported to PHPSnapshot server (press Report button).
This will notify our server that you have some issues with your WordPress installation.
Give us 24 hours to process the reports. If the solution is available your website
will be able to apply it immediately. In case of emergency, you may contact us
directly via email support@phpsnapshot.com.

= Registration Failed =

Please make sure that you have Internet Access and the website <a href="http://phpsnapshot.com">PHP Snapshot</a> is accessible.
Ok. Now please verify that your website has not trouble to send <a href="http://codex.wordpress.org/Function_API/wp_remote_request">remote request</a>.

= How can I suppress errors on my website? =

Please go to Bug Tracker backend menu and find out the answer in Info section.

== Screenshots ==

1. Linear Graph of errors
2. Pie Graph of plugin's system impact
3. List of errors in table format

== Changelog ==

= 1.5 =
* Deactivated Reporting & Solution features
* Updated Important Notice

= 1.4.2 =
* Fixed bug PHPSnapshot #82143
* Fixed bug PHPSnapshot #82953
* Last version of this plugin, moving to new WP Error Fix

= 1.4.1 =
* Automate the reporting feature
* Added message is empty error log

= 1.4 =
* Added connection buttons
* Renames the plugin to WP Error Fix
* Added visual solution notification

= 1.3.5.2 =
* Fixed Snapshot bug report #78677
* Fixed Snapshot bug report #78790

= 1.3.5 =
* Fixed conflict issue HTTP Compression plugin. Thanks to liverpoolroom
* Fixed CSS issue with loading non-existing images. Thanks to Blue Lotus Works
* Fixed Bug from PHPSnapshot report #73456
* Added Report Status column to the List of Errors

= 1.3 =
* Fixed issue with bootstrap from wp-config.php
* Added Contact Form

= 1.2.2 =
* Optimized the error handling mechanism
* Added possibility to bootstrap the error handling from wp-config.php
* Change the cron behavior

= 1.2.1 =
* Flush the output buffer during Ajax request to WP Bug Tracker plugin
* Changed screenshots
* Updated FAQ

= 1.2 =
* Fixed issue with server error log mashrooming (thanks to ianpark)
* Clean up all error logs and storages
* Fixed bug with Rest response on error (bug #25336 from PHPSnapshot)
* Fixed bug with Relative path to module file (bug #87 from PHPSnapshot)
* Fixed the Error Reporting custom handler
* Adjusted Stats to number of errors, not occurrence
* Fixed GUI glitch during page load
* Added Patching functionality
* Modifications to GUI
* Added Error counter to Admin menu
* Improved error list handling

= 1.0 =
* Initial version