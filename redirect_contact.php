<?php 
if(!isset($_SESSION)) session_start();

include("config/config.php");

include("includes/functions.php");

include "language/lang_array.php";
 
include 'includes/session.php';

$key = sha1(microtime());

if(isset($_SESSION['csrf_hack']))
unset($_SESSION['csrf_hack']);

$_SESSION['csrf_hack']=$key;

$_SESSION['csrf_hits']=$key;

include 'admin/libs/contact_captcha.php'; 

include 'libs/mail.php';  

include 'includes/head.php';
?>
<title><?php echo ($lang_array['contact_meta_title']); ?></title>
<meta name="description" content="<?php echo ($lang_array['contact_meta_description']); ?>" />
<meta name="keywords" content="<?php echo ($lang_array['contact_meta_keywords']); ?>" />

<!-- Twitter Card data -->
<meta name="twitter:card" content="<?php echo ($lang_array['contact_meta_title']); ?>"/>
<meta name="twitter:title" content="<?php echo($lang_array['contact_meta_title']); ?>">
<meta name="twitter:description" content="<?php echo ($lang_array['contact_meta_description']); ?>">
<meta name="twitter:image" content="<?php echo(rootPath()); ?>/static/images/whois.png?<?php echo(time()); ?>"/>

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo($lang_array['contact_meta_title']); ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo(getAddress()); ?>"/>
<meta property="og:image" content="<?php echo(rootPath()); ?>/static/images/whois.png?<?php echo(time()); ?>"/>
<meta property="og:description" content="<?php echo($lang_array['contact_meta_description']); ?>"/>
<meta property="og:site_name" content="<?php echo(get_title()); ?>"/>
<?php
include 'includes/header.php';

include 'includes/searchbar.php';

$_SESSION['captcha'] = simple_php_captcha(); 
?>
<div id="default-page-loader">
</div>
<div id="default-index-page">
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
									<?php if(layoutRTL()) { ?>
									<div class="col-lg-8 col-lg-push-4 col-xs-12">
									<?php } else { ?>
									<div class="col-lg-8 col-xs-12">
									<?php } ?>
										<div class="input-group">
										  <span class="input-group-addon"><i class="fa fa-user"></i></span>
										  <input type="text" class="form-control con-input" id="name" name="name" placeholder="<?php echo $_SESSION['Enter Your Name'] ; ?>" pattern="[A-Za-z].{3,50}">
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
								  <?php if(layoutRTL()) { ?>
								  <div class="col-lg-8 col-lg-push-4 col-xs-12">
								  <?php } else { ?>
								  <div class="col-lg-8 col-xs-12">
								  <?php } ?>
									<div class="input-group">
									  <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									  <input type="email" class="form-control con-input" id="email" name="email" placeholder="<?php echo $_SESSION['Enter Your Email'] ; ?>" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}">
									</div>
								  </div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
								  <?php if(layoutRTL()) { ?>
								  <div class="col-lg-8 col-lg-push-4 col-xs-12">
								  <?php } else { ?>
								  <div class="col-lg-8 col-xs-12">
								  <?php } ?>
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
									<?php if(layoutRTL()) { ?>
									<div class="col-xs-4 col-lg-push-8 captchaImage">
									<?php } else { ?>
									<div class="col-xs-4 captchaImage">
									<?php } ?>
										<img src="<?php echo($_SESSION['captcha']['image_src']) ?>" />
									</div>
									<?php if(layoutRTL()) { ?>
									<div class="col-xs-8 col-lg-pull-4">
									<?php } else { ?>
									<div class="col-xs-8">
									<?php } ?>
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
</div>
<?php 
include 'includes/results.php';	

include 'includes/footer.php'; 
?>