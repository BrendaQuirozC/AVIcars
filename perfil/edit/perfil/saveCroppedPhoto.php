<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-07-12 18:26:05
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-07 09:32:42
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/image.php";
$Image=new Image;
$name=strtotime("now").$_SESSION["iduser"];
$data=$_POST["ruta"];
list($type, $data) = explode(';', $data);
list(, $data)      = explode(',', $data);
$data = base64_decode($data);
if($_GET["t"]==1){
	$link="/users/".$_SESSION["iduser"]."/Avatar/".$name.".png";
	$url=$_SERVER["DOCUMENT_ROOT"].$link;
	file_put_contents($url, $data);
	unlink($_SERVER["DOCUMENT_ROOT"].$_GET["actual"]);
	$response["link"]=$link;
}
elseif ($_GET["t"]==3) {
	$link="/users/".$_SESSION["iduser"]."/fotoCar/".$name.".png";
	$car = $_POST["id"];
	$url=$_SERVER["DOCUMENT_ROOT"].$link;
	file_put_contents($url, $data);
	unlink($_SERVER["DOCUMENT_ROOT"].$_GET["actual"]);
	$response["link"]=$link;
	$response["car"]=$car;
}
else{
	$link="/users/".$_SESSION["iduser"]."/Cover/".$name.".png";
	$url=$_SERVER["DOCUMENT_ROOT"].$link;
	file_put_contents($url, $data);
	$full_name="/users/".$_SESSION["iduser"]."/Cover/cov_".$name.".png";
	$Image->reduce($_SERVER["DOCUMENT_ROOT"].$_GET["actual"], $_SERVER["DOCUMENT_ROOT"].$full_name, 1022, 1022);
	unlink($_SERVER["DOCUMENT_ROOT"].$_GET["actual"]);
	$response["link"]=$link;
}
echo json_encode($response);