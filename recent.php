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

if(isset($_GET['p']) && $_GET['p'] !="")
	$page = trim($_GET['p']);
else
	$page = 1;
	
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
	<title><?php echo($lang_array['recently_checked_websites'] . " " . $lang_array['dash-page'] . " " . $cur_page); ?></title>
	<meta name="description" content="<?php echo($lang_array['recent_websites_meta_description']); ?>" />
	<meta name="keywords" content="<?php echo($lang_array['recent_websites_meta_keywords']); ?>" />
	
	<meta name="twitter:card" content="<?php echo($lang_array['recently_checked_websites']); ?>" />
	<meta name="twitter:title" content="<?php echo($lang_array['recently_checked_websites'] . " " . $lang_array['dash-page'] . " " . $cur_page); ?>" />
	<meta name="twitter:description" content="<?php echo($lang_array['recent_websites_meta_description']); ?>" />
	<meta name="twitter:image" content="<?php echo(rootPath()); ?>/static/images/whois.png?<?php echo(time()); ?>"/>
	
	<meta property="og:title" content="<?php echo($lang_array['recently_checked_websites']); ?> - Page <?php echo($cur_page); ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo(getAddress()); ?>" />
	<meta property="og:image" content="<?php echo(rootPath()); ?>/static/images/whois.png?<?php echo(time()); ?>"/>
	<meta property="og:description" content="<?php echo($lang_array['recent_websites_meta_description']); ?>" /> 
	<meta property="og:site_name" content="<?php echo(get_title()); ?>" />
    <?php
	include 'includes/header.php';

	include 'includes/searchbar.php';
	?>
<div id="default-page-loader">
</div>
<div id="default-index-page">
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
							<div class="thumbnail thumbs-shadow">
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
</div>
<?php 
include 'includes/results.php';

include 'includes/footer.php'; 
?>