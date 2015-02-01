=== Orbisius Member Only Downloads for S2Member ===
Contributors: lordspace
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7APYDVPBCSY9A
Tags: wp,wordpress,orbisius,downloads,download,egd,file,files,digital downloads, download, downloads, e-commerce, e-downloads, e-store, ecommerce, eshop, mordauk, Pippin Williamson, pippinsplugins, selling, wp ecommerce
Requires at least: 3.0
Tested up to: 4.1
Parent: s2member
Stable tag: 1.0.2
License: GPLv2 or later

This plugin allows you to display/hide download links to certain users who have certain membership level. Requires: S2Member

== Description ==

= Support =
> Support is handled on our site: <a href="http://club.orbisius.com/" target="_blank" title="[new window]">http://club.orbisius.com/</a>
> Please do NOT use the WordPress forums or other places to seek support.

This is an S2Member extension plugin which allows you to display member only download links.
You can also set a minimum level required in order for the download link to be displayed.

= Features =
* Restrict downloads to logged in users or those who have certain membership level
* Easy to use

= Usage =

Paste links to your downloads. They could be links to Amazon S3.

1. Requires the user to be logged in order to see the download link
Example: [orb_s2member_dl url="http://file.zip"]

2. Requires (explicitly) the user to be logged in order to see the download link. Can be turned off too by setting req_login=0
Example: [orb_s2member_dl req_login=1 url="http://file.zip"]

3. Requires the user to be logged and to have at least level 1 membership in order to see the download link
Example: [orb_s2member_dl level=1 url="http://wordpress.org/latest.zip"]

= Support =
* Support is handled on our site: <a href="http://club.orbisius.com/support/" target="_blank" title="[new window]">http://club.orbisius.com/support/</a>
* Please do NOT use the WordPress forums or other places to seek support.

= Author =

Do you need an amazing plugin created especially for your needs? Contact me.
Svetoslav Marinov (Slavi) | <a href="http://orbisius.com" title="Custom Web Programming, Web Design, e-commerce, e-store, Wordpress Plugin Development, Facebook and Mobile App Development in Niagara Falls, St. Catharines, Ontario, Canada" target="_blank">Custom Web and Mobile Programming by Orbisius.com</a>

== Upgrade Notice ==
n/a

== Screenshots ==
1. Plugin's settings page showing usage

== Installation ==

1. Unzip the package, and upload `orbisius-s2member-only-downloads` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How to use this plugin? =
Just install the plugin and activate it. Then go to Admin > Tools > Orbisius Member Only Downloads.
Then click on a theme and the plugin will create a child theme for you.

= What to do next? =

Go to http://club.orbisius.com and post suggestions in our forum for new features that you'd like to see in this plugin or its extensions.

== Changelog ==

= 1.0.2 =
* Tested with WP 4.1
* Removed the Updater code that was supposed to be present only in Orbisius Premium plugins. Thanks WP Plugins team for the notice.

= 1.0.1 =
* Fixed a typo in readme
* Tested for WP 3.6.1

= 1.0.0 =
* Initial release