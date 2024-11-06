<?php

$url_token = 'https://carrinho.{REDEACTED}.com.br/Checkout?ReturnUrl=https://www.{REDEACTED}.com.br';


function getToken($_data, $_string1, $_string2){
	preg_match_all("($_string1(.*)$_string2)siU", $_data, $match1);
	return $match1[1][0];
}



$random = rand(1000, 100000000);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://carrinho.{REDEACTED}.com.br/Checkout?ReturnUrl=https://www.{REDEACTED}.com.br");
curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/_cookie_id_'.$random.'.txt');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: carrinho.{REDEACTED}.com.br","Connection: keep-alive"));
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$data = curl_exec($ch);

$_token = getToken($data, 'var token = "', '";');

echo $_token;

if($_token){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://carrinho.{REDEACTED}.com.br/Api/checkout/Cliente.svc/Cliente/Login");
	curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/_cookie_id_'.$random.'.txt');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: carrinho.{REDEACTED}.com.br","Connection: keep-alive", "Accept: application/json, text/javascript, */*; q=0.01","X-Requested-With: XMLHttpRequest","Content-Type: application/json; charset=utf-8"));
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:59.0) Gecko/20100101 Firefox/59.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, '{"clienteLogin":{"Token":"'.$_token.'","Operador":"","IdUnidadeNegocio":8,"PalavraCaptcha":"","Senha":"{REDEACTED}","cadastro":"on","Email":"{REDEACTED}"},"mesclarCarrinho":true,"Token":"'.$_token.'","IdUnidadeNegocio":8,"Operador":""}');
	$data = curl_exec($ch);
	echo $data;

	if(strpos($data, '"Erro":false') !== false){
		echo "</br>";
		echo "Aprovada!";
	}elseif(strpos($data, '"Erro":true') !== false){
		echo "</br>";
		echo "Reprovada!";
	}

}

//29:57
?>

