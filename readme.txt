=== SunPress Exchange ===

Author: David Fiske
Contributors: dfiske
Home: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Support: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3905584
Tags: sunpress, holiday, vacation, currency exchange, affiliate, travel
Requires at least: 2.6.0
Tested up to: 2.7.1
Stable tag: 2.0

Parse Sunshine.co.uk RSS feeds and convert the currency quoted into GBP, EUR or USD easily.

== Description ==

Parse Sunshine.co.uk RSS feeds and convert the currency quoted from GBP to EUR or USD easily.

This plugin works as a standalone plugin or very nicely with the sunPress plugin available at http://wordpress.org/extend/plugins/sunpress/

Version 1.1.1 converts UK airport IATA codes into readable names (e.g. MAN becomes Manchester Airport). 

Version 1.2 includes URL masking in the format `/book/holiday_type/holiday_reference/`

Version 2.0 stops resetting the admin settings on every update as well as allows the option to use URL masking or not.

== Installation ==

Always backup files before making any changes

1. Upload the entire "sunpress-exchange" folder to your plugins folder

2. Activate the plugin as normal

3. Go to the Sunpress Exchange tab under your Options menu and change the three variables

4. In your template file for single.php, paste `<?php sunpress_exchange_block(); ?>` where you want the block to appear; probably directly after `<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>`

EXTRA STEPS FOR SUNPRESS CONTENT TOOLS USERS

If you are running the SunPress widget, there's a couple of changes you need to make just to tidy things up:

1. In wp-sunshine.php in your SunPress plugin folder, scroll down and find the line:

   -   `add_filter('the_content', array(&, 'add_meta'));`

   Change this to:

   -   `// add_filter('the_content', array(&, 'add_meta'));`

   Save then upload this file.
	 
2. Paste `<?php hotel_rating(); ?>` in single.php - usually after `<div class="entry">`

== Frequently Asked Questions ==

Visit http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html for plugin information.

== Screenshots ==

Visit http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html for plugin information.