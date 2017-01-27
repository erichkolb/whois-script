<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php'; 

$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

$error = false;

if(isset($_GET['id']) && is_numeric($_GET['id'])) 
{

	$id = (int)mres(trim($_GET['id']));
	
	$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `widgets` WHERE `id`='$id'"));
	
	if(isset($array['id']))
		$widget = $array['widget'];
	else
		header("Location: widget.php");

} 
else if(isset($_POST['submit'])) 
{

	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$error = "Session Expired! Click on ADD button again.";

	$id = (int)mres(trim($_POST['id']));
	
	$title = str_replace(array('"'), '', $_POST['title']);
	
	$title = xssClean(mres($title));
	
	$content = xssClean(mres($_POST['content']));
	
	$language = xssClean(mres($_POST['language']));
	
	if($language == '')
		$error = $lang_array['select_language'];
	
	$array = mysql_fetch_array(mysqlQuery("SELECT id FROM widget_language WHERE id='$id' AND language ='$language';"));
	
	if(isset($array['id']))
		$error = $lang_array['add_page_language'];
	if($content == "" && !$error)
		$error = $lang_array['empty_content'];
	
	if(!$error)
		add_widget_language($id,$title,$content,$language);

} 
else 
{

	header("Location: widget.php");

}

$key = sha1(microtime());

$_SESSION[$csrfVariable] = $key;
?>
	<title>ADD Widget Language: <?php echo(get_title());?></title>
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
							<i class="fa fa-wordpress"></i> Add <?php echo ucfirst($widget); ?> language <small> Add new language for <?php echo ucfirst($widget); ?></small>
						</h1>
					</div>
				</div><!-- /.row -->
				<div class="page-content">
					<div class="margin_sides">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<?php
					if($error)
					{ 
					$error = "<b>Error : </b>" . $error; 
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
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-square-o"></i> Add Language Successfully !
					</div>
					<?php
					}
					if(isset($_GET['id']))
						$id = trim($_GET['id']);
					else
						$id = trim($_POST['id']);
					
					$sql = mysqlQuery("SELECT `language` FROM widget_language WHERE id = '$id'");
					
					$count = mysql_num_rows($sql);
					
					$exist_language = '';
					
					while($rows= mysql_fetch_array($sql))
					{
					
						$exist_language .= "lang_name != '" .$rows['language'] . "' AND ";
						
					}
					
					$exist_language = rtrim($exist_language," AND ");
					
					$c=1;
					
					if($count > 0)
						$mysql = mysqlQuery("SELECT lang_name FROM language WHERE ".$exist_language);
					else
					    $mysql = mysqlQuery("SELECT lang_name FROM language");
					
					$language_exist	= mysql_num_rows($mysql);
                   
				    if($language_exist > 0)		
                    {					
					?>
					<form role="form" action="add_widget_language.php" method="post">
					    <?php 
						if(isset($_POST['id']))
						{
						?>
						<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" >
						<?php 
						}
						else
						{
						?>
						<input type="hidden" name="id" value="<?php echo $id; ?>" >
						<?php
						}
						?>
						<div class="form-group">
							<label>Widget Title</label> 
							<?php 
							if(isset($_POST['title']))
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
                            <?php 
							if(isset($_POST['content']))
							{
							?>							
							<textarea id="content" class="form-control" rows="15" name="content">
                            <?php echo $_POST['content']; ?>						
							</textarea>
							<?php 
							}
							else
							{
							?>							
							<textarea id="content" class="form-control" rows="15" name="content">								
							</textarea>
							<?php 
							}
							?>
						</div> 
						<div class="form-group">
						<label>Language</label> 
						   <?php 
							?>
							<select  class="form-control" id="language" name="language">
								<option value=''>Select Language ...</option>
								<?php 
								while($rows = mysql_fetch_array($mysql))
								{
									?>
									<option value="<?php echo $rows['lang_name']; ?>"><?php echo $rows['lang_name']; ?></option>
									<?php 
									$c++;
								}
								?>
							</select>
						</div> 
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<a href="widget_language.php?id=<?php echo $id ; ?>"><button type="button" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back </button></a>                           							
							<button name="submit" type="submit" class="btn btn-success"><i class="fa fa-plus"></i> Add Language</button>
						</div>
					</form>
					<?php 
					}
					else
					{
					?>
					<div class="panel panel-success">
						<div class="panel-body page-list-setting">
							<div class='col-md-12 content text-center'>				
								<h1>All Language Exist For this Widget !</h1>
							</div>
						</div>	
					</div>	
					<div class="clearfix"></div>
					<div class="col-lg-6">
						<a href="widget_language.php?id=<?php echo $id; ?>"><button type="button" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back </button></a>
					</div>
					<?php
					}
					?>
				</div>
						</div>
					</div>
				</div>
			</div>
	</div>
	<?php include dirname(__FILE__) . '/includes/footer.php'; ?>
	</body>
</html>