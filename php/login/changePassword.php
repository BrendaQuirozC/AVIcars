<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
/*Controlador para cambiar tu contraeña despues de recibido el crreo.*/
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';
$usr = new Usuario;
$pwd= $_POST["newPassword"];
$resp = false;
$PwdChecked =false;
if(isset($_POST["token"]))
{
	$token = base64_decode(urldecode($_POST["token"]));
	$verify = $usr-> verifyToken($token, 1);
	if (!empty($verify))
	{
		$user = $verify["idUser"];
		$change = $usr-> changePassword($user,$pwd);
		if ($change) {
			$usrName = $usr -> getNameAndLastNameUser($verify["idUser"]);
			$usr -> changedPasswordMail($verify["userMail"],$usrName);
			$delete = $usr -> deleteToken($token,1);
			$resp = true;
		}
	}
}
else /*Caso cuando se desea modificar la contraseña*/
{	
	$verifyPwd=$_POST["verifyPwd"];	
	session_start();
	$idUser=$_SESSION["iduser"];
	if($usr->login($_SESSION["user"], null, $verifyPwd, true))
	{
    $PwdChecked = true;
	}
	if($PwdChecked==true)
	{
		$change = $usr-> changePassword($idUser,$pwd);	
		if ($change) 
		{
			$usrName = $usr -> getNameAndLastNameUser($_SESSION["iduser"]);
			$usr -> changedPasswordMail($_SESSION["mail"],$usrName);
			$resp = true;
		}
	}
	else
	{
		$resp = false;		
	}	
}
if ($resp) {
	echo 1;
}
else{
	echo "No es la contraseña correcta.";
}
