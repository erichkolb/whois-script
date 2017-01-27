<?php
if(analyticsEnabled()) {

	echo(show_analytics_status());
	
}
?>
</head> 
<body>
	<?php 
	if(basename($_SERVER['PHP_SELF'])=="index.php" || basename($_SERVER['PHP_SELF'])=="404.php" || basename($_SERVER['PHP_SELF'])=="recent.php" || basename($_SERVER['PHP_SELF'])=="redirect_contact.php" || basename($_SERVER['PHP_SELF'])=="redirect_page.php" || basename($_SERVER['PHP_SELF'])=="whois.php") { 
		$_SESSION['loader_session'];
		if(isset($_SESSION['loader_session'])) 
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
	}
	?>
	<div class="site">
		<header>
			<nav class="navbar navbar-default transition">
			  <div class="container">
			    <!-- Brand and toggle get grouped for better mobile display -->
				<?php if(layoutRTL()) { ?>
				<div class="navbar-header pull-right">
				<?php } else { ?>
				<div class="navbar-header">
				<?php } ?>
			      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="<?php echo rootpath() ; ?>" onclick='return change_pages("home","Home",event);'><img alt="<?php echo $_SERVER['HTTP_HOST'] ; ?>" src="<?php echo rootpath()?>/static/images/<?php echo get_logo().'?'.time() ; ?>" class="img-responsive" /></a>
					<?php 
					$language = $_SESSION['reset_lang_name'];
					$sql_select = mysql_query("SELECT permalink FROM `pages` WHERE status = 1  ORDER BY display_order");
					?>
			    </div>

			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="navbar-collapse-1">
					<?php if(layoutRTL()) { ?>
					<ul class="nav navbar-nav navbar-left">
					<?php } else { ?>
					<ul class="nav navbar-nav navbar-right">
					<?php } ?>
						<?php if(!layoutRTL()) { ?>
						<li id="home" class="page">
							<a href="<?php echo rootpath() ; ?>" onclick='return change_pages("home","Home",event);'><?php echo $_SESSION['Home']; ?></a>
						</li>
						<li id="recent" class="page">
							<a href="<?php echo rootpath() ; ?>/recent" onclick='return change_recent("1",event);'><?php echo $_SESSION['Recent']; ?></a>
						</li>
						<?php } ?>
						<?php if(layoutRTL()) { ?>
						<li class="page" id="contact">
							<a href="<?php echo rootpath() ; ?>/contact" onclick="return contact_page('contact',event);"><?php echo $_SESSION['Contact Us'] ; ?></a>
						</li>
						<?php } ?>
					<?php				
					$i=1;				
					while($get=mysql_fetch_array($sql_select))
					{
						$get_permalink = $get['permalink'];
						$rows = mysql_fetch_array(mysql_query("SELECT permalink,title FROM `page_language` WHERE permalink='$get_permalink' AND language='$language'"));
						if(!isset($rows['permalink']))
						{
							$default = mysql_fetch_array(mysql_query("SELECT lang_name FROM `language` WHERE status = 1 AND lang_name != '$language' ORDER BY display_order"));
							$get_language = $default['lang_name'];
								$rows = mysql_fetch_array(mysql_query("SELECT permalink,title FROM `page_language` WHERE permalink='$get_permalink' AND language='$get_language'"));
							if(!isset($rows['permalink']))
								$rows = mysql_fetch_array(mysql_query("SELECT permalink,title FROM `page_language` WHERE permalink='$get_permalink'"));
						}
						if($rows['permalink'] == $_SESSION['active_page'] || !isset($_SESSION['active_page']) && $i == 1)
						{
						?>
						<li id="<?php echo $rows['permalink'];?>" class="page active">
							<a href="<?php echo rootpath() ; ?>/page/<?php echo ($rows['permalink']) ?>" onclick='return change_pages("<?php echo ($rows['permalink']) ?>","<?php echo ($rows['title']) ?>",event);'><?php echo ($rows['title'])?></a>
						</li>
						<?php 	
						}	
						else
						{
						?>
						<li id="<?php echo $rows['permalink'];?>" class="page">
							<a href="<?php echo rootpath() ; ?>/page/<?php echo ($rows['permalink']) ?>" onclick='return change_pages("<?php echo ($rows['permalink']) ?>","<?php echo ($rows['title']) ?>",event);'><?php echo ($rows['title'])?></a>
						</li>
						<?php 
						}					
						$i++;
					}
					?>
					<?php if(layoutRTL()) { ?>
						<li id="recent" class="page">
							<a href="<?php echo rootpath(); ?>/recent" onclick='return change_recent("1",event);'><?php echo $_SESSION['Recent']; ?></a>
						</li>
						<li id="home" class="page">
							<a href="<?php echo rootpath(); ?>" onclick='return change_pages("home","Home",event);'><?php echo $_SESSION['Home']; ?></a>
						</li>
					<?php } ?>
					<?php if(!layoutRTL()) { ?>
					<li class="page" id="contact">
						<a href="<?php echo rootpath(); ?>/contact" onclick="return contact_page('contact',event);"><?php echo $_SESSION['Contact Us'] ; ?></a>
					</li>
					<?php } ?>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>
		</header> <!--/header-->