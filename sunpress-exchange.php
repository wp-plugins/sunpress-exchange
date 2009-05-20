<?php
/*
Plugin Name: Sunpress Exchange
Plugin URI: http://www.davidfiske.co.uk/wordpress-sunpress-exchange.html
Description: Add Sunshine.co.uk Deals RSS feeds to your blog in a choice of currencies
Version: 2.5
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

function add_sunpress_exchange_style() {
    echo "<link rel='stylesheet' type='text/css' href='".get_option('siteurl')."/wp-content/plugins/sunpress-exchange/sunpress-exchange.css' />";
}

// Adds plugin options on install

function set_sunpress_exchange_options() {
    $sunpress_exchange_currency = get_option('sunpress_exchange_currency');
    $sunpress_exchange_affiliate = get_option('sunpress_exchange_affiliate');
    $sunpress_exchange_tracking = get_option('sunpress_exchange_tracking');
    $sunpress_exchange_mask = get_option('sunpress_exchange_mask');
		$sunpress_exchange_nofollow = get_option('sunpress_exchange_nofollow');

    if (!isset($sunpress_exchange_currency[0])) { add_option('sunpress_exchange_currency','gbp','Choose a currency to convert to - gbp, eur or usd'); }
    if (!isset($sunpress_exchange_affiliate[0])) { add_option('sunpress_exchange_affiliate','67963','Your Sunshine.co.uk Affiliate Future affiliate ID'); }
    if (!isset($sunpress_exchange_tracking[0])) { add_option('sunpress_exchange_tracking','sunpressexchange','Enter a tracking code if required (max 20 characters)'); }
    if (!isset($sunpress_exchange_mask[0])) { add_option('sunpress_exchange_mask','y','Mask affiliate links?'); }
		if (!isset($sunpress_exchange_nofollow[0])) { add_option('sunpress_exchange_nofollow','y','No-follow affiliate links?'); }
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
    echo "<br />&nbsp;<br />Get RSS URL's from <a href='http://www.sunshine.co.uk/affiliates/rss/' title='Sunshine Affiliates - RSS' target='_blank'>http://www.sunshine.co.uk/affiliates/rss/</a><br />&nbsp;<br /><strong>Top Tip:</strong> Follows steps 2 and 3 on the Sunshine page. Leave step 1 empty, as you should have already tweaked the <a href='".get_settings('siteurl')."/wp-admin/options-general.php?page=sunpress-exchange/sunpress-exchange.php' title='SunPress Exchange Settings'>SunPress Exchange Settings</a>.";
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
				delete_post_meta($post_id, 'sunpress_exchange_rss');
				add_post_meta($post_id, 'sunpress_exchange_rss', $mydata, true);

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
		if ($_REQUEST['sunpress_exchange_mask']=="y" || $_REQUEST['sunpress_exchange_mask']=="n") {
			  update_option('sunpress_exchange_mask', $_REQUEST['sunpress_exchange_mask']);
				$ok++;
		}
		if ($_REQUEST['sunpress_exchange_nofollow']=="y" || $_REQUEST['sunpress_exchange_nofollow']=="n") {
			  update_option('sunpress_exchange_nofollow', $_REQUEST['sunpress_exchange_nofollow']);
				$ok++;
		}		
		if ($ok==5) {
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
    $default_mask = get_option('sunpress_exchange_mask');
		$default_nofollow = get_option('sunpress_exchange_nofollow');
		
		echo "<p>4 settings can be changed on this page:</p>";
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
		echo "<h3>Mask Deeplinks</h3><p><label for='sunpress_exchange_mask'>Do you want to mask deeplinks:</label> ";
		echo "<select name='sunpress_exchange_mask'>";
		
		if ($default_mask=="y") {echo "<option value='y' selected='selected'>Yes</option>"; } else {echo "<option value='y'>Yes</option>"; }
		if ($default_mask=="n") {echo "<option value='n' selected='selected'>No</option>"; } else {echo "<option value='n'>No</option>"; }

		echo "</select></p>";		
		echo "<h3>No-follow Deeplinks</h3><p><label for='sunpress_exchange_nofollow'>Do you want to no-follow deeplinks:</label> ";
		echo "<select name='sunpress_exchange_nofollow'>";
		
		if ($default_nofollow=="y") {echo "<option value='y' selected='selected'>Yes</option>"; } else {echo "<option value='y'>Yes</option>"; }
		if ($default_nofollow=="n") {echo "<option value='n' selected='selected'>No</option>"; } else {echo "<option value='n'>No</option>"; }

		echo "</select></p>";				
		echo "<p><input type='submit' name='submit' value='Save' /></p>";
		echo "</form>";

}

// Create Admin Options Menu

function modify_sunpress_exchange_menu() {
    add_options_page('SunPress Exchange', 'SunPress Exchange', 'manage_options', __FILE__, 'admin_sunpress_exchange_options');
		
    function add_sunpress_exchange_settings($links, $file){
    	static $this_plugin;
    	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);
    	if( $file == $this_plugin ){
        	$settings_link = '<a href="options-general.php?page=sunpress-exchange/sunpress-exchange.php">' . __('Settings') . '</a>';
        	$links = array_merge( array($settings_link), $links);
    	}
    	return $links;
    }
		
		add_filter( 'plugin_action_links', 'add_sunpress_exchange_settings', 10, 2 );
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

function sunpress_exchange_block($rss=false) {
    global $post;
    include_once(ABSPATH . WPINC . '/rss.php');
    
    $default_currency = get_option('sunpress_exchange_currency');
    $default_mask = get_option('sunpress_exchange_mask');
    $default_affiliate = get_option('sunpress_exchange_affiliate');		
    $default_tracking = get_option('sunpress_exchange_tracking');
		$default_nofollow = get_option('sunpress_exchange_nofollow');
        if (!isset($rss) || $rss=="") {$rss_feed = get_post_meta($post->ID, 'sunpress_exchange_rss', true);} else {$rss_feed=$rss; }
    $siteurl = get_option('siteurl');
    
    if (substr($rss_feed,0,26) == "http://rss.sunshine.co.uk/") {
				if (strpos($rss_feed, "?") !== false) {$rss_feed = substr($rss_feed,0,strpos($rss_feed, "?")); }  
        $rss = fetch_rss($rss_feed);
        if($rss!="" || $rss===true) {
            if ($default_currency!="gbp") {
                $xmlfile = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
                $xmlcontents = wp_remote_fopen($xmlfile); 
                preg_match("|<Cube currency='GBP' rate='(.*)'/>|", $xmlcontents, $eurtogbp);
                $eurtogbp=$eurtogbp[1];
                preg_match("|<Cube currency='USD' rate='(.*)'/>|", $xmlcontents, $eurtousd);
                $eurtousd=$eurtousd[1];
            }
            
            if (count($rss->items)>0) {
                echo "<div id='sunpress_exchange'>";
                echo "<div id='sunpress_exchange_logo'>Latest Deals</div>";
                $counter = 1;
                    foreach ($rss->items as $item) {
                            if ($counter < 6) {
                                preg_match('|&pound;(.*)pp|', $item['description'], $rssprice);
                                $rssprice=$rssprice[1];
                                    if ($default_currency=="eur" && isset($eurtogbp) && $eurtogbp>0) {$newprice = (1/$eurtogbp)*$rssprice; $currency="&euro;";}
                                    elseif ($default_currency=="usd" && isset($eurtousd) && $eurtousd>0) {$newprice = ((1/$eurtogbp)*$rssprice)*$eurtousd; $currency="$";}
                                    else {$newprice = $rssprice; $currency="&pound;";}
                                $rsstitle = $item['title'];
                                $rsstitle = str_replace("&", "&amp;",$rsstitle);
                                $rssdescription = $item['description'];
                                $rssdescription = str_replace_iata($rssdescription);
                                $rssdescription = str_replace("from only &pound;".$rssprice."pp","<strong>from only ".$currency.number_format($newprice,2)."pp</strong>",$rssdescription);
                                $rssurl = $item['link'];
                                
                                    if ($default_mask == "y") {
                                            if (strpos($rssurl, "holidays") !== false) {$sub="holidays";}
                                            elseif (strpos($rssurl, "hotels") !== false) {$sub="hotels";}
                                            elseif (strpos($rssurl, "flights") !== false) {$sub="flights";}
                                        
                                        $rssurl = str_replace("http://www.sunshine.co.uk/holidays/search-","",$rssurl);
                                        $rssurl = str_replace("http://www.sunshine.co.uk/hotels/search-","",$rssurl);
                                        $rssurl = str_replace("http://www.sunshine.co.uk/flights/search-","",$rssurl);
                                        $rssurl = str_replace(".html","",$rssurl);
                                        $rssurl = $siteurl."/book/".$sub."/".$rssurl."/";
                                    } else {
                                        $rssurl = "http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=".$default_affiliate."&merchantID=2980&programmeID=7749&mediaID=0&tracking=".$default_tracking."&url=".$rssurl;	 
                                    }
                                
                                    if ($default_nofollow=="y") {
        																echo "<p><a href='".$rssurl."' title='".$rsstitle."' rel='nofollow'>".$rsstitle."</a> - ".$rssdescription."</p>";
    																} else {
        																echo "<p><a href='".$rssurl."' title='".$rsstitle."'>".$rsstitle."</a> - ".$rssdescription."</p>";
    																}
                                unset($rssurl); unset($rsstitle); unset($rssdescription);
                            } else {
                                break;
                            }
                        $counter++;
                    }	
                if ($default_currency!="gbp") {
                    echo "<p><small>Currency conversion is approximate</small></p>";
                }
                echo "</div>";
            }							
        }
    }
}

// Create The Deals Box

function sunpress_exchange_compact_block($rss=false,$blocktitle=false) {
    global $post;
		include_once(ABSPATH . WPINC . '/rss.php');
		
		$default_currency = get_option('sunpress_exchange_currency');
		$default_mask = get_option('sunpress_exchange_mask');
		$default_affiliate = get_option('sunpress_exchange_affiliate');		
    $default_tracking = get_option('sunpress_exchange_tracking');
		$default_nofollow = get_option('sunpress_exchange_nofollow');
        if (!isset($rss) || $rss=="") {$rss_feed = get_post_meta($post->ID, 'sunpress_exchange_rss', true);} else {$rss_feed=$rss; }
		$siteurl = get_option('siteurl');
		
		if (substr($rss_feed,0,26) == "http://rss.sunshine.co.uk/") {
    		if (strpos($rss_feed, "?") !== false) {$rss_feed = substr($rss_feed,0,strpos($rss_feed, "?")); } 
				
				$rss = fetch_rss($rss_feed);
				if($rss!="" || $rss===true) {
            if ($default_currency!="gbp") {
    						$xmlfile = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
                $xmlcontents = wp_remote_fopen($xmlfile); 
            		preg_match("|<Cube currency='GBP' rate='(.*)'/>|", $xmlcontents, $eurtogbp);
            		$eurtogbp=$eurtogbp[1];
    						preg_match("|<Cube currency='USD' rate='(.*)'/>|", $xmlcontents, $eurtousd);
    						$eurtousd=$eurtousd[1];
						}
    				if (count($rss->items)==0) { 
						    echo "<div class='sunpress_exchange_compact'>";
								if(isset($blocktitle) && $blocktitle!="" && $blocktitle!=false) {echo "<h3>".$blocktitle."</h3>";}
								echo "<p>No recent quotes can be found.</p>";
								if(isset($blocktitle) && $blocktitle!="") {echo "<p>Why not run through a quote and see how little you could be holidaying in ".$blocktitle." for?</p>"; }
								echo "<p>Just use our quick and easy search tool.</p>";
								echo "</div>";
						} else {
    						echo "<div class='sunpress_exchange_compact'>";
								if(isset($blocktitle) && $blocktitle!="") {echo "<h3>".$blocktitle."</h3>";}
								echo "<ul>";
                $counter = 1;
                foreach ($rss->items as $item) {
                    if ($counter < 6) {
                            preg_match('|&pound;(.*)pp|', $item['description'], $rssprice);
                            $rssprice=$rssprice[1];
                        				if ($default_currency=="eur" && isset($eurtogbp) && $eurtogbp>0) {$newprice = (1/$eurtogbp)*$rssprice; $currency="&euro;";}
                        				elseif ($default_currency=="usd" && isset($eurtousd) && $eurtousd>0) {$newprice = ((1/$eurtogbp)*$rssprice)*$eurtousd; $currency="$";}
                        				else {$newprice = $rssprice; $currency="&pound;";}
                            $rsstitle = $item['title'];
                						$rsstitle = str_replace("&", "&amp;",$rsstitle);
                						$rssdescription = "<span class='sunpress_exchange_compact_from'>from just ".$currency.number_format($newprice,2)."pp</span>";
                            $rssurl = $item['link'];
        						
        						if ($default_mask == "y") {
                            if (strpos($rssurl, "holidays") !== false) {$sub="holidays";}
        										elseif (strpos($rssurl, "hotels") !== false) {$sub="hotels";}
        										elseif (strpos($rssurl, "flights") !== false) {$sub="flights";}
        										
        										$rssurl = str_replace("http://www.sunshine.co.uk/holidays/search-","",$rssurl);
        										$rssurl = str_replace("http://www.sunshine.co.uk/hotels/search-","",$rssurl);
        										$rssurl = str_replace("http://www.sunshine.co.uk/flights/search-","",$rssurl);
        										$rssurl = str_replace(".html","",$rssurl);
        										$rssurl = $siteurl."/book/".$sub."/".$rssurl."/";
        						} else {
        								$rssurl = "http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=".$default_affiliate."&merchantID=2980&programmeID=7749&mediaID=0&tracking=".$default_tracking."&url=".$rssurl;	 
        						}

                    if ($default_nofollow=="y") {
                        $extra="rel=' nofollow'";
                    } else {
                        $extra="";
                    }
        
        						if ($sub == "holidays") {
    										echo "<li><a href='".$rssurl."' title='".$rsstitle."'".$extra.">".$rsstitle."</a> ".$rssdescription." (inc flights)</li>";
										} elseif ($sub == "hotels") {
    										echo "<li><a href='".$rssurl."' title='".$rsstitle."'".$extra.">".$rsstitle."</a> ".$rssdescription." (hotel only)</li>";
										} elseif ($sub == "flights") {
    										echo "<li><a href='".$rssurl."' title='".$rsstitle."'".$extra.">".$rsstitle."</a> ".$rssdescription." (flights only)</li>";
										}
										
        						unset($rssurl); unset($rsstitle); unset($rssdescription);
                        } else {
                            break;
                        }
                    $counter++;
                    }								
        				
								echo "</ul>";		
								if (count($rss->items)<3) {
    								echo "<p>Use our quick and easy search tool to get an instant quote for your holiday.</p>";
								}
    						if ($default_currency!="gbp") {
    							  echo "<p><small>Currency conversion is approximate</small></p>";
    						}
        				echo "</div>";
						}
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

//Create Outlink Rewrite Rule

function sunpress_exchange_rewrite() {
      add_rewrite_rule('book/(.*)/(.*)/$', 'wp-content/plugins/sunpress-exchange/out.php?type=$1&id=$2');
}

// Refresh existing rules, adding above rule, on install only

function sunpress_exchange_rewrite_flush() {
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}

// Run Everything!

add_filter('generate_rewrite_rules', 'sunpress_exchange_rewrite');
register_activation_hook(__FILE__,'set_sunpress_exchange_options');
register_activation_hook(__FILE__,'sunpress_exchange_rewrite_flush');
add_action('admin_menu','modify_sunpress_exchange_menu');
add_action('wp_head', 'add_sunpress_exchange_style');
add_action('admin_menu', 'sunpress_exchange_box_outer');
add_action('save_post', 'sunpress_exchange_save_postdata');

?>