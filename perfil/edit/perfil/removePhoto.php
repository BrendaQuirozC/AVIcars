<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-05-25 15:31:15
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:57
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
session_start();
$Usuario = new Usuario;
$photo = $_POST["ruta"];
$type= $_POST["tipo"];
//$resp = false;
if($Usuario -> tmpDelete($_SESSION["iduser"],$type))
{
	unlink(($_SERVER['DOCUMENT_ROOT']).$photo);
	echo "success";
	//$resp=true;
}
else
{
	echo "error";
}
