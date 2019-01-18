<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-07-10 16:48:24
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:00
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once $_SERVER["DOCUMENT_ROOT"]."/php/usuario.php";
$usuario=new Usuario;
$resp=array();
if(empty($_SESSION)){

	$resp["Error"]="Debes iniciar sesión.";
}
elseif(!isset($_SESSION["iduser"])){
	$resp["Error"]="Debes iniciar sesión.";
}
if(!$usuario->verifyPassword($_SESSION["iduser"],$_POST["pwd"])){
	$resp["Error"]="Contrase&ntilde;a incorrecta.";
}
if(!isset($resp["Error"])){
	$resp["Success"]=true;
}
echo json_encode($resp);