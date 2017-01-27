<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php'; 

if(is_numeric($_GET['id'])) 
{

	$id = (int)mres(trim($_GET['id']));
	
	$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `widgets` WHERE `id`='$id'"));
	
	$widget = $array['widget'];
	
	if(!isset($array['id']))
		header("Location: widget.php");
	
}
else 
{
    header("Location: widget.php");
}
?>
	<title>Widget Language Settings: <?php echo(get_title()) ?></title>
    </head>
    <body>
    <?php include "includes/top_navbar.php"; ?>
	<div id="wrapper">
	    <div id="page-wrapper">
			<div class="row page-ttl">
				<div class="col-lg-12">
				<h1>
					<i class="fa fa-wordpress"></i> <?php echo ucfirst($widget); ?> Languages <small>Add, Edit, or Delete <?php echo ucfirst($widget); ?> Languages</small>
				</h1>
				</div>
			</div>
			<ol class="page-content">
				<div class="margin_sides">
					<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
						<div id="language_status">
						    <?php 
							$i=1;
							$id = trim($_GET['id']);
							$sql_select=mysqlQuery("SELECT * FROM widget_language WHERE id = '$id'");
							$total_record = mysql_num_rows($sql_select);
							$count_language=mysql_num_rows(mysqlQuery("SELECT lang_name FROM language "));
							if($total_record != $count_language)
							{
							?>
						    <div class="form-group">
								<a href="add_widget_language.php?id=<?php echo trim($_GET['id']); ?>" class="btn btn-success pull-right panel-btn-top"><i class="fa fa-plus"></i> Add New Language</a>
							</div>
							<?php 
							}
							?>
							<div class="clearfix"></div>
							<?php  
							if($total_record > 0)
							{
								?>
								<form method="post" action="language_setting.php" enctype="multipart/form-data">
									<input type="hidden" name="hidden"/>
									<div class="panel panel-success">
										<div class="panel-heading">
										    <div class="row">
												<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
													<h3 class="panel-title">Language</h3>
												</div>
												<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
													<h3 class="panel-title">Options</h3>
												</div>
											</div>
										</div>
										<div class="panel-body page-list-setting">
											<input type="hidden" name="total_record" value="<?php echo $total_record; ?>" />
											<?php
											while($rows=mysql_fetch_array($sql_select))
											{
											?>
											<div class="col-md-12 list">
												<div class="row">
													<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
														<b><?php echo $rows['language'];?></b>
													</div>
													<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
													<a class="btn btn-primary btn-sm" href="edit_widget_language.php?id=<?php echo trim($_GET['id']); ?>&language=<?php echo $rows['language'];?>" type="submit"><i class="fa fa-edit"></i> <span class="hidden-tab">Modify</span></a> 
													<?php if($total_record > 1) { ?>
													<a class="btn btn-danger btn-sm" onclick="delete_widget_pages('<?php echo trim($_GET['id']); ?>','<?php echo trim($rows['language']); ?>')" type="submit"><i class="fa fa-trash-o"></i> <span class="hidden-tab">Delete</span></a>
													<?php } ?>
													</div>
												</div>
											</div>
											<?php 
											$i++;
											}							
											?>
										</div>									
								</div>
								</form>
								<?php 
							}
							else
							{
							?>
							<div class="panel panel-success">
								<div class="panel-heading">
									<div class="row">
										<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
											<h3 class="panel-title">Language</h3>
										</div>
										<div class="col-lg-6 col-md-6 col-xs-6 col-sm-6">
											<h3 class="panel-title">Options</h3>
										</div>
									</div>
								</div>
								<div class="panel-body page-list-setting">
									<div class='col-md-12 content text-center '>				
										<h1>No Language found for this Widget !</h1>
									</div>
								</div>
							</div>
							<?php
							}
							if($total_record > 0)
							{
							?>
							<div class="clearfix"></div>
							<div class="col-lg-6">
							<a href="widget.php"><button type="button" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back </button></a>
							</div>
							<?php 
							}
							?>
						</div>
					</div>
				</div>
			</ol>
		</div>
	</div>
	<?php include dirname(__FILE__) . '/includes/footer.php'; ?>
	<script>
	function delete_widget_pages(id,language)
	{
	
		swal({   title: "Are you sure?",   text: "You will be able to Deleted this Selected widget!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){
			$.ajax({
				type: "POST",
				url: "action.php",
				data: {id:id,widget_language:language},
				cache: false,
				success: function(result)
				{		
					swal("Deleted!", "Your Selected widget has been deleted !", "success");
					var delay=1000;
					setTimeout(function(){
						window.location="<?php echo rootpath(); ?>/admin/widget_language.php?id="+id;
					},delay); 
				}
			});	
		}); 
	}
	</script>
    </body>
</html>