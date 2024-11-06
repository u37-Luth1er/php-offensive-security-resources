<?php 
error_reporting(0);
$array[] = null;
$names=file('php_list');
foreach($names as $name)
{
   	array_push($array,$name);
}
function CheckingAlives($_proxy){
	set_time_limit(3);
	$url = 'http://dynupdate.no-ip.com/ip.php';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);         // URL for CURL call
	curl_setopt($ch, CURLOPT_PROXY, $_proxy);     // PROXY details with port
	//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);   // Use if proxy have username and password
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); // If expected to call with specific PROXY type
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  // If url has redirects then go to the final redirected URL.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Do not outputting it out directly on screen.
	curl_setopt($ch, CURLOPT_HEADER, 0);   // If you want Header information of response else make 0
	curl_setopt($ch,CURLOPT_TIMEOUT,2.5);
	$curl_scraped_page = curl_exec($ch);
	curl_close($ch);
	if($curl_scraped_page){
		return True;
	}else{
		return false;
	}
}

while(True){
	$rand_keys = array_rand($array);
	$proxy = $array[$rand_keys];
	$checking = CheckingAlives($proxy);
	$proxy = trim(preg_replace('/\s\s+/', ' ', $proxy));
	if ($checking == True){
		$_working = array(
				'status' => 'live',
				'proxy'  => $proxy
		);
		$encode = json_encode($_working);
		print_r ($encode);

		break;
	}else{
		continue;
	}

}

?>
