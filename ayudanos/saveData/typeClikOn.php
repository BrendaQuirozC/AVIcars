<?php
/**
 * Created by PhpStorm.
 * User: Juan Gonzalez
 * Date: 12/12/2017
 * Time: 01:09 PM
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
    $way = json_decode($json_way, true);
    if($_POST["lastVal"] == "false")
    {
    	$lasVal = "null";
    }
    else
    {
    	$lasVal = "on";
    }
	$way = $agregar->throwLastValue($way, $lasVal);					//poner el ultimo valor al arreglo
    $getfullCaracteristicas = $agregar-> getArrayextraSpecific($versionID);		//obtener el json de las caracteristicas
    $getfullCaracteristicasNjson= json_decode($getfullCaracteristicas, true);
    if($getfullCaracteristicasNjson)
    {
        $arraymerge=array_replace_recursive($getfullCaracteristicasNjson, $way); //hace un mage entre los dos arreglos
        $arraymerge=json_encode($arraymerge, JSON_UNESCAPED_UNICODE);			 //Pasa de nuevo a json para integrar a la DB
    }
    else
    {
        $arraymerge = json_encode($way, JSON_UNESCAPED_UNICODE);
    }
    $agregar->ExtendsCharUpdate($arraymerge, $versionID, $username);
    /*
    write_log("\n".$_SESSION["user"]." Agrego:  ". $json_way." \nEn anterior:    ".$getfullCaracteristicas. " \ncon id versión ".$versionID );

    if ($field!= NULL || $field != "")
    {
        //Update en la tabla
        $getCaracteristicas = array("Subcaracteristica" => $name, "Field" => $field);
        $Querys->DefaultCharUpdate($getCaracteristicas["Subcaracteristica"], $getCaracteristicas["Field"], $versionID, null);
        //echo $getCaracteristicas["Field"];

    }*/
?>