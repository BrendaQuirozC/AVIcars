<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-05-25 10:16:00
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:53:03
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
session_start();
$Image=new Image;
$Usuario = new Usuario;
$photo= $_POST["ruta"];
$type= $_POST["tipo"];
$Usuario -> tmpDelete($_SESSION["iduser"],$type);

if(strpos($photo, 'Avatar') ==true)
{
	$folder = "Avatar";
	$changed = $Usuario -> savePhoto($_SESSION["iduser"], $photo, NULL);
	$imagePublication=str_replace("/Avatar/", "/Avatar/p_", $photo);
	copy($_SERVER["DOCUMENT_ROOT"].$photo, $_SERVER["DOCUMENT_ROOT"].$imagePublication);
	$typePub=6;
}
elseif (strpos($photo, 'Cover') == true)
{
	$folder = "Cover";
	$changed = $Usuario -> savePhoto($_SESSION["iduser"], NULL, $photo);
	$nombre_tmp=str_replace("/Cover/", "/Cover/cov_", $photo);
	$imagePublication=str_replace("/Cover/", "/Cover/p_", $photo);
	copy($_SERVER["DOCUMENT_ROOT"].$nombre_tmp, $_SERVER["DOCUMENT_ROOT"].$imagePublication);
	$typePub=7;
}

if($changed)
{
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
	$publicacion=new Publicacion;
	$url="/perfil/?cuenta=".$_SESSION["usertkn"];
	$publicacion->addPublicacion("",$typePub,$_SESSION["iduser"],array(0=>$imagePublication),NULL,$url);
	echo "success";
}
else
{
	unlink(($_SERVER['DOCUMENT_ROOT']).$photo);
	echo "error";
}
unset ($photo);
unset ($type);
unset ($nombre_tmp);
unset ($destinofotoCar);
unset ($changed);