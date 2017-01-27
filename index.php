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
<title><?php echo get_title(); ?></title>		
<meta name="description" content="<?php echo(get_description()); ?>"/>
<meta name="keywords" content="<?php echo(get_tags()); ?>"/>

<!-- Twitter Card data -->
<meta name="twitter:card" content="<?php echo(get_title()); ?>"/>
<meta name="twitter:title" content="<?php echo(get_title()); ?>">
<meta name="twitter:description" content="<?php echo(get_description()); ?>">
<meta name="twitter:image" content="<?php echo(rootPath()); ?>/static/images/<?php echo get_logo(); ?>?<?php echo(time()); ?>"/>

<!-- Open Graph data -->
<meta property="og:title" content="<?php echo(get_title()); ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="<?php echo(getAddress()); ?>"/>
<meta property="og:image" content="<?php echo(rootPath()); ?>/static/images/<?php echo get_logo(); ?>?<?php echo(time()); ?>"/>
<meta property="og:description" content="<?php echo(get_description()); ?>"/>
<meta property="og:site_name" content="<?php echo(get_title()); ?>"/>
<?php
include 'includes/header.php';

include 'includes/searchbar.php';

if(isset($_SESSION['reset_lang_name']))
{
	$language = $_SESSION['reset_lang_name'];
	$mysql = mysqlQuery("SELECT id,font FROM widgets WHERE status = 1 ORDER BY display_order");
}
?>
<div id="default-page-loader">
</div>
<div id="default-index-page">
	<?php
	include 'includes/ad-tray-728.php';
	?>	
	<div class="whois-f-d text-center">
		<div class="container">
			<div class="row">
			<?php 
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
				?>
				<?php if(layoutRTL()) { ?>
				<div class="col-xs-6 col-md-3 pull-right">
				<?php } else { ?>
				<div class="col-xs-6 col-md-3">
				<?php } ?>
					<div class="inner-mrg z-depth-1">
						<i class="glyphicon <?php echo $font; ?> glyphicon-fontsize"></i>
						<h1><?php echo $rows['title']; ?></h1>
						<?php echo db_decode($rows['content']); ?>
					</div>
				</div>
			<?php 
			$i++;
			}
			?>
			</div>
		</div>
	</div>

</div>
<?php
include 'includes/results.php';	

include 'includes/footer.php';
?>