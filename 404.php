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

include 'includes/head.php';
?>
<title><?php echo($lang_array['404_page_not_found']); ?></title>
<meta name="description" content="<?php echo($lang_array['404_meta_description']); ?>">
<?php

include 'includes/header.php';

include 'includes/searchbar.php';
?>
<div id="default-page-loader">
</div>
<div id="default-index-page">
	<div class="text-center" id="page_404">
		<div class="container">
			<h1><?php echo $_SESSION['oops'];?></h1>

			<h3><?php echo $_SESSION['404-page-not-found'];?></h3>

			<div>
				<button class="btn btn-md btn-primary" type="button" onclick='change_pages("home","Home");'>
					<li class="fa fa-home"></li> <?php echo $_SESSION['take-me-home'];?>
				</button>
				<button class="btn btn-md btn-info" type="button" onclick="contact_page('contact');">
					<li class="fa fa-envelope"></li> <?php echo $_SESSION['Contact Us'];?>
				</button>
			</div>
		</div>
		</br></br>	
		<?php include 'includes/ad-tray-728.php'; ?>		
	</div>
</div>
<?php 

include 'includes/results.php';	

include 'includes/footer.php';

 ?>