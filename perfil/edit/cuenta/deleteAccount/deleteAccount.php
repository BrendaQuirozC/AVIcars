<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-29 18:57:52
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:33
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
session_start();
$del=true;
if(empty($_SESSION)){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if(!isset($_SESSION["iduser"])){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if(!isset($_POST["t"])){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if(!isset($_POST["r"])){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if($_POST["r"]!=2&&$_POST["r"]!=3){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
$Usuario=new Usuario;
$dataToken=$Usuario->getTokenToDelete($_POST["t"],$_SESSION["iduser"]);
if(empty($dataToken)){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if(!$del){
	header("HTTP/1.0 403 Forbidden");
	$resp=array("Error"=>true);
}
else{
	$tkn=$_POST["t"];
	$razon=$_POST["r"];
	if($Usuario->updateTokenStatus($_SESSION["iduser"],$tkn,$razon)){
		$resp=array("Success"=>"/perfil/edit/cuenta/deleteAccount/delete/?t=".$tkn);
	}
	else{
		$resp=array("Error"=>true);
	}
}
echo json_encode($resp);