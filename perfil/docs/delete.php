<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-30 11:42:50
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:11
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/usuario.php";
$usuario=new Usuario;
if(empty($_SESSION)){

	header("HTTP/1.0 403 Forbbiden");
	$response["Error"]="Contraseña incorrecta";
}
elseif(!isset($_SESSION["iduser"])){
	header("HTTP/1.0 403 Forbbiden");
	$response["Error"]="Contraseña incorrecta";
}
elseif($usuario->verifyPassword($_SESSION["iduser"],$_POST["pwd"])) {
	$user=$_SESSION["iduser"];
	$type=$_POST["t"];
	$file=$_POST["f"];
	$object=$_POST["u"];
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Archivo/archivo.php";
	$archivo=new Archivo;
	if($url=$archivo->validateAccesFile($user,$file,$type,$object)){
		$infoType=$archivo->getType($type);
		$objectType=$infoType["objeto"];
		if($archivo->delete($file,$objectType))
		{
			$response["Success"]="¡Se ha borrado el documento correctamente!";
		}
		else
		{
			$response["Error"]="No se pude borrar el archivo";
		}
	}
	else
	{
		$response["Error"]="Contraseña incorrecta";
	}

}
else
{
	$response["Error"]="Contraseña incorrecta";
}
echo json_encode($response);