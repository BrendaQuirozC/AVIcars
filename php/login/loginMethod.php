<?php 
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
if(!empty($_SESSION)){
	$Usuario=new Usuario;
	if($Usuario->isNewNameOrMail($_SESSION["iduser"],$_POST["signUpEmail"],$_POST["changeNameUser"])){
		echo "WP";
	}
	else
	{
		echo $_SESSION["method"];
	}
}

?>