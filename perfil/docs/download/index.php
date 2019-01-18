<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-30 10:11:36
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-16 16:14:51
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once $_SERVER["DOCUMENT_ROOT"]."/php/usuario.php";
$usuario=new Usuario;
if(empty($_SESSION)){

	header("HTTP/1.0 404 Not Found");
	echo "Debes iniciar sesión.";
}
elseif(!isset($_SESSION["iduser"])){
	header("HTTP/1.0 404 Not Found");
	echo "Debes iniciar sesión.";
}
if(!$usuario->verifyPassword($_SESSION["iduser"],$_POST["pwd"])){
	header("HTTP/1.0 404 Not Found");
	echo "Contrase&ntilde;a incorrecta.";
}
else{
	$user=$_SESSION["iduser"];
	$type=$_POST["t"];
	$file=$_POST["f"];
	$object=$_POST["u"];
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Archivo/archivo.php";
	$archivo=new Archivo;
	try{
		$url=$archivo->validateAccesFile($user,$file,$type,$object);
	}
	catch(Exeption $e){
		$url=false;
	}
	if($url){
		$extension = pathinfo($url, PATHINFO_EXTENSION);
		$extension=strtolower($extension);
		switch($extension){
			case "jpg":
				header('Content-Type: image/jpg');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "png":
				header('Content-Type: image/png');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "jpeg":
				header('Content-Type: image/jpeg');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "gif":
				header('Content-Type: image/gif');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "bmp":
				header('Content-Type: image/bmp');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "webp":
				header('Content-Type: image/webp');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "txt":
				header('Content-Type: text/plain');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "html":
				header('Content-Type: text/html');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "css":
				header('Content-Type: text/css');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "js":
				header('Content-Type: text/javascript');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "pdf":
				header('Content-Type: application/pdf');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "xml":
				header('Content-Type: application/xml');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "ppt":
				header('Content-Type: application/vnd.ms-powerpoint');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "pptx":
				header('Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "xls":
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "xlsx":
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "doc":
				header('Content-Type: application/msword');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "docx":
				header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			case "zip":
				header('Content-Type: application/zip');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
			default:
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename="'.strtotime("now").'.'.$extension.'"');
				break;
		}
		
		readfile($_SERVER["DOCUMENT_ROOT"].$url);
	}
	else{
		header("HTTP/1.0 404 Not Found");
		echo "Lo sentimos este documento no existe.";
	}
}