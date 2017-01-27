<?php 
if(!isset($_SESSION)) session_start();

include("../config/config.php");

include("../includes/functions.php");

$array = array();

if(isset($_POST['url'])){

	$url = rtrim(trim($_POST['url']),".");
		
	$url = getdomain($url);
	
	if(validURL($url)) {
	
		$array[0] = 1;
	
	}else{
	
		$array[0] = 0;
	
	}
	
	echo json_encode($array);
	
	exit();
}
?>