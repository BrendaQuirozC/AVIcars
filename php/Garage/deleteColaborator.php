<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-11 13:09:19
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-23 12:40:08
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
$coder->decode($_POST["u"]);
$userDecoded=$coder->toEncode;
$garageData = $Garage ->accountById($garageDecoded);
if($garageData["user"]!=$_SESSION["iduser"]&&!$Garage->getAUserAccount($_SESSION["iduser"], $garageDecoded,1)&&$userDecoded!=$_SESSION["iduser"]){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
$resp=array();
if($garageData["user"]!=$_SESSION["iduser"]&&$Garage->getAUserAccount($userDecoded, $garageDecoded,1)&&$userDecoded!=$_SESSION["iduser"]){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
if($userDecoded==$garageData["user"]){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
$coder->encode($garageData["user"]);
$ownerGarageCoded=$coder->encoded;
if($Garage->deleteColaborator($userDecoded,$garageDecoded)){
	if($userDecoded!=$_SESSION["iduser"]){
		$notification->addNotification(32,"Has sido eliminado como colaborador del garage ".$garageData["nameAccount"],$_SESSION["iduser"],$userDecoded,null,null,"/perfil/garage/timeline/?cuenta=$ownerGarageCoded&garage=".$_POST["g"],0,true);
	}
	$resp["Success"]=true;
}
else{
	$resp["Error"]=true;	
}
echo json_encode($resp);