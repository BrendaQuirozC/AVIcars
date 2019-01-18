<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-14 15:11:14
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 13:16:50
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$Garage = new Garage;
$coder = new Coder();
$garageEncoded=$_POST["garage"];
$coder->decode($_POST["garage"]);
$_POST["garage"]=$coder->toEncode;

$cuentaEncoded=$_POST["cuenta"];
$coder->decode($_POST["cuenta"]);
$_POST["cuenta"]=$coder->toEncode;

$idCuenta = $_POST["cuenta"];
$idGarage= $_POST["garage"];
$nameGarage=$_POST["garageName"];
$calle=$_POST["garageStreet"];
$zip=$_POST["garageZipcode"];
$uso=$_POST["garageUse"];
$telefono= array($_POST["garagePhone"],$_POST["phonecode"]);
$wacell=(isset($_POST["cellphonewa"])) ? $_POST["cellphonewa"] : 0;
$celular= array($_POST["garageCellPhone"],$_POST["cellphonecode"],$wacell);

$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_POST["garage"],1);
$owner=($_SESSION["iduser"]==$_POST["cuenta"]);
if($colaborador||$owner){
	$respuesta=array();
	if($uso >0 && $uso <8)
	{
		$basicInfo= $Garage -> confGarageInfo($idGarage,$calle,$zip,$uso,$telefono,$celular);
		if($basicInfo)
		{
			$privacyInfo = $Garage-> confGarageName($idGarage, $nameGarage);	
			if($privacyInfo)
			{
				if($Garage -> confGarageExtras($_POST["garage"], $_POST["descripcion"]))
				{
					$respuesta["Success"]=$garageEncoded;
					$respuesta["user"]=$cuentaEncoded;
				}
				else{
					$respuesta["Error"]="Error";
				}
			}
			else{
				$respuesta["Error"]="Error";
			}
		}
		else{
			$respuesta["Error"]="Error";
		}
	}
	else{
		$respuesta["Error"]="Error";
	}
}
else{
	$respuesta["Error"]="Error";
}

echo json_encode($respuesta);
?>