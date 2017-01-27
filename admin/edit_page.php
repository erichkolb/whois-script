<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php';

$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

$error =false;
if (isset($_GET['id']) && is_numeric($_GET['id'])) 
{

	$id = (int)mres(trim($_GET['id']));
	
	$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `pages` WHERE `id`='$id'"));
	
	$description = $array['description'];
	
	$keywords = $array['keywords'];
	
	$permalink = $array['permalink'];
	
	$status = $array['status'];

} 
else if(isset($_POST['submit'])) 
{

	if($_SESSION[$csrfVariable] != $_POST['csrf'])
    $error = true;

	$id = (int)mres(trim($_POST['id']));
	
	$description = xssClean(mres($_POST['description']));
	
	$keywords = xssClean(mres($_POST['keywords']));
	
	$permalink = str_replace(array('"'), '', $_POST['permalink']);
	
	$permalink = xssClean(mres($permalink));
	
	if($_POST['publish'] == "on")
	$status = 1;
	else
	$status = 0;
	
	$content = xssClean(mres($_POST["content"]));
	
	if (strlen($permalink) > 70 || strlen($permalink) < 1)
	$error = $lang_array['edit_page_error'];
	
	if (!$error)
	{
		update_page($id, $permalink,$description, $keywords, $status);
	}
	
} 
else 
{

	header("Location: pages.php");

}

$key = sha1(microtime());

$_SESSION[$csrfVariable] = $key;
?>
	<title>Page Settings: <?php echo(get_title());?></title>
	</head>
	<body>
	<?php include "includes/top_navbar.php"; ?>
	<script type="text/javascript">
		$(function() {$("#keywords").tagsInput({width:"auto"});});	
    </script>
	<div id="wrapper">
		<div id="page-wrapper">
			<div class="row page-ttl">
				<div class="col-lg-12">
					<h1>
						<i class="fa fa-files-o"></i> Edit Page <small>Edit <?php echo ucfirst($permalink); ?> page</small>
					</h1>
				</div>
			</div>
			<div class="page-content">
				<div class="margin_sides">
					<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<?php
					if ($error)
					{ 
					?>
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-exclamation-triangle"></i> <?php echo ($error); ?>
					</div>
					<?php
					} 
					else if(isset($_POST['submit']))
					{ 
					?>
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-square-o"></i> <?php echo $lang_array['edit_page_success']; ?>
					</div>
					<?php
					} 
					?>
					<form role="form" action="edit_page.php" method="post">
						<input type="hidden" name="id" value="<?php echo $id; ?>" >
						<div class="form-group">
							<label>Permalink</label> 
							<input type="text" class="form-control" name="permalink" placeholder="Permalink" value="<?php echo ($permalink); ?>" />
						</div>
						<div class="form-group">
							<label>Meta Description</label> 
							<textarea class="form-control" maxlength="160" style="width:100%;height:100px" rows="15" name="description" required><?php echo($description); ?></textarea>
						</div>
						<div class="form-group">
							<label>Meta Keywords</label> 
							<textarea class="form-control" maxlength="60" style="width:100%;height:80px" rows="15" id="keywords" name="keywords" required><?php echo($keywords); ?></textarea>
						</div> 
						<div class="form-group">
						<label>Status</label></br>
						<?php
						if($status)
						{ 
						?>							
							<input class="my_checkbox" name="publish" type="checkbox"   checked="checked" />
						<?php 
						} 
						else 
						{ 
						?>
							<input class="my_checkbox" name="publish"  type="checkbox" name="com_status" /> 
						<?php 
						} 
						?>
						</div>
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<a href="pages.php"><button type="button" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back </button> <button name="submit" type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update</button>
						</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include dirname(__FILE__) . '/includes/footer.php'; ?>
	</body>
</html>