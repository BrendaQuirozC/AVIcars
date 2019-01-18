<?php 

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-11 16:45:05
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:59:15
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once __DIR__."/"."Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_POST["liking"]);
session_start();
$Like = new Like;
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$usuario=new Usuario;
if($usuario->getStatusUser($_SESSION["iduser"])==3){
	echo '{"error": "Confirma tu cuenta"}';
}
elseif($Like->alreadyLike($_SESSION["iduser"], $_POST["tipo"],$coder->toEncode))
{
	if($Like->unlike($_SESSION["iduser"], $_POST["tipo"], $coder->toEncode))
	{
		echo '{"success": "unlike"}';
	}
	else
	{
		echo '{"error": "unlike"}';
	}
}
else
{
	echo '{"success": "unlike"}';
}

?>