<a href="index.php" class="scrollToTop"><i class="fa fa-chevron-up fa-2x"></i></a>

</div> <!-- .site -->

<div class="positioner-height"></div>

<?php 

if($_SESSION['all_social_profile_status'] == 1)
{
	if($_SESSION['facebook_status'] == 1 || $_SESSION['twitter_status'] == 1 || $_SESSION['google_plus_status'] == 1)
	{
	?>
	<div id="social-button" class="social hidden-xs">
		<div class="container">
			<div class="text"><?php echo $_SESSION['social_message'] ; ?></div>
			<div class="social-holder">
				<?php if($_SESSION['facebook_status']) { ?>
				<div class="fb">
					<div id="fb-root"></div>
					<script>(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s); js.id = id;
						js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
						fjs.parentNode.insertBefore(js, fjs);
						  }(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-like" data-href="https://www.facebook.com/<?php echo $_SESSION['facebook_profile'];?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
				</div>
				<?php } if($_SESSION['twitter_status']) { ?>
				<div class="twitter">
					<a href="https://twitter.com/<?php echo $_SESSION['twitter_profile'];?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $_SESSION['twitter_profile'] ;?></a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>	
				</div>
				<?php } if($_SESSION['google_plus_status']) { ?>
				<div class="google-plus">
					<script src="https://apis.google.com/js/platform.js" async defer></script>
					<div class="g-plusone" data-size="medium" data-href="https://plus.google.com/<?php echo $_SESSION['google_plus_profile'];?>"></div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php 
	} 
}
?>

	<footer>
		<div class="footer">
			<nav class="container">
				<?php if(layoutRTL()) { ?>
				<div class="navbar-nav navbar-right links">
				<?php } else { ?>
				<div class="navbar-nav navbar-left links">
				<?php } ?>
					<div class="responsive-footer">
						<?php if(layoutRTL()) { ?>
						<span>
							<a href="<?php echo rootpath() ; ?>/contact" onclick="return contact_page('contact',event);">
								<?php echo $_SESSION['Contact Us'] ;?>
							</a>
						</span>
						<?php } ?>
						<?php				
						$i=1;
						$language = $_SESSION['reset_lang_name'];
						$sql_select = mysql_query("SELECT permalink FROM `pages` WHERE status = 1  ORDER BY display_order");
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
								<span class="page">
									<a href="<?php echo rootpath() ; ?>/page/<?php echo ($rows['permalink']) ?>" onclick='return change_pages("<?php echo ($rows['permalink']) ?>","<?php echo ($rows['title']) ?>",event);'>
									<?php echo ($rows['title'])?>
									</a>
								</span>
								<?php 	
							}	
							else
							{
								?>
								<span class="page">
									<a href="<?php echo rootpath() ; ?>/page/<?php echo ($rows['permalink']) ?>" onclick='return change_pages("<?php echo ($rows['permalink']) ?>","<?php echo ($rows['title']) ?>",event);'>
									<?php echo ($rows['title'])?>
									</a>
								</span>
								<?php 
							}					
							$i++;
						}
						?>
						<?php if(!layoutRTL()) { ?>
						<span>
							<a href="<?php echo rootpath() ; ?>/contact" onclick="return contact_page('contact',event);">
								<?php echo $_SESSION['Contact Us'] ;?>
							</a>
						</span>
						<?php } ?>
					</div>
					
					<?php 
					
					$mysql = mysql_query("SELECT * FROM language WHERE status = 1");
					
					$count = mysql_num_rows($mysql);
					if($count > 1)			
					{
						?>	
						<div class="nav-navbar flt-nn lang">
							<div class="btn-group dropup">
								<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Language <span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu" role="menu">
									<?php 
									$i = 1;
									while($rows = mysql_fetch_array($mysql))
									{
										if($_SESSION['reset_lang_name'] == $rows['lang_name'])
										{
											?>
											<li class="active"><a href="<?php echo(getAddress()); ?>" onclick="return change_language('<?php echo $rows['lang_name'] ; ?>',event);"><?php echo $rows['lang_name'] ;?></a></li>
											<?php
										}
										else
										{
											?>
											<li><a href="<?php echo(getAddress()); ?>" onclick="return change_language('<?php echo $rows['lang_name'] ; ?>',event);"><?php echo $rows['lang_name'] ;?></a></li>
											<?php
										}
										$i++;
									}
									?>
								</ul>
							</div>
						</div>	
						<?php 
					}
					?>			
				</div>
				<?php if(layoutRTL()) { ?>
				<div class="navbar-nav navbar-left">
				<?php } else { ?>
				<div class="navbar-nav navbar-right">
				<?php } ?>
				<?php if(layoutRTL()) { ?>
				<span>
					&#169; <?php echo date('Y') ?> <?php echo $_SESSION['All Rights Reserved'] ;?> ,<a href="http://<?php echo $_SERVER['HTTP_HOST'] ; ?>"><?php echo $_SERVER['HTTP_HOST'] ; ?></a>.
				</span>
				<div class="navbar-nav flt-nn">
					<span>
						<a href="http://nexthon.com">Nexthon.com</a>
					</span>
					<span>
						<?php echo $_SESSION['Powered By'] ;?>
					</span>
				</div>
				<?php } else { ?>
				<span>
					&#169; <?php echo date('Y') ?> <a href="http://<?php echo $_SERVER['HTTP_HOST'] ; ?>"><?php echo $_SERVER['HTTP_HOST'] ; ?></a>, <?php echo $_SESSION['All Rights Reserved'] ;?>.
				</span>
				<div class="navbar-nav flt-nn">
					<span>
						<?php echo $_SESSION['Powered By'] ;?>
					</span>
					<span>
						<a href="http://nexthon.com">Nexthon.com</a>
					</span>
				</div>
				<?php } ?>
				</div>
			</nav>
		</div>
	</footer>

    <!-- jQuery library - Please load it from Google API's -->
	<script src="<?php echo rootpath()?>/static/js/jquery.lazyloadxt.js" defer="defer"></script>
    <script src="<?php echo rootpath()?>/static/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo rootpath()?>/static/js/jquery-1.10.2.js" type="text/javascript"></script>
    <!-- Custom JS -->
    <script src="<?php echo rootpath()?>/static/js/custom.js" type="text/javascript"></script>
    <!-- Bootstrap JS -->
    <script type="text/javascript" src="<?php echo rootpath()?>/static/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo rootpath()?>/static/js/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?php echo rootpath()?>/static/js/grids.js"></script>
	<script type="text/javascript" src="<?php echo rootpath()?>/static/js/javascript.php"></script>
	<?php 
	$_SESSION['loader_session'];
	if(isset($_SESSION['loader_session'])) 
	{ 
	?>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(window).load(function(){
				$('#preloader').fadeOut('slow',function(){$(this).remove();});
			});
		});
	</script>
	<?php } ?>
	<?php if(basename($_SERVER['PHP_SELF'])=="index.php") { ?>
	<script>
		$(document).ready(function()
		{
			$(".page").removeClass("active");		
			$("#home").addClass('active');
		});
	</script>
	<?php } if(basename($_SERVER['PHP_SELF'])=="redirect_page.php") { ?>
	<script>
		$(document).ready(function()
		{
			$(".page").removeClass("active");		
			$("#<?php echo $_GET["permalink"] ; ?>").addClass('active');
		});
	</script>
	<?php } if(basename($_SERVER['PHP_SELF'])=="redirect_contact.php") { ?>
	<script>
		$(document).ready(function()
		{
			$(".page").removeClass("active");		
			$("#contact").addClass('active');
		});
	</script>
	<?php }  if(basename($_SERVER['PHP_SELF'])=="recent.php") { ?>
	<script>
		$(document).ready(function()
		{
			$(".page").removeClass("active");		
			$("#recent").addClass('active');
		});
	</script>
	<?php } if(basename($_SERVER['PHP_SELF'])=="whois.php") { ?>
	<script>
		$(document).ready(function()
		{
			$(".page").removeClass("active");
			var url = '<?php echo $url; ?>';
			if(url != '')	
				search_whois_detail(url,'click') ; 
		});
	</script>
	<?php }  
	if(basename($_SERVER['PHP_SELF']) != "whois.php") { ?>
	<script type="text/javascript">
	$(window).load(function(e) {
		$.cookie('csrf', '<?php echo($key); ?>');
		var url = '<?php echo $url; ?>';
		if(url != '')	
			search_whois_detail(url,'click') ; 
	});
	</script>
	<?php } ?>
   </body>
</html>