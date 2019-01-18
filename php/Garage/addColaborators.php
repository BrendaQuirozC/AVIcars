<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-11 11:37:44
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-23 12:19:22
 */
session_start();
if(empty($_SESSION)){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');	
	exit;
}

require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
$Garage = new Garage;
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/notification/Notification.php";
$notification=new Notificacion;
$coder->decode($_POST["g"]);
$garageDecoded=$coder->toEncode;
$garageData = $Garage ->accountById($garageDecoded);
if($garageData["user"]!=$_SESSION["iduser"]&&!$Garage->getAUserAccount($_SESSION["iduser"], $garageDecoded,1)){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
$coder->encode($garageData["user"]);
$ownerGarageCoded=$coder->encoded;
$colaboradores=json_decode($_POST["p"],true);

foreach ($colaboradores as $c => $colaborador) {
	$coder->decode($colaborador);
	$colaboradorDecoded=$coder->toEncode;
	if($Garage->addColaborator($colaboradorDecoded,$garageDecoded,$_POST["l"])){
		$notification->addNotification(30,"Has sido asignado como colaborador del garage ".$garageData["nameAccount"],$_SESSION["iduser"],$colaboradorDecoded,null,null,"/perfil/garage/timeline/?cuenta=$ownerGarageCoded&garage=".$_POST["g"],0,true);
	}
}
