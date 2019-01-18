<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-05-25 10:16:00
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-23 17:52:44
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
session_start();
$Image=new Image;
$Garage = new Garage;
$photo= $_POST["ruta"];
$type= $_POST["tipo"];
$idGarage= $_POST["garage"];
$garageData = $Garage ->accountById($idGarage);
if($garageData["user"]!=$_SESSION["iduser"]&&!$Garage->getAUserAccount($_SESSION["iduser"], $idGarage,1)){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
$Garage -> tmpGarageDelete($garageData["user"],$idGarage,$type);
$name = strtotime("now").$garageData["user"]; //nombre nuevo para la imagen 
$nombre_archivo = $_SERVER["DOCUMENT_ROOT"].$photo;
if(strpos($photo, 'Avatar') == true)
{
	$folder = "Avatar";
	$nombre_tmp='/users/'.$garageData["user"].'/'.$folder.'/'.$name.'.jpg';
	$destinofotoCar=$_SERVER["DOCUMENT_ROOT"].$nombre_tmp; //imagen con nombre y tamaño nuevo para carpeta de fotocar
	$Image->reduce($nombre_archivo, $destinofotoCar); //compresion de la imagen
	$changed = $Garage -> savePhotoGarage($idGarage, $nombre_tmp, NULL);
	$imagePublication=str_replace("/Avatar/", "/Avatar/p_", $photo);
	copy($_SERVER["DOCUMENT_ROOT"].$nombre_tmp, $_SERVER["DOCUMENT_ROOT"].$imagePublication);
	$typePub=6;
}
elseif (strpos($photo, 'Cover') == true)
{
	$folder = "Cover";
	$nombre_tmp=str_replace("/Cover/", "/Cover/cov_", $photo);
	$imagePublication=str_replace("/Cover/", "/Cover/p_", $photo);
	copy($_SERVER["DOCUMENT_ROOT"].$nombre_tmp, $_SERVER["DOCUMENT_ROOT"].$imagePublication);
	$changed = $Garage -> savePhotoGarage($idGarage, NULL, $photo);
	$typePub=7;
}

if($changed)
{
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
	$publicacion=new Publicacion;
	$coder->encode($_POST["garage"]);
	$garageEncoded=$coder->encoded;
	$coder->encode($garageData["user"]);
	$userEndoded=$coder->encoded;
	$colaborador=null;
	if($_SESSION["iduser"]!=$garageData["user"]){
		$colaborador=$_SESSION["iduser"];
	}
	$url="/perfil/garage/timeline/?cuenta=".$userEndoded."&garage=".$garageEncoded;
	$publicacion->addPublicacion("",$typePub,$garageData["user"],array(0=>$imagePublication), NULL, $url,1,$_POST["garage"],$_POST["garage"],null,null,null,null,$colaborador);
	if(strpos($photo, 'Avatar') ==true)
		unlink(($_SERVER['DOCUMENT_ROOT']).$photo);
	echo "success";
}
else
{
	unlink(($_SERVER['DOCUMENT_ROOT']).$photo);
	echo "error";
}
unset ($photo);
unset ($type);
unset ($name);
unset ($nombre_tmp);
unset ($nombre_archivo);
unset ($destinofotoCar);
unset ($changed);