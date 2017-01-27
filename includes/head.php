<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
	<meta name="HandheldFriendly" content="true" />
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" type="image/png" href="<?php echo (rootpath() ."/static/images/" . get_favicon().'?'.time());?>"/>
	<!-- Core CSS -->
	<link href="<?php echo rootpath()?>/admin/style/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo rootpath()?>/static/css/style.css" rel="stylesheet">
	<?php if(layoutRTL()) { ?>
	<link href="<?php echo rootpath()?>/static/css/styleRTL.css" rel="stylesheet">
	<?php } ?>