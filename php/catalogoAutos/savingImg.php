<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-07-31 13:21:32
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-07 16:22:51
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
session_start();
$Image=new Image;
$Garage = new Garage;
$Usuario = new Usuario;
$photo = $_POST["ruta"];
$car = $_POST["idcar"];

if($lastCover = $Garage -> getLastCoverCar($photo, $car, 6)){
	unlink($_SERVER["DOCUMENT_ROOT"].$lastCover["url"]);
}

if($Garage -> addCarImg($photo, $car, 6))
{
	$Usuario -> tmpDelete($_SESSION["iduser"],6);
	$resp["id"] = $car;
	$resp["ruta"]=$photo;
}
else
{
	$resp["error"]=true;
}


echo json_encode($resp);

unset($photo);
unset($car);