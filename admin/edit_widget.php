<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) 
{

	$id = (int)mres(trim($_GET['id']));
	
	$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `widgets` WHERE `id`='$id'"));
	
	$name = $array['widget'];
	
	$font = $array['font'];
	
	$status = $array['status'];
	
	if(!isset($name))
		header("Location: widget.php");

} 
else 
{

	header("Location: widget.php");

}
?>
	<title>Edit Widget: <?php echo(get_title());?></title>
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
						<i class="fa fa-wordpress"></i> Edit Widget <small>Edit <?php echo ucfirst($name); ?></small>
					</h1>
				</div>
			</div>
			<div class="page-content">
				<div class="margin_sides">
					<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
						<input type="hidden" name="id" id="widget_id" value="<?php echo $id; ?>" >
						<div class="form-group">
							<label>Title</label> 
							<input type="text" class="form-control" id="widget_name" placeholder="Enter Widget Name" value="<?php echo ($name); ?>" />
						</div>
						<div class="form-group">
						<label>Status</label></br>
						<?php 
						if($status)
						{ 
						?>							
							<input class="my_checkbox" id="publish" type="checkbox"   checked="checked" />
						<?php 
						} 
						else 
						{ 
						?>
							<input class="my_checkbox" id="publish"  type="checkbox" /> 
						<?php 
						} 
						?>
						</div>
						
						<div class="form-group">
							<button id="iconpicker" data-icon="<?php echo $font ; ?>" class="btn btn-default" role="iconpicker"></button></br>
							<div id="show_icon" class="btn btn-primary btn-xs mrg-10-top"><?php echo $font ; ?></div>
						</div>
						
						<div class="form-group">
							<a href="widget.php"><button type="button" class="btn btn-default"><i class="fa fa-chevron-left"></i> Back </button></a> <a onclick="edit_widget()"><button name="submit" type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	function edit_widget()
	{
	    var id = document.getElementById('widget_id').value
	    var widget_name = document.getElementById('widget_name').value;
	    var font = 'font';
	    var check_status = $('#publish:checked').val();
		if(check_status == 'on')
		var status = 1;
		else
		var status = 0;
		swal({   title: "Are you sure?",   text: "Do you really want to update this widget?",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#2CA86A",   confirmButtonText: "Yes, update it!",   closeOnConfirm: false }, function(){
			$.ajax({
				type: "POST",
				url: "action.php",
				data: {id:id,widget_name:widget_name,font:font,status:status},
				cache: false,
				success: function(result)
				{		
				    if(result == 0)
					swal("Waring!", "Enter valid Entry !", "warning");
					else
					swal("Updated!", "Your selected widget has been Updated successfully!", "success");
				}
			});	 
		});  
	}
	</script>
	<script>
	$('#iconpicker').on('change', function(e) {
		$('#show_icon').html(e.icon);
		var id = document.getElementById('widget_id').value
	    var widget_name = document.getElementById('widget_name').value;
	    var font = e.icon;
	    var check_status = $('#publish:checked').val();
		if(check_status == 'on')
		var status = 1;
		else
		var status = 0;
		$.ajax({
			type: "POST",
			url: "action.php",
			data: {id:id,widget_name:widget_name,font:font,status:status},
			cache: false,
			success: function(result)
			{		
			}
		});	 
	});
	</script>
	<?php include dirname(__FILE__) . '/includes/footer.php'; ?>
	</body>
</html>