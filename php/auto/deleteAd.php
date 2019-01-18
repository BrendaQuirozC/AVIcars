<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-07-06 15:18:32
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-19 12:32:41
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/database/conexion.php';
require_once __DIR__."/Anuncio.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Anuncio=new Anuncio;
$Garage=new Garage;
$coder->decode($_POST["a"]);
$adID=$coder->toEncode;
$owner=$Anuncio->getOwnerAd($coder->toEncode);
session_start();
if(!empty($_SESSION) && ($_SESSION["iduser"]==$owner["owner"]||$Garage->getAUserAccount($_SESSION["iduser"],$owner["garage"],2)))
{
	if($Anuncio->hideAd($coder->toEncode))
	{

		$idCar = $Anuncio->getCarbyAd($adID);
		if($Anuncio->hideAdSellCar($idCar["auto"]))
		{
			if ($Anuncio->hideAdPublication($_SESSION["iduser"], $idCar["auto"])) 
			{
				echo '{"Success": "Se borr&oacute; el anuncio correctamente"}';
			}
			else{
				echo '{"Error": "Algo sali&oacute; mal con las publicaciones del anuncio,intente m&aacute;s tarde. "}';
			}
		}
		else{
			echo '{"Error": "Error inesperado, por favor recargue la p&aacute;gina."}';
		}	
	}
	else{
		echo '{"Error": "Error inesperado, intente m&aacute;s tarde."}';
	}
	/*
	if($Anuncio->deleteAdContact($coder->toEncode))
	{
		if($Anuncio->deleteAdLocation($coder->toEncode))
		{
			if($Anuncio->deleteAdFollow($coder->toEncode))
			{
				if($Anuncio->deleteAdbyId($coder->toEncode))
				{
					echo '{"Success": "Se borr&oacute; el anuncio correctamente"}';
					$Anuncio->deleteAdCommets($coder->toEncode);
					$Anuncio->deleteAdLikes($coder->toEncode);
				}
				else
				{
					echo '{"Error": "No se pudo borrar Anuncio"}';
				}
			}
			else
			{
				echo '{"Error": "Error inesperado, intente m&aacute;s tarde"}';
			}
		}
		else
		{
			echo '{"Error": "Error inesperado, intente m&aacute;s tarde"}';
		}	
	}
	else
	{
		echo '{"Error": "Error inesperado, intente m&aacute;s tarde"}';
	}
	*/
}
else
{
	echo '{"Error": "No te pertenece el Anuncio."}';
}