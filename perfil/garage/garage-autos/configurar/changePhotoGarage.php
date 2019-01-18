<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-09 14:35:57
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-05 12:52:29
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
//Archivo para poner las imagenes en temporal, tanto de portada como de avatar para el garage
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
session_start();
$allowed = array('png', 'jpg', 'jpeg', 'webp');
$Garage = new Garage;
$Image=new Image;
$photo =""; 
$arrayFile=array();
$idGarage = $_POST["garage"];
if (isset($_FILES['portadaGarage'])) 
{
	$arrayFile=$_FILES['portadaGarage'];
	$type= 6;
	$folder = "Cover";
}
elseif (isset($_FILES['imagenGarage'])) 
{
	$arrayFile=$_FILES['imagenGarage'];
	$type= 5;
	$folder = "Avatar";
}
$extensionPhoto = pathinfo($arrayFile['name'], PATHINFO_EXTENSION);
$name=strtotime("now").$_SESSION["iduser"];
if(!empty($extensionPhoto) && !in_array(strtolower($extensionPhoto), $allowed))
{
	$resp["Error"]=true; //error si imagen portada no valida"}';
}
elseif(move_uploaded_file($arrayFile['tmp_name'], ($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$name.".".$extensionPhoto))
{
	$photo = '/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$name;
	$nombre_archivo= $_SERVER["DOCUMENT_ROOT"].$photo.".".$extensionPhoto;
	$nombre_archivo_new= $_SERVER["DOCUMENT_ROOT"].$photo.".jpg";
	shell_exec("../../../../scripts/image.sh $nombre_archivo $nombre_archivo");
	if (isset($_FILES['portadaGarage'])) 
	{
		$Image->reduce($nombre_archivo, $nombre_archivo_new, 1022, 1022);
	}
	elseif (isset($_FILES['imagenGarage'])) 
	{
		$Image->reduce($nombre_archivo, $nombre_archivo_new );
	}
	if($Garage -> tmpCoverGarage($photo.".jpg", $_SESSION["iduser"],$idGarage, $type))
	{
		$resp=array("img"=>$photo.".jpg","type"=>2);
	}
	else
	{
		$resp["Error"]=true;
	}
}
else
{
	$resp["Error"]=true;
}
echo json_encode($resp);
?>