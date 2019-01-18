<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-24 11:46:26
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-05 12:56:51
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Image=new Image;
$Garage = new Garage;
$coder->decode($_POST["garage"]);
$_POST["garage"]=$coder->toEncode;
$garage = $_POST["garage"];
$allowed = array('png', 'jpg', 'jpeg', 'webp');
$url= "";
$arrayFile=array();

if(isset($_FILES["imagenAutoModal"])){
	$arrayFile=$_FILES['imagenAutoModal'];
}
elseif (isset($_FILES["imagenAutoModalAd"])) {
	$arrayFile=$_FILES['imagenAutoModalAd'];
}
$response=array();
$extensionPhoto = pathinfo($arrayFile['name'], PATHINFO_EXTENSION);
$name=strtotime("now").$_SESSION["iduser"].".".$extensionPhoto;
if($Garage->getAUserAccount($_SESSION["iduser"], $garage,2))
{
	if(!empty($extensionPhoto) && !in_array(strtolower($extensionPhoto), $allowed) ){
		$response["Error"]=2;
	}
	elseif(move_uploaded_file($arrayFile['tmp_name'], ($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/fotoCar/'.$name))
	{
		$url = $_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/fotoCar/'.$name;
		shell_exec("../../scripts/image.sh $url $url");
		$new_name = strtotime("now").$_SESSION["iduser"];
		$nombre_tmp = '/users/'.$_SESSION["iduser"].'/fotoCar/'.$new_name.'.jpg';
		$destinofotoCar=$_SERVER["DOCUMENT_ROOT"].$nombre_tmp; //imagen con nombre y tamaño nuevo para carpeta de fotocar
		$Image->reduce($url, $destinofotoCar);
		$nuevaFoto = $Garage ->imagenTmp($garage, $new_name.'.jpg', 1, $_SESSION["iduser"]);
		if($nuevaFoto)
		{
			$response["Success"]=$nombre_tmp;
			$response["img"]=$new_name.'.jpg';
		}
		else
		{
			$response["Error"]=1;
		}
	}
	else
	{
		$response["Error"]=1;
	}
}
else
{
	$response["Error"]=1;
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
unset ($nuevaFoto);