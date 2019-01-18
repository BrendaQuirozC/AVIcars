<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-10 10:25:00
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-13 11:41:28
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/Venta/Venta.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder=new Coder;
$coder->decode($_POST["a"]);
$_POST["a"]=$coder->toEncode;
$auto=$_POST["a"];
$Instancia = new Instancia($auto);
$instanciasCar = $Instancia->intanceByCar;
session_start();
$response=array();
$Garage = new Garage;
$Anuncio=new Anuncio;
$resp =array();
if($instanciasCar)
{
	$garageByInstance = $instanciasCar[key($instanciasCar)]["garage"];
	$autoid=$instanciasCar[key($instanciasCar)]["auto"];
	if($_SESSION["iduser"]==$Garage->accountById($garageByInstance)["user"] || $_SESSION["iduser"]==$Garage->getAUserCollabByCar($autoid)) 
	{
		//verificar que el usuario contenga el Garage de la instancia
		$Venta=new Venta;
		
		if($Venta->vendido($autoid))
		{
			if($Anuncio->anuncioVendido($autoid))
			{
				$resp["Success"] ='Se actualizo a Vendido';
				$coder_1 = new Coder($autoid);
				$coder_2 = new Coder($Garage->accountById($garageByInstance)["user"]);
				$resp["url"]="?cuenta=".$coder_2->encoded."&auto=$coder_1->encoded";
			}
			else
			{
				$resp["Error"] ='Error inesperado4';
			}
		}
		else
		{
			$resp["Error"] ='Error inesperado3';
		}
	}
	else
	{
		$resp["Error"] ='Error inesperado2';
	}
}
else
{
	$resp["Error"] ='Error inesperado1';
}
echo json_encode($resp, JSON_UNESCAPED_UNICODE);