<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-07 15:49:58
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:58:40
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["seguir"]);
$seguirCoded=$_POST["seguir"];
$_POST["seguir"]=$coder->toEncode;
session_start();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$usuario=new Usuario;
if($usuario->getStatusUser($_SESSION["iduser"])==3){
	echo 'Confirma tu cuenta';
}
elseif(!empty($_SESSION) && isset($_POST["seguir"]) && isset($_POST["type"]) && ($_POST["type"]>0 && $_POST["type"]<4))
{
	try 
	{
		$Seguidor = new Seguidor($_POST["type"],$_SESSION["iduser"], $_POST["seguir"]);
	} 
	catch (Exception $e) 
	{
		throw new Exception("Error: ".$e->getMessage());
	}
	if(!$Seguidor->idAquienSigues)
	{	
		switch ($_POST["type"]) {
			case 1:
				$privacidad=isset($Seguidor->getInfoPerfil($_POST["seguir"])["privacidad"]) ? $Seguidor->getInfoPerfil($_POST["seguir"])["privacidad"] : 1;
				break;
			case 2:
				$privacidad=isset($Seguidor->accountById($_POST["seguir"])["privacidad"]) ? $Seguidor->accountById($_POST["seguir"])["privacidad"] : 1;
				break;
			case 3:
				$auto = $Seguidor->getPrivacyByCar($_POST["seguir"]);
				$privacidad=isset($auto["privacidad"]) ? $auto["privacidad"] : 1;
				break;
		}
		if($privacidad==2) //publico
		{
			if($Seguidor->seguir(1))//es publico entonces acepta a todos
			{
				$Notificacion = new Notificacion;
				if($_POST["type"]==1)
				{
					$Notificacion -> addNotification(1,NULL , $_SESSION["iduser"], $_POST["seguir"], NULL, NULL, "/perfil/seguidores/?cuenta=".$seguirCoded, 1,true);
				}
				elseif($_POST["type"]==2)
				{
					$garageInfo = $Seguidor -> nameOfGarage($_POST["seguir"]);
					$coder->encode($garageInfo["userId"]);
					$userCoded=$coder->encoded;
					$coder->encode($garageInfo["garageId"]);
					$garageCoded=$coder->encoded;
					$Notificacion -> addNotification(2,$garageInfo["garageName"], $_SESSION["iduser"] , $garageInfo["userId"], $garageInfo["garageId"], NULL, "/perfil/garage/seguidores/?cuenta=".$userCoded."&garage=".$garageCoded, 1,true);
				}
				elseif($_POST["type"]==3)
				{
					$coder->encode($auto["dueno"]);
					$userCoded=$coder->encoded;
					$coder->encode($auto["carId"]);
					$autoCoded=$coder->encoded;
					$Notificacion -> addNotification(3,$auto["nombre"], $_SESSION["iduser"] , $auto["dueno"], NULL, $auto["carId"], "/perfil/autos/detalles/seguidores/?cuenta=".$userCoded."&auto=".$autoCoded, 1,true);
				}
				echo "Siguiendo";
			}
		}
		else
		{
			if($Seguidor->seguir()) //si es privado o secreto envia un 0 en aceptado hasta que el usuario lo acepte cambia a 1
			{
				$Notificacion = new Notificacion;
				if($_POST["type"]==1)
				{
					$Notificacion -> addNotification(12,NULL , $_SESSION["iduser"], $_POST["seguir"], NULL, NULL, "/perfil/seguidores/?cuenta=".$seguirCoded, 1,true);
				}
				elseif($_POST["type"]==2)
				{
					$garageInfo = $Seguidor -> nameOfGarage($_POST["seguir"]);
					$coder->encode($garageInfo["userId"]);
					$userCoded=$coder->encoded;
					$coder->encode($garageInfo["garageId"]);
					$garageCoded=$coder->encoded;
					$Notificacion -> addNotification(13, $garageInfo["garageName"] , $_SESSION["iduser"], $garageInfo["userId"], $garageInfo["garageId"], NULL, "/perfil/garage/seguidores/?cuenta=".$userCoded."&garage=".$garageCoded, 1,true);
				}
				elseif($_POST["type"]==3)
				{
					$coder->encode($auto["dueno"]);
					$userCoded=$coder->encoded;
					$coder->encode($auto["carId"]);
					$autoCoded=$coder->encoded;
					$Notificacion -> addNotification(24,$auto["nombre"], $_SESSION["iduser"] , $auto["dueno"], NULL, $auto["carId"], "/perfil/autos/detalles/seguidores/?cuenta=".$userCoded."&auto=".$autoCoded, 1,true);
				}
				echo "Solicitud enviada";
			}
		}
	}
}