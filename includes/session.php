<?php 
if(!isset($_SESSION['reset_language']))
	{	
		$sql = mysql_fetch_array(mysql_query("SELECT * FROM language WHERE status=1 ORDER BY display_order"));
		$_SESSION['reset_lang_file'] = $sql['lang_file'];	
		$_SESSION['reset_lang_name'] = $sql['lang_name'];
		$_SESSION['reset_RTL_session'] = $sql['RTL_status'];
		$_SESSION['reset_language'] = 1;	
	}
if(!isset($_SESSION['loader_session']))
	{
		if(f_loader()) 
		$_SESSION['loader_session'] = 1 ; 
	}

if(!isset($_SESSION['language_set']))
{
	$json = file_get_contents('language/'.$_SESSION['reset_lang_file']);
	$data=json_decode($json, true);
	$_SESSION['Contact Us'] = $data['Contact Us'];
	$_SESSION['Contact'] = $data['Contact'];
	$_SESSION['Search'] = $data['Search'];
	$_SESSION['Placeholder'] = $data['Placeholder'];
	$_SESSION['Buy'] = $data['Buy'];
	$_SESSION['WhoIs'] = $data['WhoIs'];
	$_SESSION['More TLDs'] = $data['More TLDs'];
	$_SESSION['Invalid Domains'] = $data['Invalid Domains'];
	$_SESSION['Buy Now'] = $data['Buy Now'];
	$_SESSION['Name'] = $data['Name'];
	$_SESSION['Enter Your Name'] = $data['Enter Your Name'];
	$_SESSION['Email Address'] = $data['Email Address'];
	$_SESSION['Enter Your Email'] = $data['Enter Your Email'];
	$_SESSION['Subject'] = $data['Subject'];
	$_SESSION['Enter a Subject'] = $data['Enter a Subject'];
	$_SESSION['Enter Captcha Code'] = $data['Enter Captcha Code'];
	$_SESSION['Enter Code'] = $data['Enter Code'];
	$_SESSION['Your Message'] = $data['Your Message'];
	$_SESSION['Enter Your Message'] = $data['Enter Your Message'];
	$_SESSION['Send Message'] = $data['Send Message'];
	$_SESSION['Powered By'] = $data['Powered By'];
	$_SESSION['All Rights Reserved'] = $data['All Rights Reserved'];
	$_SESSION['Incorrect Information'] = $data['Incorrect Information'];
	$_SESSION['Invalid Captcha'] = $data['Invalid Captcha'];
	$_SESSION['Invalid Email'] = $data['Invalid Email'];
	$_SESSION['Invalid Name'] = $data['Invalid Name'];	
	$_SESSION['Success Contact Message'] = $data['Success Contact Message'];
	$_SESSION['Empty Captcha'] = $data['Empty Captcha'];
	$_SESSION['Language'] = $data['Language'];
	$_SESSION['social_message'] = $data['social_message'];
	$_SESSION['oops'] = $data['oops'];
	$_SESSION['404-page-not-found'] = $data['404-page-not-found'];
	$_SESSION['take-me-home'] = $data['take-me-home'];
	$_SESSION['email_required'] = $data['email_required'];
	$_SESSION['name_required'] = $data['name_required'];
	$_SESSION['message_required'] = $data['message_required'];
	$_SESSION['field_empty'] = $data['field_empty'];	
	$_SESSION['Updated Date'] = $data['Updated Date'];
	$_SESSION['Creation Date'] = $data['Creation Date'];
	$_SESSION['REGISTRAR INFO'] = $data['REGISTRAR INFO'];
	$_SESSION['Whois Server'] = $data['Whois Server'];
	$_SESSION['Referral URL'] = $data['Referral URL'];
	$_SESSION['NAME SERVERS'] = $data['NAME SERVERS'];
	$_SESSION['IMPORTANT DATES'] = $data['IMPORTANT DATES'];
	$_SESSION['Updated'] = $data['Updated'];
	$_SESSION['Created'] = $data['Created'];
	$_SESSION['Expires'] = $data['Expires'];
	$_SESSION['SITE STATS'] = $data['SITE STATS'];
	$_SESSION['Google Page Rank'] = $data['Google Page Rank'];
	$_SESSION['Domain Age'] = $data['Domain Age'];
	$_SESSION['SEO STATS'] = $data['SEO STATS'];
	$_SESSION['Alexa Rank'] = $data['Alexa Rank'];
	$_SESSION['Total Backinks'] = $data['Total Backinks'];
	$_SESSION['Domain Authority'] = $data['Domain Authority'];
	$_SESSION['Your IP address is'] = $data['Your IP address is'];
	$_SESSION['Check Whois'] = $data['Check Whois'];
	$_SESSION['Invalid OR Restricted Domain'] = $data['Invalid OR Restricted Domain'];
	$_SESSION['RAW REGISTRAR DATA'] = $data['RAW REGISTRAR DATA'];
	$_SESSION['Home'] = $data['Home'];
	$_SESSION['Recent'] = $data['Recent'];
	$_SESSION['Recently Checked Websites'] = $data['Recently Checked Websites'];
	$_SESSION['Tld Not Supported'] = $data['Tld Not Supported'];
	$_SESSION['language_set'] = 1;
}
if(!isset($_SESSION['contact_captcha_status']))
{

	$rows = mysql_fetch_array(mysqlQuery("SELECT `captcha_contact_status` FROM captcha_settings"));

	$_SESSION['contact_captcha_status'] = $rows['captcha_contact_status'];

}
if(!isset($_SESSION['cache_time']))
{
	$rows = mysql_fetch_array(mysqlQuery("SELECT * FROM cache_settings"));
	$_SESSION['whois_time'] = $rows['whois_time'];
	$_SESSION['whois_status'] = $rows['whois_status'];
	$_SESSION['site_stats_time'] = $rows['site_stats_time'];
	$_SESSION['site_stats_status'] = $rows['site_stats_status'];
	$_SESSION['seo_stats_time'] = $rows['seo_stats_time'];
	$_SESSION['seo_stats_status'] = $rows['seo_stats_status'];
	$_SESSION['cache_time'] = 1;
}
if(!isset($_SESSION['moz_api_setting']))
{
	$rows = mysql_fetch_array(mysqlQuery("SELECT * FROM apiSettings"));
	$_SESSION['mozAccessID'] = $rows['mozAccessID'];
	$_SESSION['mozSecretKey'] = $rows['mozSecretKey'];
	$_SESSION['moz_api_setting'] = 1;
}
if(!isset($_SESSION['social_profile']))
{
	$rows=mysql_fetch_array(mysqlQuery("SELECT * FROM social_profiles"));
	$_SESSION['facebook_status'] = $rows['f_status'];
	$_SESSION['twitter_status'] = $rows['t_status'];
	$_SESSION['google_plus_status'] = $rows['g_status'];
	$_SESSION['all_social_profile_status'] = $rows['social_buttons'];
	$_SESSION['facebook_profile'] = $rows['facebook'];
	$_SESSION['twitter_profile'] = $rows['twitter'];
	$_SESSION['google_plus_profile'] = $rows['google_plus'];
	$_SESSION['social_profile'] = 1;
}
?> 