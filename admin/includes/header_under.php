<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8" />
			<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
			<meta name="HandheldFriendly" content="true" />
			<meta name="robots" content="noindex, nofollow">
			<link rel="shortcut icon" type="image/png" href="<?php echo (rootpath() ."/static/images/" . get_favicon().'?'.time());?>"/>
			<link href="<?php echo rootpath()?>/admin/style/css/bootstrap.min.css" rel="stylesheet">
			<link href="<?php echo rootpath()?>/admin/style/css/admin.css" rel="stylesheet">
			<link href="<?php echo rootpath()?>/admin/style/css/bootstrap-iconpicker.min.css" rel="stylesheet">
			<link  href="<?php echo rootpath()?>/admin/style/css/font-awesome.min.css" rel="stylesheet">
			<link href="<?php echo rootpath()?>/admin/style/switch/switch/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
			<link rel="stylesheet" type="text/css" href="<?php echo rootpath()?>/admin/style/css/jquery.tagsinput.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo rootpath()?>/admin/style/css/froala_editor.min.css" />
			<link rel="stylesheet" type="text/css" href="<?php echo rootpath()?>/admin/style/css/froala_style.min.css" />
			<script type="text/javascript" src="<?php echo rootpath()?>/admin/style/js/jquery.min.js"></script>	
			<script type="text/javascript" src="<?php echo rootpath()?>/static/js/jquery.js"></script>
			<script type="text/javascript" src="<?php echo rootpath()?>/admin/style/js/jquery-ui.js"></script>
			<script type="text/javascript" src="<?php echo rootpath()?>/admin/style/js/bootstrap.js"></script>
			<script  type="text/javascript" src="<?php echo rootpath()?>/admin/js/switch.js"></script>
			<script  type="text/javascript" src="<?php echo rootpath()?>/admin/style/switch/switch/js/bootstrap-switch.js"></script>
			<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(window).load(function(){
					$('#preloader').fadeOut('slow',function(){$(this).remove();});
					$(".my_checkbox").bootstrapSwitch();
				});
			});
			</script>

			<?php 
			if(!isset($_SESSION['admin_loader_session']))
			{
				if(p_loader()) 
				$_SESSION['admin_loader_session'] = 1; 
			}
			if (isset($_SESSION['admin_loader_session'])) 
			{ 
			?>	
			<div id="preloader">
				<div class="loader">
					<div class="square" ></div>
					<div class="square"></div>
					<div class="square last"></div>
					<div class="square clear"></div>
					<div class="square"></div>
					<div class="square last"></div>
					<div class="square clear"></div>
					<div class="square "></div>
					<div class="square last"></div>
				</div>
			</div>
			<?php 
			}
			?>