<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
// A list of permitted file extensions
$allowed = array('png', 'jpg', 'jpeg', 'webp');
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
$image=new Image;
//print_r($_FILES);
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
	
	$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}

	if(move_uploaded_file($_FILES['file']['tmp_name'], ($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/fotoCar/'.$_FILES['file']['name'])){
		$Venta = new Venta;
		$uploadFileUser = $Venta -> imagenAutoTmp($_SESSION["venta"]["cuentaid"], '/users/'.$_SESSION["iduser"].'/fotoCar/'.$_FILES['file']['name'], 1);
		echo '{"status":"success"}';
		exit;
	}
}
elseif(isset($_POST["name"]) && $_POST["request"]==2)
{
	$Venta = new Venta;
	unlink(($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/fotoCar/'.$_POST["name"]);
	$Venta ->deletOneImgTmp('/users/'.$_SESSION["iduser"].'/fotoCar/'.$_POST["name"]);
	echo '{"status":"success"}';
	exit;
}

echo '{"status":"error"}';
exit;