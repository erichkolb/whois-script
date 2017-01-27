<?php
if (!isset($_SESSION))
session_start();

if (isset($_SESSION['admin_whs_secure'])){

	unset($_SESSION['admin_whs_secure']);
	
}

header("location:./index.php");
?>