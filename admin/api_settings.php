<?php
include dirname(__FILE__) . '/includes/header.php'; 

include dirname(__FILE__) . '/includes/header_under.php';
 
$error = false;

$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

function updateApiSettings($mozAccessID,$mozSecretKey) {

	$rows = mysql_num_rows(mysqlQuery("SELECT * FROM `apiSettings`"));
	
	if($rows>0) {

	mysqlQuery("UPDATE `apiSettings` SET `mozAccessID`='$mozAccessID',`mozSecretKey`='$mozSecretKey'");
	
	} else {
	
	mysqlQuery("INSERT INTO `apiSettings`(mozAccessID,mozSecretKey) VALUES('$mozAccessID','$mozSecretKey')");
	
	}
	
}

if(isset($_POST['submit'])) {

	if($_SESSION[$csrfVariable] != $_POST['csrf'])
        $error = true;

	$mozAccessID = mres(trim($_POST["mozAccessID"]));
	
	$mozSecretKey = mres(trim($_POST["mozSecretKey"]));
	
	if(!$error)
		updateApiSettings($mozAccessID,$mozSecretKey);
	
	unset($_SESSION['moz_api_setting']);
}

$key = sha1(microtime());

$_SESSION[$csrfVariable] = $key;

?>
	<title>API Settings: <?php echo(getMetaTitle()) ?></title>
	</head>
	<body>
		<?php 
		include "includes/top_navbar.php"; 
		?>
		<div id="wrapper">
			<div id="page-wrapper">
				<div class="row page-ttl">
					<div class="col-lg-12">
						<h1>
							<i class="fa fa-cog"></i> API Settings <small>Update MOZ API</small>
						</h1>
					</div>
				</div> <!-- /.row -->
				<div class="page-content">
					<div class="margin_sides">
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<span class="label label-danger">Note: Domain Authority Stats Will Not Work Without Free Moz API</span>
							<br>
							<br>
								<form role="form" action="api_settings.php" method="POST">
									<?php if(isset($_POST['mozAccessID'])) { ?>
									
										<div class="alert alert-success alert-dismissable">
											<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
											<i class="fa fa-check-square-o"></i> API Settings Updated successfully
										</div>
										<div class="form-group">
											<label>MOZ AccessID</label>
											<input type="text" class="form-control" name="mozAccessID" value="<?php echo $_POST['mozAccessID']; ?>" placeholder="Enter MOZ Access ID"  />
										</div>
										<div class="form-group">
											<label>MOZ Secret Key</label>
											<input type="text" class="form-control" name="mozSecretKey" value="<?php echo $_POST['mozSecretKey']; ?>" placeholder="Enter MOZ Secret Key" />
										</div>
										<div class="form-group">
											<label>Get Moz API Key From Here <a href="https://moz.com/community/join?redirect=/products/api/keys">Moz Analytics or free community account</a></label>
											
										</div> <?php } else {
										
										$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `apiSettings`"));   
										
										$mozAccessID = $array['mozAccessID'];
										
										$mozSecretKey = $array['mozSecretKey'];
										
										?>
										<div class="form-group">
											<label>MOZ AccessID</label>
											<input type="text" class="form-control" name="mozAccessID" value="<?php echo($mozAccessID); ?>" placeholder="Enter MOZ Access ID" />
										</div>
										<div class="form-group">
											<label>MOZ Secret Key</label>
											<input type="text" class="form-control" name="mozSecretKey" value="<?php echo($mozSecretKey); ?>" placeholder="Enter MOZ Secret Key" />
										</div>
										<div class="form-group">
											<label>Get Moz API Key From Here <a href="https://moz.com/community/join?redirect=/products/api/keys" target="_blank">Moz Analytics or free community account</a></label>
										</div>
										
									<?php } ?>
									<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
									<hr>
									<div class="form-group">
										<button type="submit" name="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> Update</button>
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