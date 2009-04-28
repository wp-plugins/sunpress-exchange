<?php
/*
Redirect file
*/

require_once(dirname(__FILE__) . '/../../../wp-config.php');

$outlink = $_GET['id'];
$type = $_GET['type'];

if (!isset($outlink[0]) && !isset($type[0])) {
    $url = get_option('siteurl');
} else {
    $default_affiliate = get_option('sunpress_exchange_affiliate');		
    $default_tracking = get_option('sunpress_exchange_tracking');
    $url = "http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=".$default_affiliate."&merchantID=2980&programmeID=7749&mediaID=0&tracking=".$default_tracking."&url=http://www.sunshine.co.uk/".$type."/search-".$outlink.".html";
}
header("Location: $url");
exit;
?>
