<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-04 14:02:22
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-05 12:56:58
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
session_start();
$Image=new Image;
$Garage = new Garage;
$Usuario = new Usuario;
$idCar = $_POST["car"];
$allowed = array('png', 'jpg', 'jpeg', 'webp');
$url= "";
$arrayFile=array();
if(isset($_FILES["imagenAuto"])){
	$arrayFile=$_FILES['imagenAuto'];
}
elseif (isset($_FILES["imagenAutoModalAd"])) {
	$arrayFile=$_FILES['imagenAutoModalAd'];
}
$response=array();
$extensionPhoto = pathinfo($arrayFile['name'], PATHINFO_EXTENSION);
$name=strtotime("now").$_SESSION["iduser"].".".$extensionPhoto;
if(!empty($extensionPhoto) && !in_array(strtolower($extensionPhoto), $allowed) ){
	$response["Error"]="2";
}
elseif(move_uploaded_file($arrayFile['tmp_name'], ($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/fotoCar/'.$name))
{
	$url = $_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/fotoCar/'.$name;

	shell_exec("../../scripts/image.sh $url $url");
	$new_name = strtotime("now").$_SESSION["iduser"];

	$nombre_tmp = '/users/'.$_SESSION["iduser"].'/fotoCar/'.$new_name.'.jpg';
	$destinofotoCar=$_SERVER["DOCUMENT_ROOT"].$nombre_tmp; //imagen con nombre y tamaño nuevo para carpeta de fotocar
	$Image->reduce($url, $destinofotoCar);
	if(!is_numeric($idCar))
	{
		$coder->decode($idCar);
		$idCar=$coder->toEncode;
	}
	if($newImg=$Garage -> addCarImg($nombre_tmp, $idCar))
	{
		$response=array("img"=>$nombre_tmp,"type"=>1, "idcar"=>$idCar, "imgid"=>$newImg);
	}
	else
	{
		$response["Error"]="1";
	}
}
else
{
	$response["Error"]="1";
}
echo json_encode($response);

unset ($response);
unset ($allowed);
unset ($url);
unset ($arrayFile);
unset ($extensionPhoto);
unset ($name);
unset ($new_name);
unset ($nombre_tmp);
unset ($destinofotoCar);