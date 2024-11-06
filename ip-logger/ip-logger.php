<?php

$usuario = @unserialize(file_get_contents('http://ip-api.com/php'));



if ($usuario['status'] == 'success'){
	$ip        = $usuario['query'];
	$pais      = $usuario['country'];
	$estado    = $usuario['regionName'];
	$cidade    = $usuario['city'];
	$cep       = $usuario['zip'];
	$latitude  = $usuario['lat'];
	$longitude = $usuario['lon'];
	$empresa   = $usuario['org'];



	echo "<pre>";

	echo "IP: "                  .$ip."\n";
	echo "Pais: "                .$pais."\n";
	echo "Estado: "              .$estado."\n";
	echo "Cidade: "              .$cidade."\n";
	echo "Latitude: "            .$latitude."\n";
	echo "Longitude: "           .$longitude."\n";
	echo "Provedor De internet: ".$empresa."\n";
	echo "<pre>";

}else{
	echo "Ocorreu um Erro.\n";

}





try{ 

	$pdo = new PDO("mysql:dbname=logger;host=localhost","root","xxx");
	$sql = "SELECT * FROM ipgger WHERE ip='$ip'";
	$sql = $pdo -> query($sql);
	if($sql -> rowCount() > 0){
		foreach ($sql -> fetchAll() as $verify) {
			echo " return false";
		}

	}else{

		try{
			
			$pdo = new PDO("mysql:dbname=logger;host=localhost","root","xxx");
			$sql = "INSERT INTO ipgger SET ip = '$ip', pais = '$pais', estado = '$estado', cidade = '$cidade', empresa = '$empresa'";

			$sql = $pdo -> query($sql);

			echo 'sucess';

		}catch(PDOException $e){
			echo "Error: ".$e;
		}
	}

}catch(PDOException $e){
	echo "Error: ".$e;
}



?>
