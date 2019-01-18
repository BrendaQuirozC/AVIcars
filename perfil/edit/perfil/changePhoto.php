<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-09 14:35:57
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-05 12:27:37
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
//Archivo para poner las imagenes en temporal, tanto de portada como de avatar para el perfil
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
session_start();
$allowed = array('png', 'jpg', 'jpeg', 'webp');
$Usuario = new Usuario;
$Image=new Image;
$photo =""; 
$arrayFile=array();
$resp=array();

if(isset($_FILES['portada']))
{
	$arrayFile=$_FILES['portada'];
	$type= 4;
	$folder = "Cover";
	$extensionPhoto = pathinfo($arrayFile['name'], PATHINFO_EXTENSION);
	$name=strtotime("now").$_SESSION["iduser"].".".$extensionPhoto;
	$photo='/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$name;
	if(!empty($extensionPhoto) && !in_array(strtolower($extensionPhoto), $allowed) )
	{
		$resp["Error"]=true; //error imagen no valida';
	}
	elseif(move_uploaded_file($arrayFile['tmp_name'], $_SERVER["DOCUMENT_ROOT"].$photo))
	{
		$nombre_archivo= $_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$name; //ruta de la imagen con el tamaño original
		shell_exec("../../../scripts/image.sh $nombre_archivo $nombre_archivo");
		$new_name = strtotime("now").$_SESSION["iduser"].'9';
		$nombre_tmp='/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$new_name.'.jpg';
		$destinofotoCar=$_SERVER["DOCUMENT_ROOT"].$nombre_tmp; //imagen con nombre y tamaño nuevo para carpeta de fotocar
		$Image->reduce($nombre_archivo, $destinofotoCar, 1022, 1022); //compresion de la imagen
		unlink(($_SERVER['DOCUMENT_ROOT']).$photo);

			
			if($Usuario -> tmpCover($new_name.'.jpg', $_SESSION["iduser"],$type))
			{
				$resp=array("img"=>$nombre_tmp,"type"=>1);
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

}
elseif (isset($_FILES['avatar'])) 
{
	$arrayFile=$_FILES['avatar'];
	$type= 3;
	$folder = "Avatar";
	$extensionPhoto = pathinfo($arrayFile['name'], PATHINFO_EXTENSION);
	$name=strtotime("now").$_SESSION["iduser"].".".$extensionPhoto;
	$photo='/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$name;
	if(!empty($extensionPhoto) && !in_array(strtolower($extensionPhoto), $allowed) )
	{
		$resp["Error"]=true; //error imagen no valida';
	}
	elseif(move_uploaded_file($arrayFile['tmp_name'], $_SERVER["DOCUMENT_ROOT"].$photo))
	{
		$nombre_archivo= $_SERVER["DOCUMENT_ROOT"].'/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$name; //ruta de la imagen con el tamaño original
		shell_exec("../../../scripts/image.sh $nombre_archivo $nombre_archivo");
		$new_name = strtotime("now").$_SESSION["iduser"].'9';
		$nombre_tmp='/users/'.$_SESSION["iduser"].'/'.$folder.'/'.$new_name.'.jpg';
		$destinofotoCar=$_SERVER["DOCUMENT_ROOT"].$nombre_tmp; //imagen con nombre y tamaño nuevo para carpeta de fotocar
		$Image->reduce($nombre_archivo, $destinofotoCar ); //compresion de la imagen
		unlink(($_SERVER['DOCUMENT_ROOT']).$photo);

			
			if($Usuario -> tmpCover($new_name.'.jpg', $_SESSION["iduser"],$type))
			{
				$resp=array("img"=>$nombre_tmp,"type"=>1);
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
}
else
{
	$resp["Error"]=true;
}
echo json_encode($resp);

unset ($allowed);
unset ($arrayFile);
unset ($extensionPhoto);
unset ($name);
unset ($new_name);
unset ($nombre_tmp);
unset ($destinofotoCar);
?>
