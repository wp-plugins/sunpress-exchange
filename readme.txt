=== SunPress Exchange ===

Author: David Fiske
Contributors: dfiske
Home: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Support: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3905584
Tags: sunpress, holiday, vacation, currency exchange, affiliate
Requires at least: 2.6.0
Tested up to: 2.7.1
Stable tag: 1.0

Parse Sunshine.co.uk RSS feeds and convert the currency quoted from GBP to EUR or USD easily.

== Description ==

Parse Sunshine.co.uk RSS feeds and convert the currency quoted from GBP to EUR or USD easily.

== Installation ==

Always backup files before making any changes

1. Upload the entire "sunpress-exchange" folder to your plugins folder

2. Activate the plugin as normal

3. Go to the Sunpress Exchange tab under your Options menu and change the three variables

4. In your template file for single.php, paste "<?php sunpress_exchange_block(); ?>" where you want the block to appear
	 - probably directly after "the_content..."

If you are running the SunPress widget, there's a couple of changes you need to make just to tidy things up:

1. In wp-sunshine.php in your SunPress plugin folder, scroll down and find the line:
      add_filter('the_content', array(&, 'add_meta'));
   Change this to:
      // add_filter('the_content', array(&, 'add_meta'));
   Save then upload this file.
	 
2. Paste "<?php hotel_rating(); ?>" in single.php - usually after "<div class="entry">"

== Frequently Asked Questions ==

Visit http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html for plugin information.

== Screenshots ==

Visit http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html for plugin information.