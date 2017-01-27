<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php';

if(isset($_POST['arrayorder']))
{
	$array = $_POST['arrayorder'];
	
	if($_POST['update'] == "update")
	{
	
		$count = 1;
		
		foreach ($array as $idval)
		{
			
			$sql_update = mysqlQuery("UPDATE widgets SET display_order='$count' WHERE id='$idval'");
			
			$count++;
		}
		
	}
	
}
?>
	<title>Widget Settings: <?php echo(get_title());?></title>
	</head>
	<body>
	<?php include "includes/top_navbar.php"; ?>
	<div id="wrapper">
		<div id="page-wrapper">
			<div class="row page-ttl">
				<div class="col-lg-12">
					<h1>
						<i class="fa fa-wordpress"></i> Widgets Settings <small>Modify and update widgets</small>
					</h1>
				</div>
			</div>
			<div class="page-content">
				<div class="margin_sides">
					<div class="row">
						<div class="col-md-8">
						   <?php 
							$id =trim(mres($_GET['id']));
							$array = mysql_fetch_array(mysqlQuery("SELECT id  FROM pages WHERE display_order = '$id'"));	
							if(isset($array['id']))
							{ 
							?>
							<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-square-o"></i> <?php echo $lang_array['add_page_success'];?>
							</div>
							<?php 
							} 
							?>
							<div class="awidget">
								<?php

								$sql_select=mysqlQuery("SELECT * FROM widgets ORDER BY display_order");

								$count=mysql_num_rows($sql_select);

                                ?>
								<div class="awidget-body">
									<div class="table_header" style="width:100%;line-height: 1px">
										<div style="width:35%;float:left;white-space: nowrap">widget</div>
										<div id="smalli" class="hidden-xs" style="width:45%;float:left;text-align: center;">Action</div>
										<div id="smalli" class="visible-xs" style="width:65%;float:left;text-align: center;">Action</div>
										<div class="hidden-xs" style="width:20%;float:left;text-align: center">Status</div>
									</div>
									<div id="list">
									<ul>
										<?Php 
										$i =1;								
										while($row=mysql_fetch_array($sql_select))
										{

											$id     = stripslashes($row['id']);

											$text   = stripslashes(ucfirst($row['widget']));

											$edit   = '<a href="edit_widget.php?id=' . $row['id'] . '" title="Edit ' . $text . '" class="small">Edit</a>';
											
											$language   = '<a href="widget_language.php?id=' . $row['id'] . '" title="Edit ' . $text . ' Language" class="small">Language</a>';

											$stat   = stripslashes($row['status']);

											?>
											<li id="arrayorder_<?php echo $id; ?>">
												<div class="small" style="width:100%;float:left;line-height: 1px">
													<div style="width:35%;float:left;line-height: 1px;white-space: nowrap">
														<?php echo $text; ?>
													</div>
													<div class="small smalli hidden-xs" style="width:45%;float:left;line-height: 1px;text-align: center; font-size:1.1em;">
														<?php 
														echo ($edit . " - ". $language);
														?>
													</div>
													<div class="small smalli visible-xs" style="width:65%;float:left;line-height: 1px;text-align: center; font-size:1.1em;">
														<?php 
														echo ($edit . " - ". $language);
														?>
													</div>
													<div class="hidden-xs" style="width:20%;float:left;line-height: 1px;text-align: center">
														<?php if($stat == 0) { echo ('OFF'); } else { echo ('ON'); } ?>
													</div>
												</div>
											</li>
											<?php 
											$i++;
										} 
										?>
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$(function(){
			$("#list ul").sortable({ opacity: 0.8, cursor: 'move', update: function(){
				var order = $(this).sortable("serialize") + '&update=update';
				$.post("widget.php", order);
			}                                                                 
			});
		});
	});   
	</script>
	</body>
</html>