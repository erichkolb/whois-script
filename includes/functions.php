<?php
if(!isset($_SESSION))
session_start();

error_reporting(E_ERROR | E_PARSE);

require "library/whois.main.php";
require "library/whois.utils.php";
include "NEXstats.php";
require "cache/phpfastcache.php";
phpFastCache::setup("storage","auto");

function valid_url($url) {

	$url = httpify($url);
	
	$url = "http://" . getdomain($url);
	
	$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) && (preg_match("#^http(s)?://[a-z0-9-_.]+\.[a-z]{2,4}#i", $url));
	
	if ($validation)
		return true;
	else
		return false;
	
}
function validURL($url) {
		
	if (!valid_url($url) || bad_domain($url))
		return false;
	
	return true;
	
}

function check_Registerd($url) {
	
	$result  = getResponse(trim(subdomain2domain($url)));	
	if($result['regrinfo']['registered']=="yes" || isset($result['regrinfo']['domain']['created']) || isset($result['regrinfo']['domain']['nserver']))	
	return '1';
	else
	return '0';

}

function bad_domain($url) {

	$str   = strtolower($url);
	
	$fetch = mysql_fetch_array(mysqlQuery("SELECT words FROM badWords"));
	
	if (trim($fetch['words']) != "") {
	
		if (strpos($fetch['words'],',') !== false) {
		
			$list = explode(",", strtolower($fetch['words']));
			
			foreach ($list as $list_word) {
			
				if (preg_match('/' . trim($list_word) . '/', $str))
					return true;
			}	} else {
			
		$list_word = trim(strtolower($fetch['words']));
		
		if (preg_match('/' . trim($list_word) . '/', $str))
		return true;
		
		}
	}
	
return false;
	
}

function subdomain2domain($url) {

	while(substr_count($url, '.')>1 && !isRegisterd($url)) {

		$array = explode('.', $url, 2);

		$url = $array[1];

	}

	return $url;

}

function isRegisterd($url) {
	
	$result  = json_decode(getResponse($url),true);
	
	return ($result['regrinfo']['registered']=="yes" || isset($result['regrinfo']['domain']['created']) || isset($result['regrinfo']['domain']['nserver'])) ? true:false;

}

function httpify($link) {

	if (preg_match("#https?://#", $link) === 0) $link = 'http://' . $link;
		return $link;

}

function getDomain($url) {

	if(preg_match("#https?://#", $url) === 0)
		$url = 'http://' . $url;
	
	return strtolower(str_ireplace('www.', '', parse_url($url, PHP_URL_HOST)));
	
}

function getResponse($url) {

	if(valid_url($url)) {
	    if(isset($_SESSION['whois_status']) && $_SESSION['whois_status'] == 1)
		{
			$cache = phpFastCache();
			$response = $cache->get($url);
			if($response==null) {
				$response = generateResponse($url);
				$cache->set($url,$response,$_SESSION['whois_time']);
			}
			unset($cache);
		}
		else
		{
			$response = generateResponse($url);
		}
		return $response;
	}
	return "";
}

function generateResponse($url) {
	$whois = new Whois();
	$response = $whois->Lookup($url);
	unset($whois);
	return $response;
}

function file_get_contents_curl($url) {

	if (extension_loaded('curl') === true) {
	
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36");
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		
		$data = curl_exec($ch);
		
		curl_close($ch);
		
	} else {
	
		$data = file_get_contents($url);
	
	}
	
return $data;

}

function webServerInformation($result) {
	
	$arrayNsIps = array();
	
	foreach ($result['regrinfo']['domain']['nserver'] as $ns) {
	
		array_push($arrayNsIps, $ns);
		
	}
	
	$newArray = array_merge(array_keys($result['regrinfo']['domain']['nserver']), $arrayNsIps);
	
	$totalNs  = count($newArray) / 2;
	
	$server_name = '';
	
	if($totalNs > 0)
	{
		
		$server_name .= '<div class="responsive-table"><table class="table table-hover table-condensed"><thead><tr><th colspan="2" class="heading"><h4>'.$_SESSION['NAME SERVERS'].'</h4></th></tr></thead><tbody>';
		
		if ($totalNs > 0) {
		
			$host1= $newArray[0];
			
			$ip1  = $newArray[$totalNs];
			
			$data = json_decode(file_get_contents_curl("http://www.telize.com/geoip/$ip1"), true);
			
			$country1 = $data['country_code'];
			
			$server_name .= '<tr><th>'.$host1.'</th><td>'.$ip1.'</td></tr>';
		}
		
		if ($totalNs > 1) {
		
			$host2= $newArray[1];
			
			$ip2  = $newArray[$totalNs + 1];
			
			$data = json_decode(file_get_contents_curl("http://www.telize.com/geoip/$ip2"), true);
			
			$country2 = $data['country_code'];
			
			$server_name .= '<tr><th width="150px">'.$host2.'</th><td>'.$ip2.'</td></tr>';
		}
		
		if ($totalNs > 2) {
		
			$host3= $newArray[2];
			
			$ip3  = $newArray[$totalNs + 2];
			
			$data = json_decode(file_get_contents_curl("http://www.telize.com/geoip/$ip3"), true);
			
			$country3 = $data['country_code'];
			
			$server_name .= '<tr><th width="150px">'.$host3.'</th><td>'.$ip3.'</td></tr>';
		}
		
		if ($totalNs > 3) {
		
			$host4= $newArray[3];
			
			$ip4  = $newArray[$totalNs + 3];
			
			$data = json_decode(file_get_contents_curl("http://www.telize.com/geoip/$ip4"), true);
			
			$country4 = $data['country_code'];
			
			$server_name .= '<tr><th width="150px">'.$host4.'</th><td>'.$ip4.'</td></tr>';
			
		}
		
		$latLong   = json_decode(file_get_contents_curl("http://www.telize.com/geoip/$ip1"), true);
		
		$latitude  = $latLong['latitude'];
		
		$longitude = $latLong['longitude'];
		
		$server_name .= '</tbody></table></div>';
	
	}
	
	return $server_name;
}

function domainAge($date) {

	$date1   = $date;
	$date2   = date("Y-m-d");
	$diff= abs(strtotime($date2) - strtotime($date1));
	$years   = floor($diff / (365 * 60 * 60 * 24));
	$months  = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$days= floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
	$str_age = "";
	
	if($months>0) {
	
		if ($years > 1)
		$str_age .= $years . " years, ";
		else if($years==1)
		$str_age .= $years . " year, ";
		else if($years<1)
		$str_age .="";
		
	} else {
	
		if ($years > 1)
		$str_age .= $years . " years ";
		else if($years==1)
		$str_age .= $years . " year ";
		else if($years<1)
		$str_age .="";
		
	}
	
	if($days>0) {
	
		if ($months > 1)
			$str_age .= $months . " months & ";
		else if($months==1)
			$str_age .= $months . " month & ";
		else if($months<1)
			$str_age .= "";
		
	} else {
	
	if ($months > 1)
		$str_age .= $months . " months ";
	else if($months==1)
		$str_age .= $months . " month ";
	else if($months<1)
		$str_age .= "";
	
	}
	
	if ($days > 1)
		$str_age .= $days . " days ";
	else if ($days==1)
		$str_age .= $days . " day ";
	else if ($days==1)
		$str_age .= $days . " ";
		
	$str_age .= "ago";
	
return $str_age;
	
}

function xssClean($data) 
{

	return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	
}

function mres($var) 
{

    if (get_magic_quotes_gpc()) 
	{
	
        $var = stripslashes(trim($var));
		
    }

return mysql_real_escape_string(trim($var));

}
if(get_magic_quotes_gpc()) 
{

	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	
	while (list($key, $val) = each($process)) 
	{
	
		foreach ($val as $k => $v) 
		{
		
			unset($process[$key][$k]);
			
			if (is_array($v)) 
			{
			
				$process[$key][stripslashes($k)] = $v;
				$process[] = &$process[$key][stripslashes($k)];
				
			}
			else 
			{
			
				$process[$key][stripslashes($k)] = stripslashes($v);
			
			}
			
		}
		
	}

	unset($process);
	
}
function isValidDate($date)
{

	if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches))
	{
	
	if (checkdate($matches[2], $matches[3], $matches[1])) 
	return true;
	
	}
	
	return false;
}
function add_page($id,$permalink,$description, $keywords, $status, $display_order)
{

	mysqlQuery("INSERT INTO pages(id,permalink,description,keywords,status,display_order) VALUES('$id','$permalink','$description','$keywords','$status','$display_order')");

}

function getDisplayOrder() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT MAX(display_order) AS disp_order FROM pages"));

	$total = $array["disp_order"];

	if($total>0)
	$display_order = $total + 1;
	else
	$display_order = 1;

	return $display_order;
	
}
function analyticsEnabled() {

	$array = mysql_fetch_array(mysqlQuery("SELECT `status` FROM `analytics`"));
	
	if ($array['status'])
		return true;
	
	return false;

}
function update_page($id, $permalink,$description,$keywords,$status)
{
	
	mysqlQuery("UPDATE `pages` SET permalink='$permalink',description='$description',keywords='$keywords',status='$status' WHERE id='$id'");
	mysqlQuery("UPDATE `page_language` SET permalink='$permalink' WHERE id='$id'");
	
}
function update_homepage($id,$description, $keywords)
{
	
	mysqlQuery("UPDATE `pages` SET description='$description',keywords='$keywords' WHERE id='$id'");
	
}
function delete_page($id)
{

    $id = (int)mres($id);

    $sql_delete = mysqlQuery("DELETE FROM pages WHERE id='$id'");

}
function clean_permalink($permalink)
{

	$to_clean = array(
	"#",
	"%",
	"&",
	"$",
	"*",
	"{",
	"}",
	"(",
	")",
	"@",
	"^",
	"|",
	"/",
	";",
	".",
	",",
	"`",
	"!",
	"\\",
	":",
	"<",
	">",
	"?",
	"/",
	"+",
	'"',
	"'"
	);
	
	$permalink = str_replace(" ", "-", $permalink);
	
	foreach($to_clean as $symbol)
	{
	
	$permalink = str_replace($symbol, "", $permalink);
	
	}
	
	while (strpos($permalink, '--') !== FALSE)
	$permalink = str_replace("--", "-", $permalink);
	
	$permalink = rtrim($permalink, "-");
	
	$permalink = ltrim($permalink, "-");
	
	if ($permalink != "-") 
	return $permalink;
	else 
	return "";
	
}

function gen_permalink($title)
{

	$permalink = strtolower(strip_tags($title));
	
	$permalink = trim($title);
	
	$permalink = str_replace(" ", "-", $permalink);
	
	$permalink = clean_permalink($permalink);
	
	$final = $permalink;
	
	$count = 1;
	
	while (already_exists($final))
	{
	
		$final = $permalink . "-" . $count;
		
		$count++;
	
	}
	
	return strtolower($final);
	
}
function already_exists($permalink)
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT * FROM pages WHERE permalink='$permalink'"));
	
	$id=$rows['id'];
	
	if($id) 
	return true;
	else 
	return false;
	
}
function gen_page_permalink($title)
{

	$permalink = string_limit_words($title, 9);
	
	$permalink = preg_replace('/[^a-z0-9]/i', ' ', $permalink);
	
	$permalink = trim(preg_replace("/[[:blank:]]+/", " ", $permalink));
	
	$permalink = strtolower(str_replace(" ", "-", $permalink));
	
	$count = 1;
	
	$temppermalink = $permalink;
	
	while (is_valid_page($permalink))
	{
		
		$permalink = $temppermalink . '-' . $count;
		
		$count++;
		
	}
	
	return $permalink;
	
}
function rootpath()
{
	
	//if(!isset($_SESSION['root_path'])) {

	$_SESSION['root_path'] = regenRootPath();
	
 //}

return $_SESSION['root_path'];
	
}
function cleanUrl($url) 
{

	$url = preg_replace('#^https?://#', '', $url);

	$url = preg_replace('/^www\./', '', $url);

	return $url;
}
function regenRootPath()
{

	$query = mysqlQuery("SELECT `rootPath` FROM `settings`");
	
	$fetch = mysql_fetch_array($query);
	
	if ($fetch['rootPath'] != "")
	{
	
		$www = (urlStructure()) ? 'www.':'';
		$https = (httpsStatus()) ? 'https://':'http://';
		$_SESSION['root_path'] = $https . $www . cleanUrl($fetch['rootPath']);
		
	}

	return $_SESSION['root_path'];

}
function update_settings($name,$title, $description, $keywords, $rootpath, $logo, $favicon,$urlStructure,$https,$f_loader,$p_loader)
{ 

    $title = strip_tags(htmlspecialchars(mres($title)));
	
	$description = strip_tags(htmlspecialchars(mres($description)));
	
	$keywords = strip_tags(htmlspecialchars(mres($keywords)));
	
    $rows = mysql_num_rows(mysqlQuery("SELECT * FROM `settings`"));
	
	if($rows>0) 
	{
		$sql_update = mysqlQuery("UPDATE `settings` SET `name`='$name',`title`='$title',`description`='$description',`keywords`='$keywords',`rootpath`='$rootpath',`logo`='$logo',`favicon`='$favicon',urlStructure='$urlStructure', httpsStatus='$https', f_loader='$f_loader', p_loader='$p_loader'");
	}
	else
	{
		$sql_update = mysqlQuery("INSERT INTO settings(title,description, keywords,rootpath, urlStructure, httpsStatus, logo,favicon,f_loader,p_loader) VALUES('$title','$description','$keywords','$rootpath','$urlStructure','$https','$logo','$favicon','$f_loader','$p_loader')");
	}
	unset($_SESSION['root_path']);
	unset($_SESSION['loader_session']);
	unset($_SESSION['admin_loader_session']);

}
function valid_facebook_url($field)
{

	if (!preg_match('/^[a-z\d.]{5,}$/i', $field))
	{
	
		return false;
	
	}
	
	return true;
	
}
function valid_twitter_username($field)
{

	if (!preg_match('/^[A-Za-z0-9_]+$/', $field))
	{
	
		return false;
	
	}
	
	return true;
	
}
function valid_google_url($field)
{

	if (!preg_match('/^([0-9]{1,21})$/', $field))
	{
	
	    return false;
	
	}
	
	return true;
	
}
function valid_title($field)
{

	if (strlen($field) > 70 || strlen($field) < 10)
	{
	
		return false;

	}
	
	return true;
	
}
function valid_desc($field)
{

	if (strlen($field) > 160 || strlen($field) < 20)
	{
	
		return false;
	
	}
	
	return true;
	
}


function valid_keyword($field)
{

	if (strlen($field) > 160 || strlen($field) < 20)
	{
	
		return false;
	
	}
	
	return true;
	
}
function get_cr_name()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT cr_name FROM currency_settings"));
	
	return $rows['cr_name'];
	
}
function get_price()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT price_dollor FROM currency_settings"));
	
	return $rows['price_dollor'];
	
}
function get_twitter()
{

	$row=mysql_fetch_array(mysqlQuery("SELECT twitter FROM `social_profiles`"));
	
	return $row['twitter'];
	
}
function get_facebook()
{

	$row=mysql_fetch_array(mysqlQuery("SELECT facebook FROM `social_profiles`"));
	
	return $row['facebook'];
	
}
function get_google()
{

	$row=mysql_fetch_array(mysqlQuery("SELECT google_plus FROM `social_profiles`"));
	
	return $row['google_plus'];
	
}
function reset_pass($email)
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT username FROM settings WHERE email = '$email'"));
	
	$username = $rows['username'];
	
	$password = genPassword();
	
	send_email(get_admin_email(), "noreply@" . getDomain(rootPath()), "Password Received", "Your Login Details Updated - " . getMetaTitle() , "Your Login Details Updated<br/>Username: " . $username . "<br/>Your new password is: " . $password . "<br/>Login Here: " . rootPath() . '/admin/login.php');
	
	$sql_update = mysqlQuery("UPDATE settings SET password='" . md5($password) . "' WHERE email='" . $email . "'");
	
}
function get_name()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT name FROM settings"));
	
	return  $rows['name'];
	
}
function get_title()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT title FROM settings"));
	
	return  $rows['title'];
	
}
function get_admin_email()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT email FROM settings"));
	
	return  $rows['email'];
	
}
function sencrypt($text)
{

	return strtr(base64_encode($text) , '+/=', '-_,');

}
function sdecrypt($text)
{

	return base64_decode(strtr($text, '-_,', '+/='));
	
}
function get_admin_username()
{

	$sql_select=mysqlQuery("SELECT username FROM settings");
	
	$rows=mysql_fetch_array($sql_select);
	
	$count=mysql_num_rows($sql_select);
	
	if ($count > 0)
	return  $rows['username'];
	
}
function get_tracking_code()
{

	$fetch = mysql_fetch_Array(mysqlQuery("SELECT status from analytics"));
	
	if ($fetch['status'])
	{
		
		$row = mysql_fetch_array(mysqlQuery("select tracking_code from analytics"));
		
		$code = str_replace("<q>", "'", $row["tracking_code"]);
		
		$code = htmlspecialchars_decode($code);
		
		return ($code);
	}
	
	return "";
	
}
function get_description()
{

	$array = mysql_fetch_array(mysqlQuery('SELECT `description` FROM settings'));

	if (trim($array['description']))
		return trim($array['description']);

	return '';

}
function get_tags()
{
	
	$array = mysql_fetch_array(mysqlQuery('SELECT `keywords` FROM settings'));

	if (trim($array['keywords']) != "")
		return trim($array['keywords']);
		
	return '';
	
}
function send_email($to, $from, $name, $subject, $body)
{

	$admin = getUser();
	
	$mail = new SimpleMail();
   
    $mail->setTo($to, 'Admin');
	
    $mail->setSubject($subject);
	
    $mail ->setFrom('no-reply@fullwebstats.com',$name);
	
	$mail->addMailHeader('Reply-To', $from, $name);
	
    $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());
	
    $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
	
    $mail->setMessage("<html><body><p face='Georgia, Times' color='red'><p>Hello! <b>" . ucwords($admin) . "</b>,</p> <p>" . $body . "</p><br /><br /><p>Sent Via <a href='" . rootPath() . "'>" . getMetaTitle() . "</a></p>");
	
    $mail->setWrap(100);
	  
	$send = $mail->send();
	
}
function genPassword()
{

	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	
	srand((double)microtime() * 1000000);
	
	$i = 0;
	
	$pass = '';
	
	while ($i <= 8)
	{
	
		$num = rand() % 33;
		
		$tmp = substr($chars, $num, 1);
		
		$pass = $pass . $tmp;
		
		$i++;
		
	}
	
	return $pass;
	
}
function is_alpha($val)
{

	return (bool)preg_match("/^([0-9a-zA-Z ])+$/i", $val);

}
function checkEmail($email)
{

	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;

}
function is_valid_page($permalink)
{

	$match = "select title from pages where permalink='" . $permalink . "'";
	
	$qry = mysqlQuery($match);
	
	$num_rows = mysql_num_rows($qry);
	
	if ($num_rows > 0) 
	return true;
	else
	return false;
	
}
function email_exists($val)
{

	$sql_select=mysqlQuery("SELECT username FROM settings WHERE email ='$val'");
	
	$rows=mysql_fetch_array($sql_select);
	
	$count=mysql_num_rows($sql_select);
	
	if($count > 0)			
	return true;
	else 
	return false;
	
}
function get_logo()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT logo FROM settings"));
	return  $rows['logo'];
	
}
function get_favicon()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT favicon FROM settings"));
	
	return  $rows['favicon'];
	
}
function valid_file_extension($ext)
{

	$allowedExts = array(
	"gif",
	"jpeg",
	"jpg",
	"png"
	);
	
	if (!in_array($ext, $allowedExts))
	{
	
	return false;
	
	}
	
	return true;
	
}
function valid_favicon_extension($ext)
{

	$allowedExts = array(
	"ico",
	"png"
	);
	
	if (!in_array($ext, $allowedExts))
	{
	
		return false;
	
	}
	
		return true;
}
function valid_language_extension($ext)
{

	$allowedExts = array(
	"txt",
	"docx",
	"php",
	"html",
	"pdf"
	);
	
	if (!in_array($ext, $allowedExts))
	{
	
		return false;
	
	}
	
		return true;
}
function show_med_rec1_ad()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT medrec1 FROM ads"));
	
	$code = str_replace("<q>", "'", $rows['medrec1']);
	
	$code = htmlspecialchars_decode($code);
	
	return ($code);
	
}
function show_med_rec2_ad()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT medrec2 FROM ads"));
	
	$code = str_replace("<q>", "'", $rows['medrec2']);
	
	$code = htmlspecialchars_decode($code);
	
	return ($code);	
	
}
function show_med_rec3_ad()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT medrec3 FROM ads"));
	
	$code = str_replace("<q>", "'", $rows['medrec3']);
	
	$code = htmlspecialchars_decode($code);
	
	return ($code);	
	
}
function show_analytics_status()
{

	$rows=mysql_fetch_array(mysqlQuery("SELECT tracking_code FROM analytics"));
	
	$code = str_replace("<q>", "'", $rows['tracking_code']);
	
	$code = htmlspecialchars_decode($code);
	
	return ($code);	
	
}
function increment_page_views()
{

	$sql = mysqlQuery("SELECT pageviews FROM `stats` WHERE `date` = CURDATE()");
	
	$rows = mysql_num_rows($sql);
	
	if ($rows > 0)
	{
	
		$sql_update = mysqlQuery("UPDATE `stats` SET `pageviews`=`pageviews`+1 WHERE `date`=CURDATE()");

	}
	else
	{
	
		$sql_insert = mysqlQuery("INSERT INTO `stats`(`pageviews`, `unique_hits`, `date`) VALUES ('1','0',CURDATE())");
	
	}
	
}
function increment_unique_hits()
{

	$sql = mysqlQuery("SELECT unique_hits FROM `stats` WHERE `date`=CURDATE()");
	
	$rows = mysql_num_rows($sql);
	
	if ($rows > 0)
	{
	
		$sql_update = mysqlQuery("UPDATE `stats` SET `unique_hits`=`unique_hits`+1 WHERE `date`=CURDATE()");
	
	}
	else
	{
	
		$sql_insert = mysqlQuery("INSERT INTO `stats`(`pageviews`, `unique_hits`, `date`) VALUES ('0','1',CURDATE())");
	
	}
	
}
function add_total_searches()
{

    $date=date("Y-m-d");
	
	$rows = mysql_num_rows(mysqlQuery("SELECT total_searches FROM `stats` WHERE `date`='$date'"));

	if ($rows > 0)
	{

		 $sql_update = mysqlQuery("UPDATE `stats` SET `total_searches`=`total_searches`+1 WHERE `date`='$date'");

	}
	else
	{

		 $sql_insert = mysqlQuery("INSERT INTO `stats`(`total_searches`, `unique_hits`, `date`) VALUES ('1','0','$date')");

	}
	
}
function db_decode($str)
{

	$str = trim(str_replace("<q>", "'", $str));
	
	$str = htmlspecialchars_decode($str);
	
	return $str;
	
}
function authenticate($email, $password)
{

	$email = mres($email);
	
	$password = md5($password);
	
	$sql      = "select email from settings WHERE (email='$email' AND password='$password') OR (username='$email' AND password='$password')";
	
	$query    = mysqlQuery($sql);
	
	if (mysql_num_rows($query) > 0)
	{
	
		return true;
		
	}
	else
	{
	
		return false;
		
	}
	
}
function show_medrec1()
{

	$row=mysql_fetch_array(mysqlQuery("SELECT medrec1,medrec1_status FROM ads"));
	
	if($row['medrec1_status']==1)
	return $row['medrec1'];
	
}
function show_medrec2()
{

	$row=mysql_fetch_array(mysqlQuery("SELECT medrec2,medrec2_status FROM ads"));
	
	if($row['medrec2_status']==1)
	return $row['medrec2'];
	
}
function show_medrec3()
{

	$row=mysql_fetch_array(mysqlQuery("SELECT medrec3,medrec3_status FROM ads"));
	
	if($row['medrec3_status']==1)
	return $row['medrec3'];
	
}
function captcha_admin_login_status() 
{

	$array  = mysql_fetch_array(mysqlQuery("SELECT captcha_admin_login_status FROM captcha_settings"));
	
	if($array['captcha_admin_login_status'])
	return true;
	return false;
	
}
function captcha_contact_status() 
{

	$array  = mysql_fetch_array(mysqlQuery("SELECT captcha_contact_status FROM captcha_settings"));
	
	if($array['captcha_contact_status'])
	return true;
	return false;
	
}
function getMetaTitle() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `title` FROM `settings`"));
	
	if ($array['title'] != "")
	return $array['title'];		
    return "Instant Domain Search";

}
function getUser() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `username` FROM `settings`"));
	
	if($array['username'])
	return $array['username'];
	
}
function send_contact_email($to, $from, $name, $subject, $body) 
{

	$admin = getUser();
	
	$mail = new SimpleMail();
   
    $mail->setTo($to, 'Admin');
	
    $mail->setSubject($subject);
	
    $mail ->setFrom('no-reply@fullwebstats.com',$name);
	
	$mail->addMailHeader('Reply-To', $from, $name);
	
    $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());
	
    $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
	
    $mail->setMessage("<html><body><p face='Georgia, Times' color='red'><p>Hello! <b>" . ucwords($admin) . "</b>,</p> <p>" . $body . "</p><br /><br /><p>Sent Via <a href='" . rootPath() . "'>" . getMetaTitle() . "</a></p>");
	
    $mail->setWrap(100);
	  
	$send = $mail->send();
	
}
function captcha_enable_settings($contact_enable,$login_enable) 
{

    $sql_update = mysqlQuery("UPDATE `captcha_settings` SET `captcha_contact_status`='$contact_enable',`captcha_admin_login_status`='$login_enable'");

}
function captcha_contact_enable() 
{

	$show  = "select captcha_contact_status from captcha_settings";
	
	$qry   = mysqlQuery($show);
	
	$array = mysql_fetch_array($qry);
	
	return $array['captcha_contact_status'];
	
}
function captcha_login_enable() 
{

	$show  = "select captcha_admin_login_status from captcha_settings";
	
	$qry   = mysqlQuery($show);
	
	$array = mysql_fetch_array($qry);
	
	return $array['captcha_admin_login_status'];
	
}
function return_pageviews_this_month()
{

	$sql="SELECT SUM(total_searches) AS total_searches from stats WHERE YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()) ";
	
	$query=@mysqlQuery($sql);
	
	$fetch_array = @mysql_fetch_array($query);
	
	if ($fetch_array['total_searches'])
	return $fetch_array['total_searches'];
	return "0";
	
}
function return_total_searches($date)
{

	$sql = "select sum(total_searches) as total_searches from stats where date='$date'";
	
	$query = @mysqlQuery($sql);
	
	$fetch_array = @mysql_fetch_array($query);
	
	if ($fetch_array['total_searches']) return $fetch_array['total_searches'];
	return "0";
	
}
function return_pageviews_all_time()
{

	$sql="SELECT SUM(total_searches) AS total_searches from stats";
	
	$query=@mysqlQuery($sql);
	
	$fetch_array = @mysql_fetch_array($query); 
	
	if ($fetch_array['total_searches'])
	return $fetch_array['total_searches'];	
	return "0";
	
}
function update_social($facebook,$twitter,$google,$f_status,$t_status,$g_status,$all_status)
{

	$sql_update = mysqlQuery("UPDATE `social_profiles` SET `facebook`='$facebook',`twitter`='$twitter',`google_plus`='$google',`f_status`='$f_status',`t_status`='$t_status',`g_status`='$g_status',`social_buttons`='$all_status'");

}
function update_ads($medrec1,$medrec1_status,$medrec2,$medrec2_status,$medrec3,$medrec3_status)
{
	
	$sql_update = mysqlQuery("UPDATE `ads` SET `medrec1`='$medrec1',`medrec1_status`='$medrec1_status',`medrec2`='$medrec2',`medrec2_status`='$medrec2_status',`medrec3`='$medrec3',`medrec3_status`='$medrec3_status'");
	
}
function show_analytics_status1() 
{ 
	
	$rows=mysql_fetch_array(mysqlQuery("SELECT tracking_code FROM analytics"));
	
	$code = str_replace("<q>", "'", $rows['tracking_code']);
	
	$code = htmlspecialchars_decode($code);
	
	return ($code);
	
}
function update_analytics($tracking_code, $status)
{

	$sql_update = mysqlQuery("UPDATE `analytics` SET `tracking_code`='$tracking_code',`status`='$status'");
	
}
function update_license($username, $code)
{

	$rows = mysql_num_rows(mysqlQuery("SELECT * FROM `license`"));
	
	if($rows>0) {

	mysqlQuery("UPDATE `license`  SET `username`='$username',`purchase_code`='$code'");
	
	} else {
	
	mysqlQuery("INSERT INTO `license`(username,purchase_code) VALUES('$username','$code')");;
	
	}
	
}
function clean($string)
{

	$string = str_replace(' ', '', $string); 
	
	$string = preg_replace('/[^A-Za-z.0-9\-]/', '', $string);
	
	return $string;
	
}
function generatePagesSitemap()
{

	$sitemap = "";

	$query = mysqlQuery("SELECT `permalink` FROM `pages` WHERE status = 1 ORDER BY `id` DESC");

	while ($array = mysql_fetch_array($query)) 
	{

		$sitemap .='<url>' . PHP_EOL;

		$sitemap .="<loc>" . rootpath() . "/page/" . $array['permalink'] . "</loc>" . PHP_EOL;

		$sitemap .="<priority>0.6</priority>" . PHP_EOL;

		$sitemap .='</url>' . PHP_EOL;

	}

	return $sitemap;

}
function generatewebsiteSitemap()
{

	$sitemap = "";

	$query = mysqlQuery("SELECT * from instant_domain ORDER BY last_date_check DESC");

	while ($array = mysql_fetch_array($query)) 
	{

		$sitemap .='<url>' . PHP_EOL;

		$sitemap .="<loc>" . rootpath() . "/" . $array['domain'] . ".html</loc>" . PHP_EOL;

		$sitemap .="<priority>0.5</priority>" . PHP_EOL;

		$sitemap .='</url>' . PHP_EOL;

	}

	return $sitemap;

}
function generateRootSitemap() 
{

	$sitemap = "";

	$sitemap .='<url>' . PHP_EOL;

	$sitemap .="<loc>" . rootPath() . "/</loc>" . PHP_EOL;

	$sitemap .="<priority>1.0</priority>" . PHP_EOL;

	$sitemap .='</url>' . PHP_EOL;

	return $sitemap;
	
}
function generateContactSitemap() 
{

	$sitemap = "";

	$sitemap .='<url>' . PHP_EOL;

	$sitemap .="<loc>" . rootPath() . "/contact</loc>" . PHP_EOL;

	$sitemap .="<priority>0.7</priority>" . PHP_EOL;

	$sitemap .='</url>' . PHP_EOL;

	return $sitemap;
	
}
function sitemapPagesStatus() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `pagesStatus` FROM `sitemaps`"));

	return $array["pagesStatus"];

}

function sitemapContactStatus() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `contactStatus` FROM `sitemaps`"));

	return $array["contactStatus"];

}

function sitemapDateUpdated() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `dateUpdated` FROM `sitemaps`"));

	return date('d M Y', strtotime($array['dateUpdated']));

}
function sitemapFileName() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `filename` FROM `sitemaps`"));
	
	return $array["filename"];

}
function updateSitemapsStatus($pagesStatus, $contactStatus,$filename)
{

	$rows = mysql_num_rows(mysqlQuery("SELECT * FROM `sitemaps`"));

	if($rows>0) 
	{

		mysqlQuery("UPDATE `sitemaps` SET `pagesStatus`='$pagesStatus',`contactStatus`='$contactStatus',`filename`='$filename'");

	}
	else 
	{

		mysqlQuery("INSERT INTO `sitemaps`(pagesStatus,contactStatus,filename) VALUES('$pagesStatus','$contactStatus','$filename')");

	}

}
function urlStructure() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `urlStructure` FROM `settings`"));

	return $array['urlStructure'];

}
function httpsStatus() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `httpsStatus` FROM `settings`"));

	return $array['httpsStatus'];

}
function f_loader() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `f_loader` FROM `settings`"));

	return $array['f_loader'];

}
function p_loader() 
{

	$array = mysql_fetch_array(mysqlQuery("SELECT `p_loader` FROM `settings`"));

	return $array['p_loader'];

}
function is_alphaNumeric($string) 
{

	if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $string))
	return true;
	
	return false;

}
function updateUser($username, $password, $email) 
{
	
	$rows = mysql_num_rows(mysqlQuery("SELECT * FROM `settings`"));
	
	if($rows>0) 
	{
	
		if ($password != "") 
		{
		
			mysqlQuery("UPDATE `settings` SET `username`='$username',password='$password',email='$email'");
		
		}
		
		else 
		{
		
			mysqlQuery("UPDATE settings SET username='$username',email='$email'");
		
		} 
	} 
	else 
	{
	
		if ($password != "") 
		{
		
			mysqlQuery("INSERT INTO `settings`(username,password,email) VALUES('$username','$password','$email')");
		
		}
		
		else 
		{
		
			mysqlQuery("INSERT INTO `settings`(username,email) VALUES('$username','$email')");
		
		}
	
	}
    
    return true;
	
}
function getAddress()
{

	$pageURL = 'http';
	
	if ($_SERVER["HTTPS"] == "on") {
	
		$pageURL .= "s";
	
	}
	$pageURL .= "://";
	
	if ($_SERVER["SERVER_PORT"] != "80") {
	
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		
	} else {
	
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	
	}
	
	return htmlentities($pageURL);

}
function convert_currency($val)
{  
	
	if(!isset($_SESSION['currency']))
	{
	
		$rows=mysql_fetch_array(mysqlQuery("SELECT * FROM currency_settings"));
		
		$_SESSION['currency']=$rows['cr_name'];	
		
		$_SESSION['show_place']=$rows['show_place'];
		
	}
	if($val == 0)
	{
		return $_SESSION['Buy'];
	}
	else
	{
	    if($_SESSION['currency'] && $_SESSION['show_place']==1)
		return ($_SESSION['Buy'].' '.$_SESSION['currency'] . ' ' . $val);
		else
		return ($_SESSION['Buy'].' '.$val. ' ' . $_SESSION['currency']);
	}
}
function edit_page_language($id,$content,$language,$title)
{

	mysqlQuery("UPDATE `page_language` SET content='$content',title='$title' WHERE `id`='$id' AND language = '$language'");

}
function edit_widget_language($id,$content,$language,$title)
{

	mysqlQuery("UPDATE `widget_language` SET content='$content',title='$title' WHERE `id`='$id' AND language = '$language'");

}
function add_page_language($id, $permalink, $title,$content,$language)
{

	mysqlQuery("INSERT INTO `page_language`(id,title,permalink,content,language) VALUES('$id','$title','$permalink','$content','$language')");

}
function add_widget_language($id,$title,$content,$language)
{

	mysqlQuery("INSERT INTO `widget_language`(id,title,content,language) VALUES('$id','$title','$content','$language')");

}
function layoutRTL(){

	if($_SESSION['reset_RTL_session'] == 1)
		return true;
	else 
		return false;
		
}
function httpStatusCode($url) {
	
	$handle = curl_init($url);
	
	$USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	
	curl_setopt($handle,  CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt($handle, CURLOPT_USERAGENT, $USER_AGENT);
	
	curl_setopt($handle, CURLOPT_TIMEOUT, 5);
			
	curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
		
	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
		
	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
	
	curl_setopt($handle,CURLOPT_HEADER,true);
    
	curl_setopt($handle,CURLOPT_NOBODY,true);
	
	curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);
	
	$response = curl_exec($handle);
	
	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
	
	curl_close($handle);
	
	return $httpCode;
	
	}
	
function getClientIP() {

    if (isset($_SERVER)) {

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];

        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];

        return $_SERVER["REMOTE_ADDR"];
    }

    if (getenv('HTTP_X_FORWARDED_FOR'))
        return getenv('HTTP_X_FORWARDED_FOR');

    if (getenv('HTTP_CLIENT_IP'))
        return getenv('HTTP_CLIENT_IP');

    return getenv('REMOTE_ADDR');
	
}

if(!urlStructure() && substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {

	if($_SERVER["HTTPS"] != "on" && httpsStatus())
		$https = 'https://';
	else
		$https = 'http://';

	if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {

		header("HTTP/1.1 301 Moved Permanently");

		header('Location: ' . $https . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);

		exit();

	}

} else if(urlStructure() && (strpos($_SERVER['HTTP_HOST'], 'www.') === false)) {

	if($_SERVER["HTTPS"] != "on" && httpsStatus())
		$https = 'https://';
	else
		$https = 'http://';

	if ((strpos($_SERVER['HTTP_HOST'], 'www.') === false)) {
	
		header("HTTP/1.1 301 Moved Permanently");
		
		header('Location: ' . $https . 'www.'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		
		exit();
	
	}

}
if($_SERVER["HTTPS"] != "on" && httpsStatus())
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
} else if($_SERVER["HTTPS"] == "on" && !httpsStatus()) {
	header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
?>