<?php
include dirname(__FILE__) . '/includes/header.php';
 
include dirname(__FILE__) . '/includes/header_under.php';

$error = false;

$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

if(isset($_GET['id']) && $_GET['id'] != '') 
{
	
	$id = mres(trim($_GET['id']));
	
	$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `language` WHERE id = '$id'"));
	
	$lang_status = $array['status'];
	
	$language_name = $array['lang_name'];
	
	$RTL_status = $array['RTL_status'];
	
	if(!isset($array['id']))
	{	
	    header("Location: language_setting.php");
	}

} 
else if(isset($_POST['submit']))
{
    if($_SESSION[$csrfVariable] != $_POST['csrf'])
    $error = "Session Expired! Click on Update button again.";
	$array = array();	
	$array['Contact Us']=xssClean(mres($_POST['contact_us']));
	$array['Contact']=xssClean(mres($_POST['contact']));
	$array['Search']=xssClean(mres($_POST['search']));
	$array['Placeholder']=xssClean(mres($_POST['placeholder']));
	$array['Buy']=xssClean(mres($_POST['buy']));
	$array['WhoIs']=xssClean(mres($_POST['whois']));
	$array['More TLDs']=xssClean(mres($_POST['more_tld']));
	$array['Invalid Domains']=xssClean(mres($_POST['invalid_domain_enter']));
	$array['Buy Now']=xssClean(mres($_POST['buynow']));
	$array['Name']=xssClean(mres($_POST['name']));
	$array['Enter Your Name']=xssClean(mres($_POST['enter_your_name']));
	$array['Email Address']=xssClean(mres($_POST['email_address']));
	$array['Enter Your Email']=xssClean(mres($_POST['email_your_email']));
	$array['Subject']=xssClean(mres($_POST['subject']));
	$array['Enter a Subject']=xssClean(mres($_POST['enter_subject']));
	$array['Enter Captcha Code']=xssClean(mres($_POST['enter_captcha_code']));
	$array['Enter Code']=xssClean(mres($_POST['enter_code']));
	$array['Your Message']=xssClean(mres($_POST['your_message']));
	$array['Enter Your Message']=xssClean(mres($_POST['enter_your_message']));
	$array['Send Message']=xssClean(mres($_POST['send_message']));
	$array['Powered By']=xssClean(mres($_POST['powered_by']));
	$array['All Rights Reserved']=xssClean(mres($_POST['right_reserved']));
	$array['Incorrect Information']=xssClean(mres($_POST['incorrect_information']));
	$array['Invalid Captcha']=xssClean(mres($_POST['invalid_captcha']));
	$array['Empty Captcha']=xssClean(mres($_POST['empty_captcha']));
	$array['Success Contact Message']=xssClean(mres($_POST['success_contact']));
	$array['Invalid Email']=xssClean(mres($_POST['invalid_email']));
	$array['Invalid Name']=xssClean(mres($_POST['invalid_name']));
	$array['Language']=xssClean(mres($_POST['language']));
	$array['social_message']=xssClean(mres($_POST['social_message']));
	$array['oops']=xssClean(mres($_POST['oops']));
	$array['404-page-not-found']=xssClean(mres($_POST['404-page-not-found']));
	$array['take-me-home']=xssClean(mres($_POST['take-me-home']));
	$array['email_required']=xssClean(mres($_POST['email_required']));
	$array['name_required']=xssClean(mres($_POST['name_required']));
	$array['message_required']=xssClean(mres($_POST['message_required']));
	$array['field_empty']=xssClean(mres($_POST['field_empty']));
	$array['Updated Date']=xssClean(mres($_POST['updated_date']));
	$array['Creation Date']=xssClean(mres($_POST['creation_date']));
	$array['REGISTRAR INFO']=xssClean(mres($_POST['register_info']));	
	$array['Whois Server']=xssClean(mres($_POST['whois_server']));	
	$array['Referral URL']=xssClean(mres($_POST['referral_url']));	
	$array['NAME SERVERS']=xssClean(mres($_POST['name_servers']));	
	$array['IMPORTANT DATES']=xssClean(mres($_POST['important_dates']));	
	$array['Updated']=xssClean(mres($_POST['updated']));	
	$array['Created']=xssClean(mres($_POST['created']));	
	$array['Expires']=xssClean(mres($_POST['expiry']));	
	$array['SITE STATS']=xssClean(mres($_POST['site_stats']));	
	$array['Google Page Rank']=xssClean(mres($_POST['google_page_rank']));	
	$array['Domain Age']=xssClean(mres($_POST['domain_age']));	
	$array['SEO STATS']=xssClean(mres($_POST['seo_stats']));	
	$array['Alexa Rank']=xssClean(mres($_POST['alexa_rank']));	
	$array['Total Backinks']=xssClean(mres($_POST['total_backinks']));	
	$array['Domain Authority']=xssClean(mres($_POST['domain_authority']));	
	$array['Your IP address is']=xssClean(mres($_POST['ip_address']));	
	$array['Check Whois']=xssClean(mres($_POST['check_whois']));	
	$array['Invalid OR Restricted Domain']=xssClean(mres($_POST['invalid_or_restricted_domain']));	
	$array['RAW REGISTRAR DATA']=xssClean(mres($_POST['raw_register_data']));
	$array['Home']=xssClean(mres($_POST['home_page']));
	$array['Recent']=xssClean(mres($_POST['recent_page']));
	$array['Recently Checked Websites']=xssClean(mres($_POST['recently_checked_websites']));
	$array['Tld Not Supported']=xssClean(mres($_POST['tldnotsupported']));
	$language_name=xssClean(mres($_POST['language_name']));
	$edit_file=xssClean(mres($_POST['edit_file']));	
	if($_POST['lang_status']=="on") 
		$lang_status =1;
	else
		$lang_status =0;
	if($_POST['rtl_status']=="on") 
		$RTL_status =1;
	else
		$RTL_status =0;			
	$_SESSION['language_status'] = $lang_status;	
	$query=mysql_fetch_array(mysql_query("SELECT `lang_name` FROM language WHERE lang_file='$edit_file'"));
	$old_language = $query['lang_name'];
	if(!$error && $language_name != 'lang_array')
	{
		mysqlQuery("UPDATE `language` SET status='$lang_status',lang_name='$language_name',RTL_status='$RTL_status' WHERE lang_file='$edit_file'");	
		mysqlQuery("UPDATE `page_language` SET language='$language_name' WHERE language='$old_language'");	
		mysqlQuery("UPDATE `widget_language` SET language='$language_name' WHERE language='$old_language'");	
		$encode=json_encode($array);
		file_put_contents("../language/".$edit_file,$encode); 
		unset($_SESSION['language_set']);
		unset($_SESSION['reset_language']);
	}
	else if(!$error)
    $error = "This language name is restricted for some reason !";
}
else
{
	header("Location: language_setting.php");
}

$key = sha1(microtime());

$_SESSION[$csrfVariable] = $key;	
?>
	<title>Edit Language: <?php echo(get_title());?></title>
	</head>
	<body>
	<?php include "includes/top_navbar.php"; ?>
	<div id="wrapper">		
		<div id="page-wrapper">
			<div class="row page-ttl">
				<div class="col-lg-12">
					<h1>
					<i class="fa fa-language"></i> <?php echo ucfirst($language_name); ?> Language Settings <small>Edit <?php echo ucfirst($language_name); ?> Language</small>
					</h1>
				</div>
			</div>
			<div class="page-content">
				<div class="margin_sides">
					<div class="row">
						<?php 
						if(isset($_POST['submit']))
						{ 
						?>
						<div class="alert alert-success alert-dismissable col-lg-8 col-md-8 col-sm-8 col-xs-12">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check-square-o"></i> <?php echo $lang_array['language_update_success'];?>
						</div>
						<?php 
						} 
						?> 
						<div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
							<form method="post" action="edit_language.php" enctype="multipart/form-data">								
								<div class="table-responsive">
									<table id="example" class="table table-striped table-bordered table-hover table-striped tablesorter" cellspacing="0" width="100%">
									<?php 
									if(isset($_GET['id']))
									{
										$id = trim($_GET['id']);
										$sql = mysql_fetch_array(mysqlQuery("SELECT lang_file,status FROM language WHERE id = '$id'"));
										if(isset($sql['lang_file']))
										{
										
											$_SESSION['file'] = $sql['lang_file'];									
											
											$json = file_get_contents('../language/'.$_SESSION['file']);
											
											$data=json_decode($json, true);
											
										}	
										
									}
									else
									{
									    $json = file_get_contents('../language/'.$_SESSION['file']);
											
										$data=json_decode($json, true);
									}
									
									?>
									    <thead>
											<tr>
												<th >Language</th>
												<th >Edit Language</th>
											</tr>
										</thead>
										<tbody id="list_language">
										    <input name="edit_file" value="<?php echo $_SESSION['file'] ;?>" type="hidden" />
											<tr>
												<th >Language Name</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $language_name;?>" placeholder="Enter Language Name" required name="language_name"  required=""></td>
											</tr>
											<tr>
												<th width="130px">Contact Us</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="contact_us" value="<?php echo $data['Contact Us']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Contact</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="contact" value="<?php echo $data['Contact']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Search</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="search" value="<?php echo $data['Search']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter Your Domain Name</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="placeholder" value="<?php echo $data['Placeholder']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Buy</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="buy" value="<?php echo $data['Buy']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">WhoIs</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="whois" value="<?php echo $data['WhoIs']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">More TLDs</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="more_tld" value="<?php echo $data['More TLDs']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Invalid Domains</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="invalid_domain_enter" value="<?php echo $data['Invalid Domains']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Buy Now</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="buynow" value="<?php echo $data['Buy Now']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Name</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="name" value="<?php echo $data['Name']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter Your Name</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="enter_your_name" value="<?php echo $data['Enter Your Name']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Email Address</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="email_address" value="<?php echo $data['Email Address']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter Your Email</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="email_your_email" value="<?php echo $data['Enter Your Email']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Subject</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="subject" value="<?php echo $data['Subject']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter a Subject</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="enter_subject" value="<?php echo $data['Enter a Subject']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter Captcha Code</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="enter_captcha_code" value="<?php echo $data['Enter Captcha Code']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter Code</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="enter_code" value="<?php echo $data['Enter Code']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Your Message</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="your_message" value="<?php echo $data['Your Message']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Enter Your Message</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="enter_your_message" value="<?php echo $data['Enter Your Message']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Send Message</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="send_message" value="<?php echo $data['Send Message']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Powered By</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="powered_by" value="<?php echo $data['Powered By']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">All Rights Reserved</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="right_reserved" value="<?php echo $data['All Rights Reserved']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Language</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="language" value="<?php echo $data['Language']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Incorrect Information</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="incorrect_information" value="<?php echo $data['Incorrect Information']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Invalid Captcha</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="invalid_captcha" value="<?php echo $data['Invalid Captcha']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Empty Captcha</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="empty_captcha" value="<?php echo $data['Empty Captcha']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Email Send Successfully</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="success_contact" value="<?php echo $data['Success Contact Message']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Invalid Email</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="invalid_email" value="<?php echo $data['Invalid Email']; ?>" required=""></td>
											</tr>
											<tr>
												<th width="130px">Invalid Name</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" name="invalid_name" value="<?php echo $data['Invalid Name']; ?>" required=""></td>
											</tr>
											<tr>
												<th>Never miss a single update!</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['social_message']; ?>" name="social_message"  required=""></td>
											</tr>
											<tr>
												<th>Oops!</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['oops']; ?>" name="oops"  required=""></td>
											</tr>
											<tr>
												<th>404 Page Not Found</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['404-page-not-found']; ?>" name="404-page-not-found"  required=""></td>
											</tr>
											<tr>
												<th>Take Me Home</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['take-me-home']; ?>" name="take-me-home"  required=""></td>
											</tr>
											<tr>
												<th>Email is Required</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['email_required']; ?>" name="email_required"  required=""></td>
											</tr>
											<tr>
												<th>Message Required</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['message_required']; ?>" name="message_required"  required=""></td>
											</tr>
											<tr>
												<th>Name is Required</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['name_required']; ?>" name="name_required"  required=""></td>
											</tr>
											<tr>
												<th>You left Some Fields empty</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['field_empty']; ?>" name="field_empty"  required="field_empty"></td>
											</tr>
											<tr>
												<th>Updated Date</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Updated Date']; ?>"  name="updated_date"  required=""></td>
											</tr>
											<tr>
												<th>Creation Date</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Creation Date']; ?>"  name="creation_date"  required=""></td>
											</tr>
											<tr>
												<th>REGISTRAR INFO</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['REGISTRAR INFO']; ?>"  name="register_info"  required=""></td>
											</tr>
											<tr>
												<th>Whois Server</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Whois Server']; ?>"  name="whois_server"  required=""></td>
											</tr>
											<tr>
												<th>Referral URL</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Referral URL']; ?>"  name="referral_url"  required=""></td>
											</tr>
											<tr>
												<th>NAME SERVERS</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['NAME SERVERS']; ?>"  name="name_servers"  required=""></td>
											</tr>
											<tr>
												<th>IMPORTANT DATES</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['IMPORTANT DATES']; ?>"  name="important_dates"  required=""></td>
											</tr>
											<tr>
												<th>Updated</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Updated']; ?>"  name="updated"  required=""></td>
											</tr>
											<tr>
												<th>Created</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Created']; ?>"  name="created"  required=""></td>
											</tr>
											<tr>
												<th>Expires</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Expires']; ?>"  name="expiry"  required=""></td>
											</tr>
											<tr>
												<th>SITE STATS</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['SITE STATS']; ?>"  name="site_stats"  required=""></td>
											</tr>
											<tr>
												<th>Google Page Rank</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Google Page Rank']; ?>"  name="google_page_rank"  required=""></td>
											</tr>
											<tr>
												<th>Domain Age</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Domain Age']; ?>"  name="domain_age"  required=""></td>
											</tr>
											<tr>
												<th>SEO STATS</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['SEO STATS']; ?>"  name="seo_stats"  required=""></td>
											</tr>
											<tr>
												<th>Alexa Rank</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Alexa Rank']; ?>"  name="alexa_rank"  required=""></td>
											</tr>
											<tr>
												<th>Total Backinks</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Total Backinks']; ?>"  name="total_backinks"  required=""></td>
											</tr>
											<tr>
												<th>Domain Authority</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Domain Authority']; ?>"  name="domain_authority"  required=""></td>
											</tr>
											<tr>
												<th>Your IP address is</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Your IP address is']; ?>"  name="ip_address"  required=""></td>
											</tr>
											<tr>
												<th>Check Whois</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Check Whois']; ?>"  name="check_whois"  required=""></td>
											</tr>
											<tr>
												<th>Invalid OR Restricted Domain</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Invalid OR Restricted Domain']; ?>"  name="invalid_or_restricted_domain"  required=""></td>
											</tr>
											<tr>
												<th>RAW REGISTRAR DATA</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['RAW REGISTRAR DATA']; ?>"  name="raw_register_data"  required=""></td>
											</tr>
											<tr>
												<th>Home</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Home']; ?>"  name="home_page"  required=""></td>
											</tr>
											<tr>
												<th>Recent</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Recent']; ?>"  name="recent_page"  required=""></td>
											</tr>
											<tr>
												<th>Recently Checked Websites</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Recently Checked Websites']; ?>"  name="recently_checked_websites"  required=""></td>
											</tr>
											<tr>
												<th>Tld Not Supported</th>
												<td id="remove" class="ellipsis"><input type="text" class="form-control" value="<?php echo $data['Tld Not Supported']; ?>"  name="tldnotsupported"  required=""></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="form-group">
									<label>Status</label></br>
									<?php
									if($lang_status == 1)
									{
									?>
									<input class="my_checkbox"  name="lang_status" type="checkbox"   checked="checked" />
									<?php 
									}
									else
									{
									?>
									<input class="my_checkbox" name="lang_status" type="checkbox" />
									<?php 
									}
									?>
								</div>
								<div class="form-group">
									<label>RTL</label></br>
									<?php
									if($RTL_status == 1)
									{
									?>
									<input class="my_checkbox"  name="rtl_status" type="checkbox"   checked="checked" />
									<?php 
									}
									else
									{
									?>
									<input class="my_checkbox" name="rtl_status" type="checkbox" />
									<?php 
									}
									?>
								</div>
								<div class="clearfix"></div>
								<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
								<div class="form-group">
									<div class="col-lg-6">
										<a class="btn btn-default" href="language_setting.php"><i class="fa fa-chevron-left"></i> Back</a>
										<button class="btn btn-success" name="submit" type="submit"><i class="fa fa-check"></i> Update</button>
									</div>
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