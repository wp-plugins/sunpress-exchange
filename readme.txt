=== SunPress Exchange ===

Author: David Fiske
Contributors: dfiske
Home: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Support: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3905584
Tags: sunpress, holiday, vacation, currency exchange, affiliate, travel
Requires at least: 2.6.0
Tested up to: 2.7.1
Stable tag: 2.5

Parse Sunshine.co.uk RSS feeds and convert the currency quoted into GBP, EUR or USD easily.

== Description ==

Parse Sunshine.co.uk RSS feeds and convert the currency quoted from GBP to EUR or USD easily.

This plugin works as a standalone plugin or very nicely with the sunPress plugin available at http://wordpress.org/extend/plugins/sunpress/

== Installation ==

Always backup files before making any changes

1. Upload the entire "sunpress-exchange" folder to your plugins folder

2. Activate the plugin as normal

3. Go to the Sunpress Exchange tab under your Options menu and change the three variables

4. In your template file for single.php, paste `<?php sunpress_exchange_block(); ?>` where you want the block to appear; probably directly after `<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>`

OPTIONAL STEPS

1. To use just one RSS feed for all your posts, in your template file for single.php, paste `<?php sunpress_exchange_block('rss_feed_url'); ?>` where you want the block to appear; probably directly after `<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>`. Replace rss_feed_url with the address of the RSS feed.

2. To display a compact version of the widget, replace `<?php sunpress_exchange_block(); ?>` with: `<?php sunpress_exchange_compact_block('', 'widget_title'); ?>` or `<?php sunpress_exchange_block('rss_feed_url'); ?>` with `<?php sunpress_exchange_compact_block('rss_feed_url', 'widget_title'); ?>`. Replace rss_feed_url with the address of the RSS feed and widget_title with the name of your widget, which will be displayed above the offers on the page.  

The compact version of the widget works exactly the same as the standard sized widget - it's just smaller. You can use a static RSS feed across all posts or with dynamic RSS feed URLs specified in your blog post as per normal. 

The compact version is not formatted. It will use inheritted styles from your Wordpress theme. You can overwrite this by adding clauses to your Wordpress theme stylesheet. The output is wrapped with the class: `.sunpress_exchange_compact`.

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

== Changelog ==

Version 1.1.1 converts UK airport IATA codes into readable names (e.g. MAN becomes Manchester Airport). 

Version 1.2 includes URL masking in the format `/book/holiday_type/holiday_reference/`

Version 2.0 stops resetting the admin settings on every update as well as allows the option to use URL masking or not.

Version 2.1 includes new styling and allows the widget to be used outside of posts. Just add the code `<?php sunpress_exchange_block("rss_url_here"); ?>` to your template, replacing rss_url_here with an RSS feed url.

Version 2.2 adds a new compact widget template tag

Version 2.4 adds a failsafe check for RSS feed URL validity

Version 2.5 adds a nofollow option to URLs