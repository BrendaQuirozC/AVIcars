<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-25 15:51:20
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-23 17:13:21
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
if($_POST["tipo"]==1)
{
	$Usuario = new Usuario;
	//tipo 1 es editar privacidad de Perfil
	if($Usuario->perfilUsuario($_SESSION["iduser"],null,$_POST["privacyType"]))
	{
		echo '{"Success":"Privacidad actualizada"}';
	}
}
elseif($_POST["tipo"]==2)
{
	$Garage = new Garage;
	//tipo 2 es editar privacidad de Garage
	if($Garage->getAUserAccount($_SESSION["iduser"], $_POST["privacy"],1))
	{
		if($Garage->changePrivacyId($_POST["privacyType"],$_POST["privacy"]))
		{
			echo '{"Success":"Privacidad actualizada"}';
		}
	}
}
elseif($_POST["tipo"]==3)
{
	/*$Instancia = new Instancia($idAuto);
	$instanciasCar = $Instancia->intanceByCar;*/
	$Garage = new Garage;
	$idAuto = $_POST["privacy"];
	//tipo 3 es editar privacidad de Auto
	$instanciaById = $Garage->instanciaById($idAuto);
	$garageById = $Garage->accountById($instanciaById[0]["o_avi_account_id"]);
	if($_SESSION["iduser"]==$garageById["user"]||$Garage->getAUserAccount($_SESSION["iduser"], $_POST["privacy"],2)) 
	{
		if($Garage->privacyInstanceCar($idAuto, $_POST["privacyType"]))
		{
			echo '{"Success":"Privacidad actualizada"}';
		}
	}
}