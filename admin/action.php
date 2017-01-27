<?php
if(!isset($_SESSION)) 
session_start();

include "../config/config.php";

include "../includes/functions.php";

if(isset($_POST['delete']) && $_POST['delete'] == 'delete_all')
{

	$id = (int)mres($_POST['id']);   

    $page = (int)mres($_POST['page']); 

	$page -= 1;

	$per_page = 8;
	
	$start = $page * $per_page;	
	
	if($id)
	{
		
		$q = mysqlQuery("SELECT * FROM instant_domain WHERE id='$id'");
		
		$n = mysql_num_rows($q);
		
		if($n)
			mysqlQuery("DELETE FROM instant_domain WHERE id='$id'"); 

		$q = mysqlQuery("SELECT * FROM instant_domain LIMIT $start, $per_page");		
		$n = mysql_num_rows($q);
		
		if($n)
		echo $n;

	}
}
else if(isset($_POST['delete_page']) && $_POST['delete_page'] == 'delete_page')
{

	$id = (int)mres($_POST['id']);

	$sql_delete = mysqlQuery("DELETE FROM pages WHERE id='$id'");
	
	$sql_delete = mysqlQuery("DELETE FROM page_language WHERE `id`='$id'");
 
}
else if(isset($_POST['language']) && $_POST['language'] != '')
{

	$id = (int)mres($_POST['id']);
	
	$language = mres($_POST['language']);

	$sql_delete = mysqlQuery("DELETE FROM page_language WHERE `id`='$id' AND language = '$language'");
	
}
else if(isset($_POST['language_file']) && $_POST['language_file'] != '')
{
    unset($_SESSION['language_set']);
	
	$file = mres($_POST['language_file']);
	
	$language = mres($_POST['language_name']);
	
	$sql_delete = mysqlQuery("DELETE FROM page_language WHERE language = '$language'");
	
	$sql_delete = mysqlQuery("DELETE FROM widget_language WHERE language = '$language'");
	
	$sql_delete = mysqlQuery("DELETE FROM language WHERE lang_name = '$language'");
	
	unlink('../language/'.$file);
	
}
else if(isset($_POST['widget_name']))
{

    if($_POST['widget_name'] == '')
		echo "0";
	else 
	{
	
		$id = mres($_POST['id']);
		
		$widget_name = mres($_POST['widget_name']);
		
		$font = mres($_POST['font']);
		
		$status = mres($_POST['status']);
		
		mysqlQuery("UPDATE `widgets` SET widget='$widget_name',font='$font',status='$status' WHERE `id`='$id'");
		
		echo "1";
		
	}
	
}
else if(isset($_POST['widget_language']) && $_POST['widget_language'] != '')
{

	$id = (int)mres($_POST['id']);
	
	$language = mres($_POST['widget_language']);

	$sql_delete = mysqlQuery("DELETE FROM widget_language WHERE `id`='$id' AND language = '$language'");
	
}
?>