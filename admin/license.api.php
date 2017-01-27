<?php
error_reporting(E_ALL ^ E_NOTICE);

if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['admin_whs_secure'])) header('Location: ./index.php');

include "../config/config.php";

include "../includes/functions.php";
?>