<?php 
if(!isset($_SESSION)) session_start();

include("config/config.php");

include("includes/functions.php");

include "language/lang_array.php";
 
include 'includes/session.php';

include "libs/whoischeck.php";

$array = array();

if(isset($_POST['url']))
{

	$url = rtrim(trim($_POST['url']),".");
	
	$url = getdomain($url);
	
	$obj = new Whoischeck($url);

	if ($obj->isAvailable()) 
		$existense = 1;
	else
		$existense = 0;
	
	if(!isset($_SESSION['domain_search_session']))
	{
		$_SESSION['domain_search_session'] = array();
	}
	if($existense == 0)
	{
		if(!in_array($url, $_SESSION['domain_search_session']))
			add_total_searches();	 	
		array_push($_SESSION['domain_search_session'],$url);
	}
	
	if(validURL($url)) {
	
		$date = date('Y-m-d H:i:s');
		
		if($existense == 0)
		{	
			$count_record = mysql_num_rows(mysqlQuery("SELECT * FROM instant_domain WHERE domain='$url'"));
			if($count_record > 0)
				mysqlQuery("UPDATE instant_domain SET last_date_check='$date' WHERE domain='$url'");
			else
				mysqlQuery("INSERT INTO instant_domain(domain,last_date_check) VALUES('$url','$date')");
		}
		
		if($existense == 0)
		{
			if(isset($_POST['type']) && $_POST['type'] == 'rawdata')
			{
				
				$data = getResponse(trim(subdomain2domain($url)));
				
				$list_server = webServerInformation($data);
				
				$domain_name = $data['regrinfo']['domain']['name'];

				$registrar   = $data['regyinfo']['registrar'];

				$referrer   = $data['regyinfo']['referrer'];

				$creation_date   = $data['regrinfo']['domain']['created'];

				$updation_date   = $data['regrinfo']['domain']['changed'];

				$expiration_date = $data['regrinfo']['domain']['expires']; 
						
				$count = count($data['regyinfo']['servers']);

				$s = 0;
				
				$server = '';

				while($s < $count)
				{
				
					$server .= $data['regyinfo']['servers'][$s]['server'];
					
					$server .= "<br>";
					
					$s++;
					
				}
				
				if(isset($registrar) && $registrar != "" || isset($server) && $server != "" || isset($referrer) && $referrer != "")
				{
					$registrar_info = '<div class="responsive-table">
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<th colspan="2" class="heading"><h4>'.$_SESSION['REGISTRAR INFO'].'</h4></th>
								</tr>
							</thead>
							<tbody>';
					if(isset($registrar) && $registrar != "")
					{
					$registrar_info .='<tr>
										<th width="150px">'.$_SESSION['Name'].'</th>
										<td>'.$registrar.'</td>
									 </tr>';
					}
					if(isset($server) && $server != "")
					{
					$registrar_info .='<tr>
									<th>'.$_SESSION['Whois Server'].'</th>
									<td>'.$server.'</td>
								</tr>';
					}
					if(isset($referrer) && $referrer != "")
					{
					$registrar_info .='<tr>
								<th>'.$_SESSION['Referral URL'].'</th>
								<td>'.$referrer.'</td>
							</tr>';
					}
					$registrar_info .='</tbody>
						</table>
					</div>';
				}
				
				$count_status=  count($data['regrinfo']['domain']['status']);
				
				$raw_registrar_data = '';
				
				$i = 0;
				
				$count_registrar_data=  count($data['rawdata']);

				while($i <= $count_registrar_data)
				{ 
					if(isset($data['rawdata'][$i]) && $data['rawdata'][$i] != "")
					{
						$containsLetter  = preg_match('/[a-zA-Z]/',   $data['rawdata'][$i]);
						
						$containsDigit   = preg_match('/\d/',          $data['rawdata'][$i]);
						
						if($containsLetter || $containsDigit)
							$raw_registrar_data .= '<tr><th width="150px">'.$data['rawdata'][$i].'</th></tr>';
					}
					$i++;
				}
				
				if($raw_registrar_data == '')
				{
					$home = '"home"';
					$Home = '"Home"';
					$contact = 'contact';
					$raw_registrar_data = "<div class='text-center' id='page_404'>
								<h3>".$_SESSION['Tld Not Supported']."</h3>
								<div>
									<button class='btn btn-md btn-primary' type='button' onclick='change_pages($home,$Home);'>
										<li class='fa fa-home'></li> ".$_SESSION['take-me-home']."
										</button>
									<button class='btn btn-md btn-info' type='button' onclick='contact_page(".$contact.");'>
										<li class='fa fa-envelope'></li> ".$_SESSION['Contact Us']."
									</button>
								</div>
								</br></br>
							</div>";
				}
				if(isset($updation_date) && $updation_date != "" || isset($creation_date) && $creation_date != "" || isset($expiration_date) && $expiration_date != "")
				{				
					$important_dates = '<div class="responsive-table">
						<table class="table table-hover table-condensed">
							<thead>
								<tr>
									<th colspan="2" class="heading"><h4>'.$_SESSION['IMPORTANT DATES'].'</h4></th>
								</tr>
							</thead>
							<tbody>';
						if(isset($updation_date) && $updation_date != "")
						{
						$important_dates .= '<tr>
								<th>'.$_SESSION['Updated'].':</th>
								<td>
									<span>'.$updation_date.'</span>
									<span class="c-date">('.domainAge($updation_date).')</span>
								</td>
							</tr>';
						}
						if(isset($creation_date) && $creation_date != "")
						{
						$important_dates .= '<tr>
								<th>'.$_SESSION['Created'].':</th>
								<td>
									<span>'.$creation_date.'</span>
									<span class="c-date">('.domainAge($creation_date).')</span>
								</td>
							</tr>
							<tr>';
						}
						if(isset($expiration_date) && $expiration_date != "")
						{
						$important_dates .=	'<th>'.$_SESSION['Expires'].':</th>
								<td>
									<span>'.$expiration_date.'</span>
									<span class="c-date">('.domainAge($expiration_date).')</span>
								</td>
							</tr>';
						}
						$important_dates .= '</tbody>
							</table>
						</div>';
				}
				$array[0] = '
					<div class="panel">
						<div class="panel-heading">
							<div class="panel-title">
								 <h4><i class="fa fa-globe"></i> '.$url.'</h4>
							</div>
						</div>
					</div>
					<div class="domain-img thumbs-shadow">
						<img src="http://free.pagepeeker.com/v2/thumbs.php?size=x&url='.$url.'" alt="Facebook" class="img-responsive thumbnail center-block" />
					</div>
					<div class="row social-btns transition">
						<div class="col-xs-12">
							<a target="_blank" class="social-btn fb-btn" href="https://www.facebook.com/sharer/sharer.php?u='.(rootpath()).'/'.$url.'.html">
								<i class="fa fa-facebook-square"></i>
							</a>
							<a target="_blank" class="social-btn twitter-btn" href="http://twitter.com/home?status='.$url.' WHOIS search to reveal information about the registry, owner and expiry details of a '.$url.' domain name - '.(rootpath()).'/'.$url.'.html">
								<i class="fa fa-twitter"></i>
							</a>
							<a target="_blank" class="social-btn google-btn" href="https://plus.google.com/share?url='.(rootpath()).'/'.$url.'.html">
								<i class="fa fa-google-plus"></i>
							</a>
							<a target="_blank" class="social-btn pin-btn" href="http://pinterest.com/pin/create/button/?url='.(rootpath()).'/'.$url.'.html&media=http://free.pagepeeker.com/v2/thumbs.php?size=x&url='.$url.'&description='.$url.' WHOIS search to reveal information about the registry, owner and expiry details of a '.$url.' domain name.">
								<i class="fa fa-pinterest"></i>
							</a>
							<a target="_blank" class="social-btn li-btn" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.(rootpath()).'/'.$url.'.html&amp;title=Whois Lookup and Stats&description='.$url.' WHOIS search to reveal information about the registry, owner and expiry details of a '.$url.' domain name.">
								<i class="fa fa-linkedin-square"></i>
							</a>
							<a target="_blank" class="social-btn red-btn" href="https://www.reddit.com/login?dest=http://www.reddit.com/submit?url='.(rootpath()).'/'.$url.'.html/&title=Whois Lookup and Stats">
								<i class="fa fa-reddit"></i>
							</a>
							<a target="_blank" class="social-btn vk-btn" href="https://vk.com/share.php?url='.(rootpath()).'/'.$url.'.html&title='.$url.' Whois Lookup and Stats&description='.$url.' WHOIS search to reveal information about the registry, owner and expiry details of a '.$url.' domain name.&image=https://www.reddit.com/login?dest=http://www.reddit.com/submit?url='.(rootpath()).'/'.$url.'.html&noparse=true">
								<i class="fa fa-vk"></i>
							</a>
							<a target="_blank" class="social-btn digg-btn" href="http://digg.com/submit?phase=2&amp;url='.(rootpath()).'/'.$url.'.html">
								<i class="fa fa-digg"></i>
							</a>
							<a target="_blank" class="social-btn tumblr-btn" href="http://www.addthis.com/bookmark.php?v=300&winname=addthis&pub=ra-53adb54f33ed92b4&source=max-300&lng=en-US&s=tumblr&url='.(rootpath()).'/'.$url.'.html&title=Whois Lookup and Stats&ate=AT-ra-53adb54f33ed92b4/-/-/542d6935f299da25/1/5416adb6b9757748&frommenu=1&uid=5416adb6b9757748&ct=1&pre='.(rootpath()).'/'.$url.'.html&tt=0&captcha_provider=nucaptcha">
								<i class="fa fa-tumblr"></i>
							</a>
						</div>
					</div>'.$registrar_info.''.$list_server.''.$important_dates;
					$array[1] = $raw_registrar_data;
			}
			else if(isset($_POST['type']) && $_POST['type'] == 'Site-Stats')
			{
				$stats  = new NEXStats($url);
				 
				$data = getResponse(trim(subdomain2domain($url)));
				
				if(isset($_SESSION['site_stats_status']) && $_SESSION['site_stats_status'] == 1)
				{
					$cache = phpFastCache();
					
					$googleRank = $cache->get($url.'_google_rank');			
					if($googleRank==null) {
						$googleRank = $stats->getGooglePagerank();
						$cache->set($url.'_google_rank',$googleRank,$_SESSION['site_stats_time']);
					}
				}
				else
				{
					$googleRank = $stats->getGooglePagerank();
				}
				
				$creation_date   = $data['regrinfo']['domain']['created'];
				
				if(isset($googleRank) && $googleRank != "" || isset($creation_date) && $creation_date != "")
				{
				$site_stats = '<table class="table table-hover table-condensed">
									<thead>
										<tr>
											<th colspan="2" class="heading"><h4>'.$_SESSION['SITE STATS'].'</h4></th>
										</tr>
									</thead>
									<tbody>';
									if(isset($googleRank) && $googleRank != "")
									{
										$site_stats .= '<tr>
											<th>'.$_SESSION['Google Page Rank'].':</th>
											<td>'.$googleRank.'</td>
										</tr>';
									}
									if(isset($creation_date) && $creation_date != "")
									{
										$site_stats .= '<tr>
											<th>'.$_SESSION['Domain Age'].':</th>
											<td>'.domainAge($creation_date).'</td>
										</tr>';
									}
									$site_stats .= '</tbody>
							</table>';
				}
				$array[0] = $site_stats;
			}
			else if(isset($_POST['type']) && $_POST['type'] == 'SEO-Stats')
			{			
				$stats  = new NEXStats($url);
				
				if(isset($_SESSION['seo_stats_status']) && $_SESSION['seo_stats_status'] == 1)
				{
				 
					$cache = phpFastCache();
					
					$alexaRank = $cache->get($url.'_alexa_rank');			
					if($alexaRank==null) {
						$alexaRank  = $stats->getAlexaRank();
						$cache->set($url.'_alexa_rank',$alexaRank,604800);
					}
					
					$backlinks = $stats->getAlexaBacklinks();

					$domainAuthority = $cache->get($url.'_domainAuthority');			
					if($domainAuthority==null) {
						$domainAuthority = $stats->domainAuthority($_SESSION['mozAccessID'],$_SESSION['mozSecretKey']);
						$cache->set($url.'_domainAuthority',$domainAuthority,604800);
					} 
					
				}
				else
				{
					$alexaRank  = $stats->getAlexaRank();
					$backlinks = $stats->getAlexaBacklinks();
					$domainAuthority = $stats->domainAuthority($_SESSION['mozAccessID'],$_SESSION['mozSecretKey']);
				}
				if(isset($alexaRank) && $alexaRank != "" || isset($backlinks) && $backlinks != "" || isset($domainAuthority) && $domainAuthority != "")
				{
				$seo_stats = '<table class="table table-hover table-condensed">
								<thead>
									<tr>
										<th colspan="2" class="heading"><h4>'.$_SESSION['SEO STATS'].'</h4></th>
									</tr>
								</thead>
								<tbody>';
								if(isset($alexaRank) && $alexaRank != "")
								{
									$seo_stats .= '<tr>
										<th width="150px">'.$_SESSION['Alexa Rank'].':</th>
										<td>'.$alexaRank.'</td>
									</tr>';
								}
								if(isset($backlinks) && $backlinks != "")
								{
									$seo_stats .='<tr>
										<th>'.$_SESSION['Total Backinks'].':</th>
										<td>'.$backlinks.'</td>
									</tr>';
								}
								if(isset($domainAuthority) && $domainAuthority != "")
								{
									$seo_stats .='<tr>
										<th>'.$_SESSION['Domain Authority'].':</th>
										<td>'.round($domainAuthority).'</td>
									</tr>';
								}
								$seo_stats .='</tbody>
							</table>';
				}			
				$array[0] = $seo_stats;
			
			}
		}
		else
		{	
			$_SESSION['active_page'] = 'home';
			
			if(isset($_SESSION['reset_lang_name']))
			{
				$language = $_SESSION['reset_lang_name'];
				$mysql = mysqlQuery("SELECT id,font FROM widgets WHERE status = 1 ORDER BY display_order");
			}
			$html =
			'<div class="whois-f-d text-center">
				<div class="container">
					<div class="row">';
					while($fetch = mysql_fetch_array($mysql))
					{
						$get_id = $fetch['id'];
						$font = $fetch['font'];
						$rows = mysql_fetch_array(mysqlQuery("SELECT id,title,content FROM widget_language WHERE language = '$language' AND id='$get_id'"));
						if(!isset($rows['id']))
						{
							$default = mysql_fetch_array(mysql_query("SELECT lang_name FROM `language` WHERE status = 1 AND lang_name != '$language' ORDER BY display_order"));
							$default_language = $default['lang_name'];				
							$rows = mysql_fetch_array(mysql_query("SELECT title,content FROM `widget_language` WHERE language = '$default_language' AND id='$get_id'"));
						}
						if(layoutRTL()) {
							$html .= '<div class="col-xs-6 col-md-3 pull-right">';
						} else {
							$html .= '<div class="col-xs-6 col-md-3">';
						}
						
						$html .= '<div class="inner-mrg z-depth-1">
								<i class="glyphicon '.$font.' glyphicon-fontsize"></i>
								<h1>'.$rows['title'].'</h1>
								'.db_decode($rows['content']).'
							</div>
						</div>';
					$i++;
					}
					$html .= '</div>
				</div>
			</div>';
			
			$ads = mysql_fetch_array(mysql_query("SELECT medrec1 FROM `ads` WHERE medrec1_status = 1")); 
			
			if(isset($ads['medrec1']) && $ads['medrec1'] != "")
			{
				$first_ads_Leaderboard = "<div class='mrg-b-10 hidden-xs hidden-sm'>";
				$first_ads_Leaderboard .= "<div class='ad-tray-728'>";
				$first_ads_Leaderboard .= $ads['medrec1'];
				$first_ads_Leaderboard .="</div>";
				$first_ads_Leaderboard .="</div>";
			}
			
			$array[] =0;
			$array[1] =$first_ads_Leaderboard.$html;
		}

	}
	else
	{
		if(!isset($url) && $url == "")
		{
			$array[0] = 0 ;
			$array[1] = $_SESSION['Enter Your Name'];
		}
		else
		{
			$array[0] = 0 ;
			$array[1] = $_SESSION['Invalid Domains'] ;
		}
	}		
	
	echo json_encode($array);

	exit();

}
?>