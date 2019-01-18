<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-29 18:57:52
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:39
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
if(!isset($_POST["c"])){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if($_POST["c"]!==$_SESSION["iduser"]){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if(!$del){
	header("HTTP/1.0 403 Forbidden");
}
else{
	$Usuario=new Usuario;
	$toDelete=$_POST["c"];
	$tknDeleteSession=bin2hex(openssl_random_pseudo_bytes(32));
	if($Usuario->addTokenDelete($_POST["c"],$tknDeleteSession)){
		$resp=array("Success"=>"/perfil/edit/cuenta/deleteAccount/?t=".$tknDeleteSession);
	}
	else{
		$resp=array("Error"=>true);
	}
}
echo json_encode($resp);