<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-04-03 11:56:27
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:16:06
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';
session_start();
$usr = new Usuario;
$usrName = $usr -> getNameAndLastNameUser($_SESSION["iduser"]);
$sended = $usr -> sendConfirmationMail($_SESSION["mail"],$usrName,$_SESSION["iduser"], $usr->verifyTokenByUser($_SESSION["iduser"],2));
if($sended)
{
	echo 1;
}
else
{
	echo 0;
}
