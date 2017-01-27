<?php
error_reporting(E_ERROR);
class NEXStats {

	public $url,$timeout;
	
	function __construct($url,$timeout=3) {
	
		$this->url=rawurlencode($url);
		
		$this->timeout=$timeout;
		
	}
	
	public function getClientIP() {

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
	
	public function getGoogleCount() {
	
		$clientIp = $this->getClientIP();
		
		$json = json_decode($this->getRemoteContents("http://ajax.googleapis.com/ajax/services/search/web?v=1.0&filter=0&q=site:$this->url&userip=$clientIp"));
		
		if(intval($json->responseData->cursor->estimatedResultCount))
			return intval($json->responseData->cursor->estimatedResultCount);
		else
			return 0;
			
	}
	
	public function getBingCount() {
	
		$html_bing_results = $this->getRemoteContents("http://www.bing.com/search?q=site:" . $this->url . "&FORM=QBRE&mkt=en-US");
		
		$document = new DOMDocument(); 
		
		$document->loadHTML($html_bing_results);
		
		$selector = new DOMXPath($document);
		
		$anchors = $selector->query('/html/body//span[@class="sb_count"]');
		
		foreach ($anchors as $node) {
		
			$bing_results = $this->innerHTML($node);
		
		}
		
		$bing_results = str_replace("results","",$bing_results);
		
		$bing_results = str_replace(",","",$bing_results);
		
		if(trim($bing_results)!="") return $bing_results;
			return 0;
		
	}
	
	public function getYahooCount() {
		
		$results = trim($this->getStringBetween($this->getRemoteContents("http://search.yahoo.com/search;_ylt=?p=site:" . $this->url),'">Next</a><span>',' results</span>'));
		
		$results= str_replace(",","",$results);
		
		if($results=="")
			return 0;
		
		return $results;
		
	}
	
	public function getGooglePagerank() {
		
		$data=json_decode($this->getRemoteContents("http://www.prapi.net/pr.php?url=http://$this->url&f=json"),true);
		
	return $data['pagerank'];
		
	}
	
	public function StrToNum($Str, $Check, $Magic) {
	
	$Int32Unit = 4294967296; // 2^32
	
	$length = strlen($Str);
	
	for ($i = 0; $i < $length; $i++) {
	
		$Check *= $Magic;
		
		if ($Check >= $Int32Unit) {
		
			$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
			
			$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
		
		}
		
		$Check += ord($Str{$i});
		
	}
	
	return $Check;
	
	}
	
	public function HashURL($String) {
	
		$Check1 = $this->StrToNum($String, 0x1505, 0x21);
		
		$Check2 = $this->StrToNum($String, 0, 0x1003F);
		
		$Check1 >>= 2;
		
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
		
		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
		
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
		
	return ($T1 | $T2);
		
	}
	
	public function CheckHash($Hashnum) {
	
		$CheckByte = 0;
		
		$Flag = 0;
		
		$HashStr = sprintf('%u', $Hashnum);
		
		$length = strlen($HashStr);
		
		for ($i = $length - 1; $i >= 0; $i --) {
		
			$Re = $HashStr{$i};
			
			if (1 === ($Flag % 2)) {
			
				$Re += $Re;
				
				$Re = (int)($Re / 10) + ($Re % 10);
				
			}
			
			$CheckByte += $Re;
			
			$Flag ++;
			
		}
		
		$CheckByte %= 10;
		
			if (0 !== $CheckByte) {
			
				$CheckByte = 10 - $CheckByte;
				
				if (1 === ($Flag % 2) ) {
				
					if (1 === ($CheckByte % 2)) {
					
						$CheckByte += 9;
						
					}
					
					$CheckByte >>= 1;
					
				}
			
		}
		
		return '7'.$CheckByte.$HashStr;
		
	}
	
	public function getDmozListing() {
	
		$dmoz_result = strtolower($this->getRemoteContents('http://dmoz.org/search/?q=' . urlencode($this->url)));
		
		if(strpos($dmoz_result,"dmoz sites")!==false)
			return 1;
		else
			return 0;
	}
	
	function getAlexaRank() {
	
		$xml = simplexml_load_string($this->getRemoteContents('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$this->url));
		$rank=0;
		
		if($xml->SD[1]) {
		
			$rank=(int)$xml->SD[1]->POPULARITY->attributes()->TEXT;
			return $rank;
			
		}
		
	return 0;
	
	}
	
	public function getSpeedScore() {
		
		$contents = $this->getRemoteContents('https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=http://' .$this->url);
		
		$json = json_decode($contents);
		
		if($json->score)
			return $json->score;
		else
			return 0;
		
	}
	
	public function getAlexaBacklinks() {
	
		$xml = simplexml_load_string($this->getRemoteContents('http://data.alexa.com/data?cli=10&dat=snbamz&url='.$this->url));
		
	return isset($xml->SD[0]->LINKSIN)?$xml->SD[0]->LINKSIN->attributes()->NUM:0;
	
	}
	
	public function getAlexaBounceRate() {
	
		$html_alexa_results = $this->getRemoteContents('http://www.alexa.com/siteinfo/' . $this->url);
		
		$document_alexa = new DOMDocument();
		
		$document_alexa->loadHTML($html_alexa_results);
		
		$selector_alexa = new DOMXPath($document_alexa);
		
		$content_alexa_bounce = $selector_alexa->query('/html/body//strong[@class="font-big2 valign"]');
		
		foreach($content_alexa_bounce as $bounce_alexa) {
		
			$bounce_rate = $this->innerHTML($bounce_alexa);
			
			break;
			
		}
		
		$bounce_rate = trim(str_replace('%','', $bounce_rate));
		
		if(!Is_numeric($bounce_rate))
			$bounce_rate=rand(40,70);
			
	return $bounce_rate;
	
	}
	
	public function getTwitterShares() {
	
		$json = json_decode($this->getRemoteContents("http://urls.api.twitter.com/1/urls/count.json?url=$this->url"), true);
		
	return isset($json['count'])?intval($json['count']):0;
	
	}
	
	public function getLinkedInShares() {
	
		$json = json_decode($this->getRemoteContents("https://www.linkedin.com/countserv/count/share?url=$this->url&format=json"), true);
		
	return isset($json['count'])?intval($json['count']):0;
	
	}
	
	public function getFacebookStats() {
	
		return json_decode($this->getRemoteContents("http://api.facebook.com/restserver.php?method=links.getStats&urls=http://$this->url&format=json
"),true);
	
	}
	
	public function getGooglePlusOneCount() {
	
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ");
		
		curl_setopt($curl, CURLOPT_POST, true);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode("http://www." . $this->url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		
		$curl_results = curl_exec ($curl);
		
		curl_close ($curl);
		
		$json = json_decode($curl_results, true);
		
	return isset($json[0]['result']['metadata']['globalCounts']['count'])?intval( $json[0]['result']['metadata']['globalCounts']['count'] ):0;
	
	}
	
	public function getStumbleUponShares() {
	
		$json = json_decode($this->getRemoteContents("http://www.stumbleupon.com/services/1.01/badge.getinfo?url=$this->url"), true);
		
	return isset($json['result']['views'])?intval($json['result']['views']):0;
		
	}
	
	public function getPinterestShares() {
	
		$return_data = $this->getRemoteContents("http://api.pinterest.com/v1/urls/count.json?url=http://$this->url");
		
		$json = json_decode(preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data), true);
		
	return isset($json['count'])?intval($json['count']):0;
	
	}
	
	public function getRemoteContents($url, $retries=3) {
	
		if (extension_loaded('curl') === true) {
		
			$ch=curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $url);
			
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36");
			
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
			
			$result = curl_exec($ch);
			
		} else {
		
			$options  = array('http' => array('user_agent' => 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36', 'timeout' => $this->timeout));
			
			$context  = stream_context_create($options);
			
			$result = trim(file_get_contents($url,false, $context));
			
		}
		if (trim($result)=="") {
		
			unset($ch);
			
			if ($retries >= 1) {
			
				return $this->getRemoteContents($url, $retries--);
				
			}
			
		}
		
	return $result;
	
	}
	
	public function getVKShares() {
	
		$str = $this->getRemoteContents("http://vk.com/share.php?act=count&index=1&url=http://" . $this->url);
		
		if (!$str) return 0;
		
		preg_match('/^VK.Share.count\((\d+),\s+(\d+)\);$/i', $str, $matches);
		
		$rq = $matches[2];
		
	return $rq;
	
	}
	
	public function getGoogleSafeBrowsingCheck() {
	
		$results = $this->getRemoteContents("http://www.google.com/safebrowsing/diagnostic?site=" . $this->url);
		
		if (preg_match('/This site is not currently listed as suspicious/',$results))
			return true;
		
	return false;
	
	}
	
	
	public function getSpamhausCheck() {
	
	$results = $this->getRemoteContents("http://www.spamhaus.org/query/domain/" . $this->url);
	
	if (preg_match('/is not listed in the DBL/',$results))
		return true;
	
	return false;
	
	}
	
	function domainAuthority($accessID,$secretKey) {
		
		$expires = time() + 300;
		
		$stringToSign = $accessID."\n".$expires;
		
		$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
		
		$urlSafeSignature = urlencode(base64_encode($binarySignature));
		
		$url="www.$url";
		
		$cols = "103079215108";
		
		$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/".urlencode($this->url)."?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;
		
		$content = $this->getRemoteContents($requestUrl);
		
		$domainAuthority = $this->getStringBetween($content,'{"pda":',',"upa"');
		
		if(is_numeric($domainAuthority))
			return $domainAuthority;
		
		return 0;
		
	}
	
	public function getStringBetween($string,$start,$end) {
	
		$string = " " . $string;
		
		$ini = strpos($string, $start);
		
		if ($ini == 0) return "";
		
		$ini+= strlen($start);
		
		$len = strpos($string, $end, $ini) - $ini;
		
	return substr($string, $ini, $len);
		
	}
	
	public function innerHTML(DOMNode $node) {
	
		$doc = new DOMDocument();
		
		foreach ($node->childNodes as $child) {
		
			$doc->appendChild($doc->importNode($child, true));
			
		}
		
	return $doc->saveHTML();
		
	}
}
?>