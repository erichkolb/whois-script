<?php
if(!isset($_SESSION)) session_start();

include("../config/config.php");

include("../includes/functions.php");

if(isset($_POST['uniqueHits']) && $_POST['uniqueHits']='1' && !isset($_SESSION['uniqueHit'])) {

	increment_unique_hits();
	
	$_SESSION['uniqueHit'] = true;
	
} else if(isset($_POST['pageViews']) && $_POST['pageViews']='1') {

	increment_page_views();

}
?>