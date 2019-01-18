<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-30 13:39:06
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:00:12
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
}
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/report.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$Report = new Report;
$coder=new Coder;
$unblock=true;
foreach ($_POST["blocked"] as $b => $blocked) {
	$coder->decode($blocked);
	if(!$Report->unBlock($_SESSION["iduser"],$coder->toEncode)){
		$unblock=false;
	}
}
if($unblock){
	$resp=array("Success"=>true);
}
else{
	$resp=array("Error"=>true);
}
echo json_encode($resp);