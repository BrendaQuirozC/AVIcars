<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-23 11:53:11
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 11:57:21
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["publicacion"]);
$Garage = new Garage;
$publicacion = new Publicacion($coder->toEncode);
$getPublication = $publicacion->getPublicacionById();
$puedeBorrar=false;
if(isset($getPublication["cuentaDestino"]))
{
	$cuentaDestino = $getPublication["cuentaDestino"];
	$garage = $Garage ->accountById($cuentaDestino);
	if($garage["user"]==$_SESSION["iduser"] || $Garage->getAUserAccount($_SESSION["iduser"], $cuentaDestino,3))
	{
		$puedeBorrar=true;
	}
	else
	{
		$puedeBorrar=false;
	}
}
$resp=array();
if(!empty($_SESSION) && ($publicacion->author==$_SESSION["iduser"] || $publicacion->container==$_SESSION["iduser"] || $puedeBorrar))
{
	$imagenes = json_decode(base64_decode($publicacion->imagenes, true));
	if(!empty($imagenes))
	{
		foreach ($imagenes as $img => $i) {
			unlink($_SERVER['DOCUMENT_ROOT'].$i);
		}
	}
	if($publicacion->publicacionDelete($coder->toEncode))
	{
		$resp["Success"]="Publicación eliminada";
	}
	else
	{
		$resp["Error"]="Error inesperado, intente más tarde";
	}
}
else
{
	$resp["Error"]="No puedes borrar esta publicación";
	//intento de hackeo :V 
}
echo json_encode($resp);
?>