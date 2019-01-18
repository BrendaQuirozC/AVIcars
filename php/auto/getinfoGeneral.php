<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-31 10:49:25
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 12:02:52
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
}
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Utilities/coder.php";

$Instancia = new Instancia;
$coder=new Coder;
$coder->decode($_POST["idCar"]);
$car=$Instancia->getInfoinstance($coder->toEncode);
if($car["idUser"]!=$_SESSION["iduser"]&&!$Instancia->getAUserAccount($_SESSION["iduser"],$car["garageId"],2)){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;
}
$edadActualPrestamo="";
$nac=date_create($car["nac"]);
$today=date_create("now");
$diff=date_diff($nac,$today);
$edad=$diff->y;
$car["edad"]=$edad;
echo json_encode($car);