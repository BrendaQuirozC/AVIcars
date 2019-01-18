<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-21 14:36:12
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 12:01:16
 */
session_start();
if(empty($_SESSION)){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;	
}
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;	
}
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Utilities/coder.php";
$coder = new Coder();
$Instancia = new Instancia;
if(!isset($_GET["c"])){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;	
}
$coder->decode($_GET["c"]);
$carID=$coder->toEncode;
if(!$carID){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;	
}
$car=$Instancia->getInfoinstance($carID);
if(!$Instancia->getAUserAccount($_SESSION["iduser"],$car["garageId"],2)&&$_SESSION["iduser"]!=$car["idUser"]){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;
}
$dataSend="";
foreach ($_POST as $key => $value) {
	$dataSend.=$key."=".$value."&";
}
$dataSend=substr($dataSend, 0, -1);
$curl=curl_init();
curl_setopt_array($curl, array(
	CURLOPT_URL => "http://crm.infosite.com.mx//index.php?entryPoint=WebToLeadCapture",
	CURLOPT_SSL_VERIFYHOST => 0,
	CURLOPT_SSL_VERIFYPEER => 0,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => $dataSend,
	CURLOPT_HTTPHEADER => array(
			"Content-Type" => "application/x-www-form-urlencoded"
		)
	)
);
curl_exec($curl);
$error = curl_error($curl);
curl_close($curl);
//echo $error;