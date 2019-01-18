<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-28 16:03:44
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:15:52
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/report.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$coder = new Coder();
$Report = new Report;
$resp=array();
$Usuario = new Usuario;
$coder->decode($_POST["perfil"]);
$_POST["perfil"]=$coder->toEncode;
if($Usuario->getStatusUser($_SESSION["iduser"])==3){
	$resp["Error"]="Confirma tu correo.";
}
elseif(!empty($_SESSION["iduser"]) && isset($_POST["perfil"]))
{
	if(isset($_POST["type"]) && $_POST["type"]>0 && $_POST["type"]<10)
	{
		if ($_POST["garage"]) 
		{
			$coder->decode($_POST["garage"]);
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], null, $coder->toEncode, null, null)) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
		elseif ($_POST["publicacion"]) 
		{
			$coder->decode($_POST["publicacion"]);
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], $coder->toEncode, null, null, null)) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
		elseif ($_POST["comentario"]) 
		{
			$coder->decode($_POST["comentario"]);
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], null, null, null, null, $coder->toEncode)) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
		elseif ($_POST["car"]) 
		{
			$coder->decode($_POST["car"]);
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], null, null, $coder->toEncode, null)) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
		elseif ($_POST["ad"]) 
		{
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], null, null, null, $_POST["ad"])) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
		elseif ($_POST["adcomment"]) 
		{
			$coder->decode($_POST["adcomment"]);
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], null, null, null, null, null, $coder->toEncode)) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
		else
		{
			if ($Report -> doReport($_SESSION["iduser"], $_POST["type"], $_POST["perfil"], $_POST["text"], null, null, null, null)) 
			{
				$resp["Success"]="Tu apoyo nos ayuda a tener un sitio m&aacute;s respetuoso.";
			}
			else
			{
				$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
			}
		}
	}
	else
	{
		$resp["Error"]="Hubo un error, por favor intente m&aacute;s tarde.";
	}
}
else
{
	$resp["Error"]="No se puede reportar, existe un problema de conexi&oacute;n.";
}

echo json_encode($resp);