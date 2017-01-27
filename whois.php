<?php
if(!isset($_SESSION)) session_start();

include("config/config.php");

include("includes/functions.php");

include "language/lang_array.php";
 
include 'includes/session.php'; 

include "libs/whoischeck.php";

if(isset($_GET['url']))
{

	$url = rtrim(trim($_GET['url']),".");
	
	$url = getdomain($url);
	
}
include 'includes/head.php';
?>
<title><?php echo($url.'  '.$lang_array['whoislookup']); ?></title>		
<meta name="description" content="<?php echo($url); ?> WHOIS search to reveal information about the registry, owner & expiry details of a <?php echo($url); ?> domain name."/>
<meta name="keywords" content="<?php echo($url.'  '.$lang_array['whois_tags']); ?>"/>

<!-- Twitter Card data -->
<meta name="twitter:card" content="<?php echo($url.'  '.$lang_array['whoislookup']); ?>"/>
<meta name="twitter:title" content="<?php echo($url.'  '.$lang_array['whoislookup']); ?>">
<meta name="twitter:description" content="<?php echo($url); ?> WHOIS search to reveal information about the registry, owner & expiry details of a <?php echo($url); ?> domain name.">
<meta name="twitter:image" content="http://free.pagepeeker.com/v2/thumbs.php?size=x&url=<?php echo $url; ?>"/>

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo($url.'  '.$lang_array['whoislookup']); ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo(getAddress()); ?>"/>
<meta property="og:image" content="http://free.pagepeeker.com/v2/thumbs.php?size=x&url=<?php echo $url; ?>"/>
<meta property="og:description" content="<?php echo($url); ?> WHOIS search to reveal information about the registry, owner & expiry details of a <?php echo($url); ?> domain name."/>
<meta property="og:site_name" content="<?php echo($url.'  '.$lang_array['whoislookup']); ?>"/>
<?php
include 'includes/header.php';

include 'includes/searchbar.php';
?>
<div id="default-page-loader">
</div>
<div id="default-index-page">	
</div>
<?php
include 'includes/results.php';	

include 'includes/footer.php';
?>