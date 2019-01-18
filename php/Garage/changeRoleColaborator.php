<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-18 17:59:35
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-23 12:23:38
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
$coder->decode($_POST["u"]);
$userDecoded=$coder->toEncode;
$resp=array();
if($garageData["user"]!=$_SESSION["iduser"]&&$Garage->getAUserAccount($userDecoded, $garageDecoded,1)){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
if($userDecoded==$_SESSION["iduser"]){
	header('HTTP/1.0 403 Forbidden');	
	exit;
}
$coder->encode($garageData["user"]);
$ownerGarageCoded=$coder->encoded;
if($Garage->editLevelColaborator($userDecoded,$garageDecoded,$_POST["l"])){
	$notification->addNotification(31,"Tienes un nuevo estatus de colaborador en el garage ".$garageData["nameAccount"],$_SESSION["iduser"],$userDecoded,null,null,"/perfil/garage/timeline/?cuenta=$ownerGarageCoded&garage=".$_POST["g"],0,true);
	$resp["Success"]=true;
}
else{
	$resp["Error"]=true;	
}
echo json_encode($resp);