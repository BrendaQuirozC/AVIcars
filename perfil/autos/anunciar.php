<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-10 12:16:35
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:51:47
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_SESSION))
{
	header('HTTP/1.1 403 Forbidden');
}
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Venta/Venta.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$Garage=new Garage;
$venta=new Venta;
$publicacion=new Publicacion;
$imgs=json_decode($_POST["img"],true);
$url = "/perfil/autos/detalles/?cuenta=".$_SESSION["iduser"]."&auto=".$_POST["version"];
$garage = $Garage ->instanciaById($_POST["version"]);
if(empty($imgs))
{
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/Garage.php";
	$Garage = new Garage;
	$imgsAuto = $Garage->imagenesGenerales($_POST["version"]);
	if(!empty($imgsAuto))
	{
		foreach ($imgsAuto as $key => $img) {
			$imgs[]=$img["a_avi_car_img_car"];
		}
	}
	else
	{
		$imgs=null;
	}
}
else
{
	$size=0;
	while ($size < sizeof($imgs)) {
		$imgs[$size] = '/users/'.$_SESSION["iduser"].'/'.$imgs[$size];
		$size++;
	}
}
$resp=array();
if($venta->changeStatusVenta($_POST["version"]))
{
	$resp["Success"]=true;
	$venta->levantarVenta($_POST["version"],$_POST["price"]);
	$publicacion->addPublicacion($_POST["publicacion"],5,$_SESSION["iduser"],$imgs, $_POST["price"], $url, 1, $garage[0]["o_avi_account_id"], $garage[0]["o_avi_account_id"], $_POST["version"]);
}
else{
	$resp["Error"]=true;
}
echo json_encode($resp);