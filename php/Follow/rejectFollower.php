<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-20 09:08:07
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:58:29
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["follower"]);
$_POST["follower"]=$coder->toEncode;
if(!empty($_SESSION) && isset($_POST["follower"]) && isset($_POST["type"]) && ($_POST["type"]>0 && $_POST["type"]<4))
{
	$Seguidor = new Seguidor;
	if ($_POST["type"]== 2)
	{
		$tipo_notif = 2;
		$idGarage= $_POST["garage"];
		$idAuto= null;
		$porSeguir= $_POST["garage"];
	}
	elseif ($_POST["type"]== 3) 
	{
		$tipo_notif = 3;
		$idGarage= null;
		$idAuto= $_POST["auto"];
		$porSeguir= $_POST["auto"];
	}
	else
	{
		$tipo_notif = 1;
		$idGarage= null;
		$idAuto= null;
		$porSeguir=$_SESSION["iduser"];
	}
	if($Seguidor -> reject($porSeguir,$_POST["follower"], $_POST["type"])) 
	{
		$Seguidor -> deleteRequestNotification($_SESSION["iduser"],$_POST["follower"], $tipo_notif, $idGarage, $idAuto);
		echo "success";
	}
}

unset($tipo_notif);
unset($idGarage);
unset($idAuto);