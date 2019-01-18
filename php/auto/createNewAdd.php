<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-08-27 12:41:11
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 12:03:04
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["iduser"])){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder=new Coder;
$Garage=new Garage;
$marcas=Auto::getMarcas();
$submarcas=Auto::getSubMarcas();
$modelos=Auto::getModels();
$versiones=Auto::knowVersion();
$coder->decode($_POST["idCar"]);
$currAuto=$coder->toEncode;

if($currAuto!=0){
	$imagenesAd=$Garage->imagenesGenerales($currAuto);
}
$specificCar= $Garage->getPrivacyByCar($currAuto);
if($specificCar["dueno"]!=$_SESSION["iduser"]&&!$Garage->getAUserAccount($_SESSION["iduser"],$specificCar["garage"],2)){
	header('HTTP/1.0 403 Forbidden');
	echo "Tu no puedes ver esto! D;";
	exit;
}
$coder->encode($specificCar["carId"]);
$carEncoded=$coder->encoded;
$coder->encode($specificCar["garage"]);
$garageEncoded=$coder->encoded;
$detalles = $Garage->getUserdetail($_SESSION["iduser"]);
$detallesGarage=$Garage->getGarageInfo($specificCar["garage"]);
$adDetailCar=Auto::adCar($currAuto);
$countOf= Auto::getCarifAd($specificCar["carId"]) ;
$datacar=array(
	"Alias" => ($currAuto) ? $specificCar["nombre"] : '',
	"Precio" => $specificCar["price"],
	"Currency" => ($currAuto) ?  $specificCar["currency"] : '',
	"brand" => (($specificCar['brand']=="" && $specificCar['nombreMarca']=="") ? 0 : (($specificCar['brand']=="" && $specificCar['nombreMarca']!="") ? -1 : $specificCar['brand'])),
	"subbrand" => (($specificCar['subbrand']=="" && $specificCar['nombreSubmarca']=="") ? 0 : (($specificCar['subbrand']=="" && $specificCar['nombreSubmarca']!="") ? -1 : $specificCar['subbrand'])),
	"model" => (($specificCar['model']=="" && $specificCar['nombreModelo']=="") ? 0 : (($specificCar['model']=="" && $specificCar['nombreModelo']!="") ? -1 : $specificCar['model'])),
	"versionmd" => (($specificCar['version']=="" && $specificCar['nombreVersion']=="") ? 0 : (($specificCar['version']=="" && $specificCar['nombreVersion']!="") ? -1 : $specificCar['version'])),
	"Marca" => $specificCar["nombreMarca"],
	"Submarca" => $specificCar["nombreSubmarca"],
	"Modelo" => $specificCar["nombreModelo"],
	"Version" => $specificCar["nombreVersion"],
	"auto" => $_POST["idCar"],
	"garage" =>  $garageEncoded,
	"Garageid" => $garageEncoded ,
	"Car" => $carEncoded,
	"Imagenes" => $imagenesAd,
	"phone1" => array(
		"code" => (!isset($adDetailCar["locationphone"]) && $detallesGarage["telefonocode"]) ? $detallesGarage["telefonocode"] : (isset($adDetailCar["locationphone"]) ? $adDetailCar["locationphone"]  : "MX"),
		"phone" => (!isset($adDetailCar["phone"]) && $detallesGarage["telefono"]) ? $detallesGarage["telefono"] : (isset($adDetailCar["phone"]) ? $adDetailCar["phone"] : "" ),
		"wa" => ((!isset($adDetailCar["phonewa"])) ? false : ($adDetailCar["phonewa"]==1) ? true : false),
	),
	"phone2" => array(
		"code" => (!isset($adDetailCar["locationphone2"]) && $detallesGarage["celularcode"]) ? $detallesGarage["celularcode"] : (isset($adDetailCar["locationphone2"]) ? $adDetailCar["locationphone2"]  : "MX"),
		"phone" => (!isset($adDetailCar["phone2"]) && $detallesGarage["celular"]) ? $detallesGarage["celular"] : (isset($adDetailCar["phone2"]) ? $adDetailCar["phone2"] : "" ),
		"wa" => ((!isset($adDetailCar["phone2wa"])&&$detallesGarage["celularwa"]==1) ? true : (isset($adDetailCar["phone2wa"]) ? (($adDetailCar["phone2wa"]==1) ? true : false) : false))
	),
	"phone3" => array(
		"code" => isset($adDetailCar["locationphone3"]) ? $adDetailCar["locationphone3"] : "MX",
		"phone" => isset($adDetailCar["phone3"]) ? $adDetailCar["phone3"] : "",
		"wa" => ((!isset($adDetailCar["phone3wa"])) ? false : ($adDetailCar["phone3wa"]==1) ? true : false),
	),
	"address" => array( "cp" => $detallesGarage["zip"], "calle" => $detallesGarage["calle"]),
	"mail1" => (!isset($adDetailCar["email"]) && $detalles["o_avi_user_email"]) ? $detalles["o_avi_user_email"] : (isset($adDetailCar["email"]) ? $adDetailCar["email"] : "" ),
	"mail2" => isset($adDetailCar["email2"]) ? $adDetailCar["email2"] : "",
	"mainPhone" => $detalles["phonecode"],
	"mainMail" => $detalles["o_avi_user_email"],
	"texto" => isset($adDetailCar["texto"]) ? $adDetailCar["texto"] : "",
	"nombre" => $detalles["o_avi_userdetail_name"],
	"apellido" => $detalles["o_avi_userdetail_last_name"],
	"nacimiento" => $detalles["fechaNacimiento"],
	"cp" => $detalles["a_avi_useraddress_zip_code"],
	"age" => 0
);

if($datacar["nacimiento"]!=""){
	$born=date_create($datacar["nacimiento"]);
	$hoy=date_create("now");
	$diff=date_diff($born,$hoy);
	$datacar["age"] = $diff->format("%y");
}

if ($countOf["auto"] != 0 && $countOf["status"]==1 ){
	$coderAd=new Coder($countOf["anuncio"]);
	$datacar["AlreadyAd"]=true;
	$datacar["adCoded"]=$coderAd->encoded;
}
echo json_encode($datacar);
unset($currAuto);
unset($datacar);
?>

