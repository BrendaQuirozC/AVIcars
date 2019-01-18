<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-23 13:52:00
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-30 13:17:42
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Garage = new Garage;
$coder->decode($_POST["publicacion"]);
$publicacion = new Publicacion($coder->toEncode);
$getPublication = $publicacion->getPublicacionById();
$cuentaDestino = $getPublication["cuentaDestino"];
$resp=array();
if(!empty($_SESSION) && ($publicacion->author==$_SESSION["iduser"]) || $Garage->getAUserAccount($_SESSION["iduser"], $cuentaDestino,3))
{
		if(isset($_POST["get"]))
		{
			$resp["contenido"]=$publicacion->texto;
		}
		else
		{
			if($_POST["publicaciontext"]!="")
			{
				if($publicacion->publicacionUpdate($_POST["publicaciontext"],$coder->toEncode))
				{
					$resp["Success"]=true;
				}
				else
				{
					$resp["Error"]=true;
				}
			}
			else
			{
				$resp["Error"]=true;
			}
		}
}
echo json_encode($resp);
