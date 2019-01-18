<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-04-19 13:23:05
 * @Last Modified by:   Cairo G. Resendiz
 * @Last Modified time: 2018-04-20 13:21:11
 */

session_start();
if(isset($_SESSION["user"]))
{
	$username = $_SESSION["user"];
}
else
{
	//SOLO PARA PRUEBA
	$username = "juan.gonzalez";
}
require_once "../funcionRecursiva.php";
$agregar = new Recursividad;
$json_way = $_POST["json_way"];
$versionID = $_POST["versionID"]; 
$do = true;
$lastVal = json_decode($_POST["lastVal"], true);
if(isset($lastVal["unidades"]) &&  $lastVal["unidades"]!="" && isset($lastVal["tipoUnidad"]))
{
	$data = $lastVal["unidades"].$lastVal["tipoUnidad"];
}
elseif(isset($lastVal["data"]) && $lastVal["data"]!=="")
{
	$data = $lastVal["data"];
}
else
{
	$do = false;
}
if($do)
{
	$way = json_decode($json_way, true);
	$tipo = key($way);
	$way = $agregar->throwLastValue($way, $data);	
	$getfullCaracteristicas = $agregar-> getArrayextraSpecific($versionID);
	$getfullCaracteristicasNjson= json_decode($getfullCaracteristicas, true);
	$nuevoArreglo = array();
	//$name = array_pop($auxiliarpop);
	//$field = $agregar -> columna_c_char($tipo, $way);
	//funcion para parsear json
	//$penultimo = array_pop($arrayNew);
	//print_r($way);
	if(!isset($getfullCaracteristicasNjson[$tipo]))
	{
	    $getfullCaracteristicasNjson[$tipo]=$way[$tipo];
	}
	else
	{
	    $nuevo = $agregar->replaceNumandText($way[$tipo], $getfullCaracteristicasNjson[$tipo], $nuevoArreglo,$tipo);
	    $getfullCaracteristicasNjson[$tipo]=$nuevo[$tipo];
	}
	$arraymerge=json_encode($getfullCaracteristicasNjson, JSON_UNESCAPED_UNICODE);
	$agregar->ExtendsCharUpdate($arraymerge, $versionID, $username);
	/*
	write_log("\n".$_SESSION["user"]." Agrego:   ". $json_way." \nEn anterior:    ".$getfullCaracteristicas. " \ncon id versión ".$versionID );
	if ($field!= NULL || $field != "")
	{
		//Update en la tabla
		$getCaracteristicas = array("Subcaracteristica" => $name, "Field" => $field);
		$agregar->DefaultCharUpdate($getCaracteristicas["Subcaracteristica"], $getCaracteristicas["Field"], $versionID, null);
		//echo $getCaracteristicas["Field"];

	}
	*/
}

?>