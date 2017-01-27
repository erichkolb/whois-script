<?php 
if(!isset($_SESSION)) 
session_start();

include("config/config.php");

include("includes/functions.php");

include "language/lang_array.php";

include 'admin/libs/contact_captcha.php'; 

include 'libs/mail.php';

$data = array();

$_SESSION['captcha'] = simple_php_captcha(); 

if(isset($_POST['page']) && $_POST['page'] != 'contact' && $_POST['page'] != 'home' && $_POST['page'] != 'recent')
{

	$permalink = mres($_POST['page']);
	
	$_SESSION['active_page'] = $_POST['page'];
	
	$language = $_SESSION['reset_lang_name'];
	$sql = mysqlQuery("SELECT content,title FROM page_language WHERE permalink = '$permalink' AND language = '$language'");
	$mysql = mysql_fetch_array($sql);
	$count_page = mysql_num_rows($sql);
	if($count_page == 0)
	{					
		$default = mysql_fetch_array(mysql_query("SELECT lang_name FROM `language` WHERE status = 1 AND lang_name != '$language' ORDER BY display_order"));
		$language = $default['lang_name'];				
			$mysql = mysql_fetch_array(mysql_query("SELECT content,title FROM `page_language` WHERE permalink='$permalink' AND language = '$language'"));
		if(!isset($mysql['content']))
			$mysql = mysql_fetch_array(mysql_query("SELECT content,title FROM `page_language` WHERE permalink='$permalink'"));
	}
	$content = db_decode($mysql['content']);
	$Rectangle = mysql_fetch_array(mysql_query("SELECT medrec1 FROM `ads` WHERE medrec1_status = 1"));
	$Leaderboard1 = mysql_fetch_array(mysql_query("SELECT medrec2 FROM `ads` WHERE medrec2_status = 1"));
	$Leaderboard2 = mysql_fetch_array(mysql_query("SELECT medrec3 FROM `ads` WHERE medrec3_status = 1"));
	
	if(layoutRTL()) { 
		$page_layout = "<div class='col-lg-8 col-lg-push-4 col-xs-12'>";
	} else { 
		$page_layout = "<div class='col-lg-8 col-xs-12'>";
	}
	
	if(layoutRTL()) { 
		$Leaderboard_layout = "<div class='col-lg-4 col-lg-pull-8 visible-lg'>";
	} else { 
		$Leaderboard_layout = "<div class='col-lg-4 visible-lg'>";
	}
	
	if(isset($Leaderboard1['medrec2']) && $Leaderboard1['medrec2'] != "")
	{
		$first_ads_Leaderboard = "<div class='yt-card ad-tray-336 mrg-b-20 hidden-sm'>";
		$first_ads_Leaderboard .= $Leaderboard1['medrec2'];
		$first_ads_Leaderboard .="</div>";
	}
	
	if(isset($Leaderboard2['medrec3']) && $Leaderboard2['medrec3'] != "")
	{
		$second_ads_Leaderboard = "<div class='yt-card ad-tray-336 mrg-b-20 hidden-sm'>";
		$second_ads_Leaderboard .= $Leaderboard2['medrec3'];
		$second_ads_Leaderboard .="</div>";
	}
	
	if(isset($Rectangle['medrec1']) && $Rectangle['medrec1'] != "")
	{
		$Rectangle_ads_Leaderboard = "<div class='mrg-b-10 hidden-xs hidden-sm'>";
		$Rectangle_ads_Leaderboard .= "<div class='ad-tray-728'>";
		$Rectangle_ads_Leaderboard .= $Rectangle['medrec1'];
		$Rectangle_ads_Leaderboard .= "</div>";
		$Rectangle_ads_Leaderboard .= "</div>";
	}
	if($_SESSION['facebook_profile'] != "" || $_SESSION['twitter_profile'] != "" || $_SESSION['google_plus_profile'] != "") {
		$social_profile = "<div  class='panel panel-default'>";
		if($_SESSION['facebook_profile'] != "") {
			$social_profile .= "<div class='panel-body'>
				<iframe src='//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F".$_SESSION['facebook_profile']."&amp;width&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false' scrolling='no' frameborder='0' style='border:none; overflow:hidden; height:62px;' allowTransparency='true'></iframe>
			</div>";
			} if($_SESSION['twitter_profile']!="" || $_SESSION['google_plus_profile']!="") {
			$social_profile .= "<div class='panel-footer'>
				<a href='https://twitter.com/".$_SESSION['twitter_profile']."' class='twitter-follow-button' data-show-count='false'>Follow @".$_SESSION['twitter_profile']."</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				<div class='g-follow' data-annotation='bubble' data-height='20' data-href='https://plus.google.com/".$_SESSION['google_plus_profile']."' data-rel='publisher'></div>
				<!-- Place this tag after the last widget tag. -->
				<script type='text/javascript'>
				(function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
				</script>
			</div>";
			}
		$social_profile .= "</div>";
	}
	$data[0] ="
	<div class='container'>

		<div class='row box'>
			".$page_layout."
				<div class='panel'>
					<div class='panel-heading'>
					   <h1 class='panel-title'><b>".$mysql['title']."</b></h1>
					</div>
					<div class='panel-body'>
						<p>".$content."</p>
					</div>
				</div>
					".$Rectangle_ads_Leaderboard."
			</div>
			  
			".$Leaderboard_layout."
			<div>
				<section>
					".$social_profile."".$first_ads_Leaderboard."</br>".$second_ads_Leaderboard."	
				</section>
			</div>

		</div>

	</div>";
	
	echo json_encode($data);

	exit();

}
else if(isset($_POST['page']) && $_POST['page'] == 'home')
{

	$permalink = mres($_POST['page']);
	
	$_SESSION['active_page'] = $_POST['page'];
	
	if(isset($_SESSION['reset_lang_name']))
	{
		$language = $_SESSION['reset_lang_name'];
		$mysql = mysqlQuery("SELECT id,font FROM widgets WHERE status = 1 ORDER BY display_order");
	}
	$html =
	'<div class="whois-f-d text-center">
		<div class="container">
			<div class="row">';
			while($fetch = mysql_fetch_array($mysql))
			{
				$get_id = $fetch['id'];
				$font = $fetch['font'];
				$rows = mysql_fetch_array(mysqlQuery("SELECT id,title,content FROM widget_language WHERE language = '$language' AND id='$get_id'"));
				if(!isset($rows['id']))
				{
					$default = mysql_fetch_array(mysql_query("SELECT lang_name FROM `language` WHERE status = 1 AND lang_name != '$language' ORDER BY display_order"));
					$default_language = $default['lang_name'];				
					$rows = mysql_fetch_array(mysql_query("SELECT title,content FROM `widget_language` WHERE language = '$default_language' AND id='$get_id'"));
				}
				if(layoutRTL()) {
					$html .= '<div class="col-xs-6 col-md-3 pull-right">';
				} else {
					$html .= '<div class="col-xs-6 col-md-3">';
				}
				
				$html .= '<div class="inner-mrg z-depth-1">
						<i class="glyphicon '.$font.' glyphicon-fontsize"></i>
						<h1>'.$rows['title'].'</h1>
						'.db_decode($rows['content']).'
					</div>
				</div>';
			$i++;
			}
			$html .= '</div>
		</div>
	</div>';
	
	$ads = mysql_fetch_array(mysql_query("SELECT medrec1 FROM `ads` WHERE medrec1_status = 1")); 
	
	if(isset($ads['medrec1']) && $ads['medrec1'] != "")
	{
		$first_ads_Leaderboard = "<div class='mrg-b-10 hidden-xs hidden-sm'>";
		$first_ads_Leaderboard .= "<div class='ad-tray-728'>";
		$first_ads_Leaderboard .= $ads['medrec1'];
		$first_ads_Leaderboard .="</div>";
		$first_ads_Leaderboard .="</div>";
	}
	
	$data[0] =$first_ads_Leaderboard.$html;
	
	echo json_encode($data);

	exit();

}
else if(isset($_POST['page']) && $_POST['page'] == 'contact')
{
	?>
	<div id="contact-us">
		<div class="container">
			<div class="row box">
				<?php if(layoutRTL()) { ?>
				<div class="col-lg-8 col-lg-push-4 col-xs-12">
				<?php } else { ?>
				<div class="col-lg-8 col-xs-12">
				<?php } ?>
				
					<div class="panel">
					  <div class="panel-heading">
						 <h3 class="panel-title"><b><?php echo $_SESSION['Contact Us'] ; ?></b></h3>
					  </div>
					  
					<div class="panel-body">
						<section class="box">
							<div id="load-message">
							</div>
							<div style="display:none" id="replace-class"  class="alert alert-default alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> 
								<div id="alert-message"></div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-8 col-xs-12">
										<div class="input-group">
										  <span class="input-group-addon"><i class="fa fa-user"></i></span>
										  <input type="text" class="form-control con-input" id="name" name="name" placeholder="<?php echo $_SESSION['Enter Your Name'] ; ?>" pattern="[A-Za-z].{3,50}">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
								  <div class="col-sm-8 col-xs-12">
									<div class="input-group">
									  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									  <input type="email" class="form-control con-input" id="email" name="email" placeholder="<?php echo $_SESSION['Enter Your Email'] ; ?>" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}">
									</div>
								  </div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
								  <div class="col-sm-8 col-xs-12">
									<div class="input-group">
									  <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
									  <input type="text" class="form-control con-input" name="subject" id="subject"  placeholder="<?php echo $_SESSION['Enter a Subject'] ; ?>">
									</div>
								  </div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-12">
										<textarea class="form-control" id="message_box" rows="9" cols="25" name="message" placeholder="<?php echo $_SESSION['Enter Your Message'] ; ?>"  style="resize:none"></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<?php 
									if (captcha_contact_status()) 
									{ 
									?>
									<div class="col-xs-6">
										<img src="<?php echo($_SESSION['captcha']['image_src']) ?>" />
									</div>
									<div class="col-xs-6">
										<label><?php echo $_SESSION['Enter Captcha Code'] ; ?></label>
										<input class="form-control" name="captcha_code" id="captcha_code" placeholder="<?php echo $_SESSION['Enter Code'] ; ?>" value=""  type="text">												
									</div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-12">
										<button type="submit" name="submit" onclick="mailsend();" class="btn btn-success btn-md"><i class="fa fa-envelope"></i> <?php echo $_SESSION['Send Message'] ; ?></button>
									</div>
								</div>
							</div>
						</section>
					</div>
				</div>
				<?php include 'includes/ad-tray-728.php'; ?>
				</div>
				<?php include 'includes/sidebar.php'; ?>
			</div>
		</div> <!--.container-->
	</div> <!--#contact-us-->
	<?php	
	exit();		
}
else if(isset($_POST['page']) && $_POST['page'] == 'recent')
{

	$page = $_POST['p'];
	
	$cur_page = $page;

	$page -= 1;

	$per_page = 12;

	$first_btn = true;

	$last_btn = true;

	$start = $page * $per_page;
	
	$result_pag_data = mysqlQuery("SELECT * from instant_domain ORDER BY last_date_check DESC  LIMIT $start, $per_page ") or die('MySql Error' . mysql_error()); 

	$n = mysql_num_rows($result_pag_data); 
	
	$data = mysqlQuery("select * from instant_domain");
	
	$rows = mysql_num_rows($data);
	
	$no_of_paginations = ceil($rows / $per_page);
	?>
	<script src="<?php echo rootpath()?>/static/js/jquery.lazyloadxt.js" defer="defer"></script>
	<div id="recent">
		<div class="container">
			<div class="row box">
				<?php if(layoutRTL()) { ?>
				<div class="col-lg-8 col-lg-push-4 col-xs-12">
				<?php } else { ?>
				<div class="col-lg-8 col-xs-12">
				<?php } ?>
					<div class="row">
						<div class="panel">
							<div class="panel-heading">
							   <h1 class="panel-title"><?php echo $_SESSION['Recently Checked Websites'] ; ?></h1>
							</div>
						</div>
						<?php 
						while($rows = mysql_fetch_array($result_pag_data))
						{
						?>
						<div class="col-xs-6 col-sm-4">
							<div class="thumbnail">
								<a href="<?php echo rootpath(); ?>/<?php echo $rows['domain']; ?>.html" onclick="return search_whois_detail('<?php echo $rows['domain']; ?>',event);">
									<img src="<?php echo rootpath(); ?>/static/images/lazyload.jpg" id="img-1" onError="this.onerror=null;this.src='<?php echo rootpath(); ?>/static/images/default_thumb.png';" data-src="http://free.pagepeeker.com/v2/thumbs.php?size=x&url=<?php echo $rows['domain']; ?>" alt="...">
								</a>	
								<div class="caption">
									<p>
										<img class="index_web_favicon" width="16" height="16" alt="facebook.com" src="http://www.google.com/s2/favicons?domain=http://<?php echo $rows['domain']; ?>"> 
										<a href="<?php echo rootpath(); ?>/<?php echo $rows['domain']; ?>.html" onclick="return search_whois_detail('<?php echo $rows['domain']; ?>',event);"><?php echo $rows['domain']; ?></a>
									</p>
								</div>
							</div>
						</div>
						<?php 
						}
						if ($cur_page >= 7) {
						$start_loop = $cur_page - 3;
						if ($no_of_paginations > $cur_page + 3)
							$end_loop = $cur_page + 3;
						else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
							$start_loop = $no_of_paginations - 6;
							$end_loop = $no_of_paginations;
						} else {
							$end_loop = $no_of_paginations;
						}
						} else {
							$start_loop = 1;
							if ($no_of_paginations > 7)
								$end_loop = 7;
							else
								$end_loop = $no_of_paginations;
						}

						$msg="";
						
						$msg .= "<nav id='pagination'><ul class='pagination'>";

						if ($first_btn && $cur_page > 1) {
							$msg .= "<li  class='disabled'><a href='".rootpath()."/recent' onclick='return change_recent(1,event);'  aria-label='Previous'><span aria-hidden='true'>First</span></a></li>";
						} else if ($first_btn) {
							$msg .= "<li class='disabled'><a href='".rootpath()."/recent' onclick='return change_recent(1,event);'  aria-label='Previous'><span aria-hidden='true'>First</span></a></li>";
						}


						for ($i = $start_loop; $i <= $end_loop; $i++) {
						if ($cur_page == $i)
							$msg .= "<li class='active'><a href='".rootpath()."/recent/".$i."' onclick='return change_recent(".$i.",event);'>".$i."<span class='sr-only'>(current)</span></a></li>";
						else
							$msg .= "<li ><a href='".rootpath()."/recent/".$i."' onclick='return change_recent(".$i.",event);'>".$i."</a></li>";
						}
																			/*TO ENABLE THE END BUTTON*/
						if ($last_btn && $cur_page < $no_of_paginations) {
							$msg .= "<li><a href='".rootpath()."/recent/".$no_of_paginations."' onclick='return change_recent(".$no_of_paginations.",event)'; aria-label='Next'><span aria-hidden='true'>Last</span></a></li>";
						} else if ($last_btn) {
							$msg .= "<li><a href='".rootpath()."/recent/".$no_of_paginations."' onclick='return change_recent(".$no_of_paginations.",event)'; aria-label='Next'><span aria-hidden='true'>Last</span></a></li>";
						}
						$msg = $msg. "</ul></nav>";
						?>
					</div>				
					<?php 
					if($no_of_paginations > 1)
						echo $msg;
					?>
					<?php include 'includes/ad-tray-728.php'; ?>
				</div>		      
				<?php include 'includes/sidebar.php'; ?>
			</div> 
		</div> 
	</div> 
	<?php
	
	exit();		
}
?>