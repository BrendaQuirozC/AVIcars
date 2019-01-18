<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2018-12-03 16:31:03
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-12-11 10:23:19
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
if (isset($_FILES['portadaAuto'])) 
{
	$arrayFile=$_FILES['portadaAuto'];
	$type= 6;
	$folder = "fotoCar";
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
	shell_exec("../../scripts/image.sh $nombre_archivo $nombre_archivo");
	if (isset($_FILES['portadaAuto'])) 
	{
		$Image->reduce($nombre_archivo, $nombre_archivo_new, 1022, 1022);
	}
	if($Garage -> tmpCoverGarage($photo.".jpg", $_SESSION["iduser"],$idGarage, $type))
	{
		$resp=array("img"=>$photo.".jpg","type"=>2, "idcar"=>$_POST["car"]);
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