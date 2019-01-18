<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-24 13:47:46
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-07 13:43:34
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Utilities/coder.php";
$Garage = new Garage;
$Venta = new Venta;
$coder = new Coder();
$usuario = $Venta->userImgCarTmpbyRuta(isset($_POST["idImg"]) ? $_POST["idImg"] : NULL);
if(isset($usuario) &&  $usuario==$_SESSION["iduser"]){
	unlink($_SERVER["DOCUMENT_ROOT"]."/users/".$_SESSION["iduser"]."/fotoCar/".$_POST["idImg"]);
	$Venta -> deletOneImgTmp($_POST["idImg"]);
	echo "success";
}
elseif (!$usuario) 
{
	if ( is_numeric($_POST["idCar"])) {
		$idCar = $_POST["idCar"];
	}
	else {
		$coder->decode($_POST["idCar"]);
		$idCar=$_POST["auto"]=$coder->toEncode;
	}
	
	if ($idCar && isset($_POST["idImg"])) 
	{
		$urlImg = $Garage -> imagenPorCoche($idCar,$_POST["idImg"]);
		$borrado = $Garage -> deleteCarImg($idCar,$_POST["idImg"]);
		if($borrado)
		{
			unlink($_SERVER["DOCUMENT_ROOT"]."/".$urlImg);
			echo "success";
		}
		else
		{
			echo "error";
		}
	}
	elseif ($idCar) {
		$infoCar = $Garage -> getPrivacyByCar($idCar);
		$clean = $Garage -> selectAllImgTmp($_SESSION["iduser"], $infoCar["garage"]);
		if($clean)
		{
			foreach ($clean as $cl => $imgClean) {
				unlink($_SERVER["DOCUMENT_ROOT"]."/users/".$_SESSION["iduser"]."/fotoCar/".$imgClean);
			}
			if($Garage -> deleteAllCarImg($infoCar["garage"])){
				echo "success";
			}
			else{
				echo "error";
			}
			
		}
		else
		{
			echo "error";
		}
	}
	else{
		echo "error";
	}

}
else
{
	echo "error";
}