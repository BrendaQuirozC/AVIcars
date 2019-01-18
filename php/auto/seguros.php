<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-18 16:06:33
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 12:00:59
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
$dataSend.="carbrand_c=".urlencode($_POST["otraMarcaInput"]);
$dataSend.="&carsubbrand_c=".urlencode($_POST["otroModeloInput"]);
$dataSend.="&carmodel_c=".urlencode($_POST["otroAnoInput"]);
$dataSend.="&carversion_c=".urlencode($_POST["otroVersionInput"]);
$dataSend.="&phone_mobile=".urlencode($_POST["telefono"]);
$dataSend.="&email1=".urlencode($_POST["email"]);
$dataSend.="&first_name=".urlencode($_POST["nombre"]);
$dataSend.="&last_name=".urlencode($_POST["apellido"]);
$dataSend.="&primary_address_postalcode=".urlencode($_POST["cp"]);
$dataSend.="&age_c=".urlencode($_POST["edad"]);
$dataSend.="&campaign_id=b28f7f95-4bc1-66c6-0555-58d3fb75262d";
$dataSend.="&assigned_user_id=c87c3bdb-11ec-fbde-b9b8-58b6bcd15f01";
$dataSend.="&lead_source=AVI cars";
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