<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-30 11:33:02
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:16:30
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
}
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/report.php";
$coder = new Coder();
$coder->decode($_POST["p"]);
$report=new Report;
if($report->blockUser($_SESSION["iduser"],$coder->toEncode)){
	$resp=array("Success"=>true);
}
else{
	$resp=array("Error"=>true);
}
echo json_encode($resp);