<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-20 09:47:49
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:58:19
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
session_start();
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["follower"]);
$_POST["follower"]=$coder->toEncode;
if(!empty($_SESSION) && isset($_POST["follower"]) && isset($_POST["type"]) && ($_POST["type"]>0 && $_POST["type"]<4))
{
	$Seguidor = new Seguidor;
	$Notificacion = new Notificacion;
	if ($_POST["type"]== 2)
	{
		$garageInfo = $Seguidor -> nameOfGarage($_POST["garage"]);
		$tipo_notif = 2;
		$porSeguir= $_POST["garage"];
		$success = "Ha seguido tu garage";
		$accepted_notif = 27;
		$coder->encode($_POST["garage"]);
		$garageCoded=$coder->encoded;
		$url = '/perfil/garage/timeline/?cuenta='.$_SESSION["usertkn"].'&garage='.$garageCoded;
		$garage = $_POST["garage"];
		$auto = NULL;
		$text = $garageInfo["garageName"];
	}
	elseif ($_POST["type"]== 3) 
	{
		$carName = $Seguidor->getPrivacyByCar($_POST["auto"]);
		$tipo_notif = 3;
		$porSeguir= $_POST["auto"];
		$success = "Ha seguido tu auto";
		$accepted_notif = 28;
		$coder->encode($_POST["auto"]);
		$autoCoded=$coder->encoded;
		$url = '/perfil/autos/detalles/?cuenta='.$_SESSION["usertkn"].'&auto='.$autoCoded;
		$garage = NULL;
		$auto= $_POST["auto"];
		$text = $carName["nombre"];
	}
	else
	{
		$tipo_notif = 1;
		$porSeguir= $_SESSION["iduser"];
		$success = "Te ha comenzado a seguir";
		$accepted_notif = 26;
		$url = '/perfil/seguidores/?cuenta='.$_SESSION["usertkn"];
		$garage = NULL;
		$auto= NULL;
		$text = NULL;
	}
	if($Seguidor -> acceptFollower($porSeguir,$_POST["follower"], $_POST["type"])) 
	{
		$Seguidor -> updateConfirmNotification($_SESSION["iduser"],$_POST["follower"], $tipo_notif);
		$Notificacion -> addNotification($accepted_notif, $text , $_SESSION["iduser"], $_POST["follower"], $garage, $auto, $url, 1,true);
		echo $success;
	}
	else
	{
		echo "error";
	}
	unset($tipo_notif);
	unset($porSeguir);
	unset($success);
	unset($accepted_notif);
	unset($url);
	unset($garage);
	unset($auto);
	unset($text);
}