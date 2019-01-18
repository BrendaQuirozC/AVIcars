<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
/*Controlador para cambiar tu contraeña despues de recibido el crreo.*/
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';

$usr = new Usuario;
$email= $_POST["getPwd"];
$newPassword =$usr -> createToken($email,1);
if ($newPassword['token']) {
	$usrName = $usr -> getNameAndLastNameUser($newPassword['id']);
	$usr->recoverPwd($email,$newPassword['token'],$usrName);
	echo 1;
}
else
{
	echo 0;
}