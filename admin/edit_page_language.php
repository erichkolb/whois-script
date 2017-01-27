<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php';

$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

$error =false;
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['language'])) 
{

	$id = (int)mres(trim($_GET['id']));
	
	$language = mres(trim($_GET['language']));
	
	$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `page_language` WHERE `id`='$id' AND language = '$language'"));
	
	if(isset($array['id']))
	{	
		$content = $array['content'];
		
		$title = $array['title'];
	}
	else
	{
	    header("Location: pages.php");
	}

} 
else if(isset($_POST['submit'])) 
{

	if($_SESSION[$csrfVariable] != $_POST['csrf'])
    $error = true;

	$id = (int)mres(trim($_POST['id']));
	
	$content = xssClean(mres($_POST['content']));
	
	$title = str_replace(array('"'), '', $_POST['title']);
	
	$title = xssClean(mres($title));
	
	$language = xssClean(mres($_POST['language']));
	
	if($content == "")
	$error = $lang_array['empty_content'];
	
	if (!$error)
	{
		edit_page_language($id,$content,$language,$title);
	}
} 
else 
{

	header("Location: pages.php");

}

$key = sha1(microtime());

$_SESSION[$csrfVariable] = $key;

if(isset($language))
$_SESSION['edit_language'] = $language;
if(isset($id))
$_SESSION['edit_id'] = $id;
?>
	<title>Edit Page Language: <?php echo(get_title());?></title>
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
						<i class="fa fa-files-o"></i> <?php echo ucfirst($title); ?> Page Language <small>Edit <?php echo ucfirst($_SESSION['edit_language']) ; ?> Language of <?php echo ucfirst($title); ?> page</small>
					</h1>
				</div>
			</div>
			<div class="page-content">
				<div class="margin_sides">
					<div class="row">
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
							<form role="form" action="edit_page_language.php" method="post">
								<input type="hidden" name="language" value="<?php echo $_SESSION['edit_language']; ?>" >
								<input type="hidden" name="id" value="<?php echo $_SESSION['edit_id']; ?>" >
								<div class="form-group">
									<label>Page Title</label> 
									<?php 
									if(isset($_POST['submit']))
									{
									?>
									<input type="text" class="form-control"   name="title" placeholder="Page Name" value="<?php echo $_POST['title']; ?>" /> 
									<?php 
									}
									else 
									{
									?>
									<input type="text" class="form-control"   name="title" placeholder="Page Name" value="<?php echo $title; ?>" /> 
									<?php 
									}
									?>
								</div>
								<div class="form-group">
									<label>Content</label> 
									<textarea id="content" class="form-control" rows="15" name="content">
										<?php 
										if(isset($_POST['submit']))
										echo $_POST['content'];
										else 
										echo ($content);
										?>
									</textarea>
								</div>
								<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
								<div class="form-group">
									<a href="page_language.php?id=<?php echo $_SESSION['edit_id']; ?>"><button type="button" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back </button></a> <button name="submit" type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include dirname(__FILE__) . '/includes/footer.php'; ?>
	</body>
</html>