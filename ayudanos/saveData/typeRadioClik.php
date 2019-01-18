<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-04-18 16:09:24
 * @Last Modified by:   Cairo G. Resendiz
 * @Last Modified time: 2018-04-19 13:07:06
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

$input = "Si";
$json_way = $_POST["json_way"];
$versionID = $_POST["versionID"];
$way="";
$way = json_decode($json_way,true);
if($_POST["lastVal"] == "false")
{
	$lasVal = "null";
}
else
{
	$lasVal = "Si";
}
$way = $agregar->throwLastValue($way, $lasVal);	
$getfullCaracteristicas = $agregar-> getArrayextraSpecific($versionID);
$getfullCaracteristicasNjson= json_decode($getfullCaracteristicas, true);
$nuevoArreglo = array();
$tipo = key($way);

//$field = $Querys -> columna_c_char($tipo, $way);
//funcion para parsear json

if(json_decode($getfullCaracteristicas, true))
{
    if (!isset($getfullCaracteristicasNjson[$tipo]))
    {
        $getfullCaracteristicasNjson[$tipo] = $way[$tipo];
    }
    else
    {
        $nuevo = $agregar->replaceRecursive($way[$tipo], $getfullCaracteristicasNjson[$tipo], $nuevoArreglo, $tipo);
        $getfullCaracteristicasNjson[$tipo] = $nuevo[$tipo];
    }
    $arraymerge = json_encode($getfullCaracteristicasNjson, JSON_UNESCAPED_UNICODE);
    $agregar->ExtendsCharUpdate($arraymerge, $versionID, $username);
    //write_log("\n".$_SESSION["user"]." Agrego:  ". $json_way." \nEn anterior:    ".$getfullCaracteristicas. " \ncon id versión ".$versionID );
}
else
{
    $arraymerge=json_encode($way, JSON_UNESCAPED_UNICODE);
    $agregar->ExtendsCharUpdate($arraymerge, $versionID, $username);
    //write_log("\n".$_SESSION["user"]." Agrego:  ". $json_way." \nEn anterior:    ".$getfullCaracteristicas. " \ncon id versión ".$versionID );
}
/*
if ($field!= NULL || $field != "")
{
    //Update Lineal
    $getCaracteristicas = array("Subcaracteristica" => $name, "Field" => $field);
    $agregar->DefaultCharUpdate($getCaracteristicas["Subcaracteristica"], $getCaracteristicas["Field"], $versionID, null);
}
*/
?>