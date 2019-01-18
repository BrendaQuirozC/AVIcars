<?php
/**
 * User: Brenda Quiroz
 * Date: 22/02/2018
 * Time: 10:21 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
session_start();
$garage=new Garage;
$usr = new Usuario;
$database=new Database;
$db=$database->connect();
$id = $_SESSION["iduser"];
$nombeCuenta= $_POST["garageName"];
$uso= $_POST["garageUse"];
$privacidad= $_POST["garagePrivacy"];
$padre=0;
$accountStatus=1;

$nuevaCuenta = $usr->nuevaCuenta($id, $nombeCuenta,$privacidad,$accountStatus,$padre);
if($nuevaCuenta)
{
	if($usr->crearDetallesCuenta($nuevaCuenta, $uso))
	{
		$coder = new Coder($nuevaCuenta);
		$url = "/perfil/garage/timeline/?cuenta=".$_SESSION["usertkn"]."&garage=".$coder->encoded;
		require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Publicacion/publicacion.php";
		$publicacion=new Publicacion;
		$imgs=null;
		if($garage->aUserAccount($_SESSION["iduser"], $nuevaCuenta))
	  	{
			if($padre!=0 && $publicacion->addPublicacion($nombeCuenta,2,$_SESSION["iduser"],$imgs, null, $url, 1, $padre, $padre))
			{

				if($publicacion->addPublicacion($nombeCuenta,9,$_SESSION["iduser"],$imgs, null, $url, 1, $padre, $nuevaCuenta))
				{
					echo json_encode(array("Respuesta"=>true, "garage"=>$coder->encoded, "u"=>$_SESSION["usertkn"]));
				}
				else
				{
					echo json_encode(array("Respuesta"=>false));
				}
			}
			elseif($padre==0 && $publicacion->addPublicacion($nombeCuenta,9,$_SESSION["iduser"],$imgs, null, $url, 1, NULL, $nuevaCuenta))
			{
				echo json_encode(array("Respuesta"=>true, "garage"=>$coder->encoded, "u"=>$_SESSION["usertkn"]));
			}
			else
			{
				echo json_encode(array("Respuesta"=>false));
			}
		}
		else
		{
			echo json_encode(array("Respuesta"=>false));	
		}
	}
	else
	{
		echo json_encode(array("Respuesta"=>false));
	}
}
else
{
	echo json_encode(array("Respuesta"=>false));
}