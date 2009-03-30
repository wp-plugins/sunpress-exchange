<?php
/*
Plugin Name: Sunpress Exchange
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Add Sunshine.co.uk Deals RSS feeds to your blog in a choice of currencies
Version: 1.1
Author: David Fiske
Author URI: http://www.davidfiske.com

    Copyright 2009 David Fiske (http://www.davidfiske.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Adds a stylesheet for the output

function add_style() {
    echo "<link rel='stylesheet' type='text/css' href='".get_settings('siteurl')."/wp-content/plugins/sunpress-exchange/sunpress-exchange.css' />";
}

// Adds plugin options on install

function set_sunpress_exchange_options() {
		add_option('sunpress_exchange_currency','gbp','Choose a currency to convert to - gbp, eur or usd');
		add_option('sunpress_exchange_affiliate','67963','Your Sunshine.co.uk Affiliate Future affiliate ID');
		add_option('sunpress_exchange_tracking','sunpressexchange','Enter a tracking code if required (max 20 characters)');				
}

// Removes plugin options on uninstall

function unset_sunpress_exchange_options() {
		delete_option('sunpress_exchange_currency');
		delete_option('sunpress_exchange_affiliate');		
		delete_option('sunpress_exchange_tracking');
}


// Adds a custom section to the "advanced" Post and Page edit screens

function sunpress_exchange_box_outer() {
    if( function_exists( 'add_meta_box' )) {
        add_meta_box( 'sunpress_exchange_box', __( 'SunPress Exchange', 'myplugin_textdomain' ),'sunpress_exchange_box_inner','post','advanced');
    } else {
        add_action('dbx_post_advanced', 'old_sunpress_exchange_box' );
    }
}

// Prints fields inside the custom post/page section post-WordPress 2.5

function sunpress_exchange_box_inner() {
    global $post;
    echo '<input type="hidden" name="sunpress_exchange_noncename" id="sunpress_exchange_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
    echo '<label for="sunpress_exchange_rss_field">' . __("Sunshine RSS Feed URL", 'myplugin_textdomain' ) . '</label> ';
    echo '<input type="text" name="sunpress_exchange_rss_field" value="'.get_post_meta($post->ID, 'sunpress_exchange_rss', true).'" size="50" />';
    echo "<br />&nbsp;<br />Get RSS URL's from <a href='http://www.sunshine.co.uk/affiliates/rss/' title='Sunshine Affiliates - RSS'>http://www.sunshine.co.uk/affiliates/rss/</a><br />&nbsp;<br /><strong>Top Tip:</strong> Follows steps 2 and 3 on the Sunshine page. Leave step 1 empty, as you should have already tweaked the <a href='".get_settings('siteurl')."/wp-admin/options-general.php?page=sunpress-exchange/sunpress-exchange.php' title='SunPress Exchange Settings'>SunPress Exchange Settings</a>.";
}

// Prints fields inside the custom post/page section pre-WordPress 2.5

function old_sunpress_exchange_box() {
    echo '<div class="dbx-b-ox-wrapper">'."\n";
    echo '<fieldset id="myplugin_fieldsetid" class="dbx-box">' . "\n";
    echo '<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">'.__( 'SunPress Exchange', 'myplugin_textdomain' )."</h3></div>";   
    echo '<div class="dbx-c-ontent-wrapper"><div class="dbx-content">';
    sunpress_exchange_box_inner();
    echo "</div></div></fieldset></div>\n";
}

// Verify and save posted data

function sunpress_exchange_save_postdata($post_id) {
    if (!wp_verify_nonce($_POST['sunpress_exchange_noncename'], plugin_basename(__FILE__) )) {
        return $post_id;
    }

    if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id )) {
            return $post_id;
        } else {
            if ( !current_user_can( 'edit_post', $post_id )) {
                return $post_id;
						}
				}
    }

    $mydata = $_POST['sunpress_exchange_rss_field'];
    if (get_post_meta($post_id, 'sunpress_exchange_rss', true)) {
        update_post_meta($post_id, 'sunpress_exchange_rss', $mydata);
    } else {
        add_post_meta($post_id, 'sunpress_exchange_rss', $mydata, true);
    }
   return $mydata;
}

// Create Admin Options Page

function admin_sunpress_exchange_options() {
		echo "<div class='wrap'><h2>SunPress Exchange Options</h2>";
    		if($_REQUEST['submit']) {
    				updateadmin_sunpress_exchange_options();
    		}
		print_sunpress_exchange_options_form(); 
		echo "</div>";
}

// Save Admin Option Changes

function updateadmin_sunpress_exchange_options() {
		$ok = 0;
		if ($_REQUEST['sunpress_exchange_currency']=="gbp" || $_REQUEST['sunpress_exchange_currency']=="eur" || $_REQUEST['sunpress_exchange_currency']=="usd") {
			  update_option('sunpress_exchange_currency', $_REQUEST['sunpress_exchange_currency']);
				$ok++;
		}
		if (is_numeric($_REQUEST['sunpress_exchange_affiliate'])) {
			  update_option('sunpress_exchange_affiliate', $_REQUEST['sunpress_exchange_affiliate']);
				$ok++;
		}		
		if (strlen($_REQUEST['sunpress_exchange_tracking'])<=20) {
			  update_option('sunpress_exchange_tracking', $_REQUEST['sunpress_exchange_tracking']);
				$ok++;
		}
		if ($ok==3) {
			  echo "<div id='message' class='updated fade'><p>Options saved</p></div>";
		} else {
			  echo "<div id='message' class='error fade'><p>Options could not be saved</p></div>";		
		} 		
}

// Create Admin Options Form

function print_sunpress_exchange_options_form() {
		$default_currency = get_option('sunpress_exchange_currency');
		$default_affiliate = get_option('sunpress_exchange_affiliate');		
		$default_tracking = get_option('sunpress_exchange_tracking');
		
		echo "<p>3 settings can be changed on this page:</p>";
		echo "<form method='post'>";
		echo "<h3>Currency</h3><p><label for='sunpress_exchange_currency'>Choose a currency:</label> ";
		echo "<select name='sunpress_exchange_currency'>";
		
		if ($default_currency=="gbp") {echo "<option value='gbp' selected='selected'>Great British Pounds (GBP) - &pound;</option>"; } else {echo "<option value='gbp'>Great British Pounds (GBP) - &pound;</option>"; }
		if ($default_currency=="eur") {echo "<option value='eur' selected='selected'>Euro (EUR) - &euro;</option>"; } else {echo "<option value='eur'>Euro (EUR) - &euro;</option>"; }
		if ($default_currency=="usd") {echo "<option value='usd' selected='selected'>United States Dollar (USD) - $</option>"; } else {echo "<option value='usd'>United States Dollar (USD) - $</option>"; }

		echo "</select></p>";
		echo "<p><small>Currency conversion is approximate and based on figures from the European Central Bank. The currency source is updated daily.</small></p><hr />";
		echo "<h3>Affiliate Details</h3><p><label for='sunpress_exchange_affiliate'>Your <a href='http://www.sunshine.co.uk/affiliates/' title='Sunshine Affiliates'>Sunshine.co.uk</a> <a href='http://www.affiliatefuture.co.uk/registration/affiliates.asp?AffiliateID=67963' title='Affiliate Future'>Affiliate Future</a> affiliate ID:</label> <input type='text' name='sunpress_exchange_affiliate' value='".$default_affiliate."' /></p><hr />";
		echo "<h3>Tracking Information (optional)</h3><p><label for='sunpress_exchange_tracking'>Tracking reference</label> <input type='text' name='sunpress_exchange_tracking' value='".$default_tracking."' maxlength='20' /></p>";
		echo "<p><em>Tracking reference is optional and must be under 20 characters long if used</em></p>";
		echo "<p><input type='submit' name='submit' value='Save' /></p>";
		echo "</form>";

}

// Create Admin Options Menu

function modify_menu() {
    add_options_page('SunPress Exchange', 'SunPress Exchange', 'manage_options', __FILE__, 'admin_sunpress_exchange_options');
}

// Aiport Replacement Code

function str_replace_iata($description){
    $iata_codes = array("/\bABZ\b/", "/\bBHD\b/", "/\bBFS\b/", "/\bBHX\b/", "/\bBLK\b/", "/\bBOH\b/", "/\bBRS\b/", "/\bCAL\b/", "/\bCWL\b/", "/\bORK\b/", "/\bCVT\b/", "/\bLDY\b/", "/\bDSA\b/", "/\bDUB\b/", "/\bEMA\b/", "/\bEDI\b/", "/\bEXT\b/", 
    "/\bGWY\b/", "/\bGLA\b/", "/\bHUY\b/", "/\bINV\b/", "/\bIOM\b/", "/\bJER\b/", "/\bKIR\b/", "/\bNOC\b/", "/\bLBA\b/", "/\bLPL\b/", "/\bLCY\b/", "/\bLGW\b/", "/\bLHR\b/", "/\bLTN\b/", "/\bSTN\b/", "/\bMAN\b/", "/\bNCL\b/", "/\bNQY\b/", "/\bNWI\b/", "/\bPLH\b/", 
    "/\bPIK\b/", "/\bSNN\b/", "/\bSOU\b/", "/\bMME\b/");
    $airport_names   = array("Aberdeen Airport", "Belfast City Airport", "Belfast International Airport", "Birmingham International Airport", 
    "Blackpool International Airport", "Bournemouth Airport", "Bristol International Airport", "Campbeltown Airport", "Cardiff International Airport", 
    "Cork Airport", "Coventry Airport", "City of Derry Airport", "Robin Hood Airport (Doncaster / Sheffield)", "Dublin Airport", 
    "East Midlands Airport", "Edinburgh Airport", "Exeter International Airport", "Galway Airport", "Glasgow Airport", "Humberside International Airport", 
    "Inverness Airport", "Isle Of Man Airport", "Jersey Airport", "Kerry Airport", "Ireland West Airport (Knock)", "Leeds Bradford International Airport", 
    "Liverpool John Lennon Airport", "London City Airport", "Gatwick Airport", "Heathrow Airport", "Luton Airport", "Stansted Airport", "Manchester Airport", 
    "Newcastle Airport", "Newquay Cornwall Airport", "Norwich International Airport", "Plymouth City Airport", "Glasgow Prestwick International Airport", 
    "Shannon Airport", "Southampton Airport", "Durham Tees Valley Airport");
    $output = @preg_replace($iata_codes, $airport_names, $description);
    return $output;
}

// Create The Deals Box

function sunpress_exchange_block() {
    global $post;
		include_once(ABSPATH . WPINC . '/rss.php');
		
		$default_currency = get_option('sunpress_exchange_currency');
		$default_affiliate = get_option('sunpress_exchange_affiliate');		
		$default_tracking = get_option('sunpress_exchange_tracking');
		$rss_feed = get_post_meta($post->ID, 'sunpress_exchange_rss', true);
		
		if (substr($rss_feed,0,26) == "http://rss.sunshine.co.uk/") {
    		$rss = fetch_rss($rss_feed);
				if($rss!="" || $rss===true) {
            $xmlfile = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
            $xmlcontents = wp_remote_fopen($xmlfile); 
        		preg_match("|<Cube currency='GBP' rate='(.*)'/>|", $xmlcontents, $eurtogbp);
        		$eurtogbp=$eurtogbp[1];
						preg_match("|<Cube currency='USD' rate='(.*)'/>|", $xmlcontents, $eurtousd);
						$eurtousd=$eurtousd[1];
    				echo "<div id='sunpress_exchange'>";
						echo "<div id='sunpress_exchange_logo'>Latest Deals</div>";
								if (isset($eurtogbp) && $eurtogbp>0 && isset($eurtousd) && $eurtousd>0) {
                    $counter = 1;
                    foreach ($rss->items as $item) {
                        if ($counter < 6) {
                            preg_match('|&pound;(.*)pp|', $item['description'], $rssprice);
                            $rssprice=$rssprice[1];
                        				if ($default_currency=="eur") {$newprice = (1/$eurtogbp)*$rssprice; $currency="&euro;";}
                        				elseif ($default_currency=="usd") {$newprice = ((1/$eurtogbp)*$rssprice)*$eurtousd; $currency="$";}
                        				else {$newprice = $rssprice; $currency="&pound;";}
                            $rsstitle = $item['title'];
														$rsstitle = str_replace("&", "&amp;",$rsstitle);
														$rssdescription = $item['description'];
														$rssdescription = str_replace_iata($rssdescription);
                            $rssdescription = str_replace("from only &pound;".$rssprice."pp","<strong>from only ".$currency.number_format($newprice,2)."pp</strong>",$rssdescription);
                            echo "<p><a href='http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=".$default_affiliate."&amp;merchantID=2980&amp;programmeID=7749&amp;mediaID=0&amp;tracking=".$default_tracking."&amp;url=".$item['link']."' title='".$rsstitle."'>".$rsstitle."</a> - ".$rssdescription."</p>";
                        } else {
                            break;
                        }
                    $counter++;
                    }								
								} 
						if ($default_currency!="gbp") {
							  echo "<p><small>Currency conversion is approximate</small></p>";
						}
				echo "</div>";
				}
		}
}

// Hide SunPress Meta tags from the post when using SunPress

function hotel_rating(){
	global $post;			 
  $ratingvalue = get_post_custom_values('Rating',$post->ID);
  if (count($ratingvalue)>0) {
    	echo "<ul>";
    	foreach ( $ratingvalue as $key => $value ) {
        echo "<li><span class='post-meta-key'>Rating:</span> ".$value."</li>"; 
      }
    	echo "</ul>";
	}
}

// Run Everything!

register_activation_hook(__FILE__,'set_sunpress_exchange_options');
register_deactivation_hook(__FILE__,'unset_sunpress_exchange_options');
add_action('admin_menu','modify_menu');
add_action('wp_head', 'add_style');
add_action('admin_menu', 'sunpress_exchange_box_outer');
add_action('save_post', 'sunpress_exchange_save_postdata');

?>