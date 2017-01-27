<?php 
if(!isset($_SESSION)) session_start();

include("config/config.php");

include("includes/functions.php");

include "language/lang_array.php";
 
include 'includes/session.php';

$key = sha1(microtime());

if(isset($_SESSION['csrf_hack']))
unset($_SESSION['csrf_hack']);

$_SESSION['csrf_hack']=$key;

$_SESSION['csrf_hits']=$key;

include 'includes/head.php';

if (isset($_GET["permalink"]) && trim($_GET["permalink"]) != "") {

	$permalink = trim($_GET["permalink"]);
	$language = $_SESSION['reset_lang_name'];
	$array =  mysql_fetch_array(mysqlQuery("SELECT v.*,t.title AS page_title ,t.content AS page_content FROM `pages` v,`page_language` t WHERE v.permalink = t.permalink AND v.permalink='$permalink' AND t.language = '$language' AND v.status='1'"));
	if(!isset($array['id']))
	{
		$default = mysql_fetch_array(mysql_query("SELECT lang_name FROM `language` WHERE status = 1 AND lang_name != '$language' ORDER BY display_order"));
		$language = $default['lang_name'];
		$array =  mysql_fetch_array(mysqlQuery("SELECT v.*,t.title AS page_title ,t.content AS page_content FROM `pages` v,`page_language` t WHERE v.permalink = t.permalink AND v.permalink='$permalink' AND t.language = '$language'"));
		if(!isset($array['id']))
		$array =  mysql_fetch_array(mysqlQuery("SELECT v.*,t.title AS page_title ,t.content AS page_content FROM `pages` v,`page_language` t WHERE v.permalink = t.permalink AND v.permalink='$permalink'"));
	}
	if ($array['id']) {
	
		$title = $array['page_title'];
		$content = $array['page_content'];
		$description = $array['description'];
		$keywords = $array['keywords'];
		
	} else {	
	
		header('HTTP/1.0 404 Not Found');
		header("Location: ".rootpath()."/404.php");
		exit();
		
	} 
}
 
else {

	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . rootpath());
	exit();
} 
?>

<title><?php echo $title; ?></title>		
<meta name="description" content="<?php echo($description); ?>"/>
<meta name="keywords" content="<?php echo($keywords); ?>"/>

<!-- Twitter Card data -->
<meta name="twitter:card" content="<?php echo($title); ?>"/>
<meta name="twitter:title" content="<?php echo($title); ?>">
<meta name="twitter:description" content="<?php echo($description); ?>">
<meta name="twitter:image" content="<?php echo(rootPath()); ?>/static/images/whois.png?<?php echo(time()); ?>"/>

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo($title); ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo(getAddress()); ?>"/>
<meta property="og:image" content="<?php echo(rootPath()); ?>/static/images/whois.png?<?php echo(time()); ?>"/>
<meta property="og:description" content="<?php echo($description); ?>"/>
<meta property="og:site_name" content="<?php echo($title); ?>"/>
<?php
include 'includes/header.php';

include 'includes/searchbar.php';
?>	
<div id="default-page-loader">
</div>
<div id="default-index-page">
	<div>
		<div class='container'>
			<div class='row box'>
				<?php if(layoutRTL()) { ?>
				<div class='col-lg-8 col-lg-push-4 col-xs-12'>
				<?php } else { ?>
				<div class='col-lg-8 col-xs-12'>
				<?php } ?>
					<div class="panel">
						<div class="panel-heading">
							<h1 class="panel-title"><b><?php echo ($title); ?></b></h1>
						</div>
						<div class="panel-body">
							<?php echo htmlspecialchars_decode($content); ?>
						</div>
					</div>
					<?php include 'includes/ad-tray-728.php'; ?>
				</div>				  
				<?php include 'includes/sidebar.php'; ?>
			</div> <!-- .row -->
		</div> <!-- .container -->
	</div>
</div>
<?php 
include 'includes/results.php';

include 'includes/footer.php'; 
?>