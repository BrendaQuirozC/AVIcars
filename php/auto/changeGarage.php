<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-12 11:28:10
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 12:03:55
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
}
else{
	require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
	$coder = new Coder();
	$Instancia=new Instancia;
	$Garage=new Garage;
	$coder->decode($_POST["g"]);
	$garage=$coder->toEncode;
	$coder->decode($_POST["c"]);
	$car=$coder->toEncode;
	$garageContain= $Garage->instanciaById($car);
	if($Garage->getAUserAccount($_SESSION["iduser"],$garage,1)&&$Garage->getAUserAccount($_SESSION["iduser"],$garageContain[0]["i_avi_account_car_account_id"],1)){
		if($Instancia->changeGarage($car,$garage,$garageContain[0]["i_avi_account_car_car_id"])){
			$resp=array("c"=>$_SESSION["usertkn"],"a"=>$_POST["c"]);
		}
	}
	if(isset($resp)){
		$resp["Success"]=true;
	}
	else{
		$resp=array("Error"=>true);
	}	
}
echo json_encode($resp);
?>