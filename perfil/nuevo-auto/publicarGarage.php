<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-03-14 17:15:10
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:54:11
 * CONTROLADOR
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
$Image=new Image;
$Venta = new Venta;
$publicacion=new Publicacion;
$vende=true;
$vin=null;
$precio=null;
$km=null;
$color=null;
$version=null;
$estado=null;
$count=0;
if($_POST["version"])
{
	$version=$_POST["version"];
}
if($_POST["vin"]!="")
{
	$vin=$_POST["vin"];
}
if($_POST["color"])
{
	$color=$_POST["color"];
}
if($_POST["estado"])
{
	$estado=$_POST["estado"];
}
$alias=$_POST["alias"];

$idCardetail = $Venta -> cardetail($version,$color,$vin);
//print_r($_SESSION);
if($idCardetail)
{
	$instanciaUsrAuto = $Venta -> carAccount($_SESSION["venta"]["cuentaid"], $idCardetail, $alias, $estado, 0);
	$imgAuto =array();
	$publicacionImg=array();
	$imgAuto = $Venta -> getfotos(1, $_SESSION["venta"]["cuentaid"]);
	if($instanciaUsrAuto)
	{
		$Instancia = new Instancia;
		$Instancia->createInstance($instanciaUsrAuto, $_SESSION["venta"]["cuentaid"]);
	}
	foreach ($imgAuto as $imgkey => $ruta)
	{
	    $nameFile = basename($ruta); //ruta con el nombre original de la imagen
	    $extension = pathinfo($nameFile, PATHINFO_EXTENSION); //extension de la imagen ej. jpg, png, gif
		$name = strtotime("now").$_SESSION["iduser"].$count; //nombre nuevo para la imagen 
		$nombre_archivo = $_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/fotoCar/'.$nameFile;
		$nombre_tmp='/users/'.$_SESSION["iduser"].'/fotoCar/'.$name.'.jpg';
		$destinofotoCar=$_SERVER["DOCUMENT_ROOT"].$nombre_tmp; //imagen con nombre y tamaño nuevo para carpeta de fotocar
		$urlExt_tmp='/users/'.$_SESSION["iduser"].'/'.$name.'.jpg';
		$urlExt = $_SERVER["DOCUMENT_ROOT"].$urlExt_tmp;//imagen con nombre y tamaño nuevo para publicar
		$Image->reduce($nombre_archivo, $destinofotoCar); //compresion de la imagen
		$Venta -> imagenAuto($instanciaUsrAuto, $nombre_tmp); //insertar la url de la imagen en la base de car_img

		if(copy($destinofotoCar, $urlExt)) 
		{
		    $publicacionImg[]=$urlExt_tmp;
		}
		unlink($nombre_archivo); 
    	$count++;
	}
	$Venta ->deletImgTmp($_SESSION["venta"]["cuentaid"]);
	//En funcion placas de carro Campo null falta agregar ruta de la imagen de la targeta
	//$Venta->placasCarro($_POST["placas"], $_POST["eFederale"], NULL, $idCardetail);
	$url = "/perfil/autos/detalles/?cuenta=".$_SESSION["iduser"]."&auto=".$instanciaUsrAuto;
	if(empty($publicacionImg))
	{
		$publicacionImg=null;
	}
	$publicacion->addPublicacion($alias,3,$_SESSION["iduser"],$publicacionImg, null, $url, 1, $_SESSION["venta"]["cuentaid"], $_SESSION["venta"]["cuentaid"], $instanciaUsrAuto);
	unset($_SESSION["venta"]);
	echo json_encode(array("Respuesta"=>true, "auto"=>$instanciaUsrAuto, "cuenta"=>$_SESSION["iduser"]));
}
else
{
	echo json_encode(array("Respuesta"=>false));
}

unset($imgAuto);
unset($idCardetail);
unset($vinimg);
unset($facturaimg);
unset($km);
unset($precio);
unset($instanciaUsrAuto);
unset($nameFile);
unset($nombre_archivo);
?>
