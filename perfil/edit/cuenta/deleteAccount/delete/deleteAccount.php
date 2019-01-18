<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-29 18:57:52
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:52:25
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
if(!isset($_POST["razon"])){
	header("HTTP/1.0 403 Forbidden");
	$del=false;
}
if(empty($_POST["razon"])){
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
	$razones=$_POST["razon"];
	$delete=false;
	if($dataToken["status"]==2){
		$getinfoUser = $Usuario->getUserdetail($_SESSION["iduser"]);
		if($Usuario->disableUser($_SESSION["iduser"])){
			$delete=true;
		}
	}
	elseif($dataToken["status"]==3){
		$getinfoUser = $Usuario->getUserdetail($_SESSION["iduser"]);
		if($Usuario->disableUser($_SESSION["iduser"])){
			$delete=true;
		}

	}
	if($delete){
		$Usuario->insertUserDeletedInfo(json_encode($razones),$_SESSION["iduser"],$_SESSION["mail"], $getinfoUser["generoid"]!="" ? $getinfoUser["generoid"] : NULL , $getinfoUser["fechaNacimiento"]!="" ? $getinfoUser["fechaNacimiento"] : NULL, $getinfoUser["a_avi_useraddress_zip_code"]!="" ? $getinfoUser["a_avi_useraddress_zip_code"] : NULL);
		$Usuario->desableToken($tkn,$_SESSION["iduser"],json_encode($razones));
		$usrName = $Usuario -> getNameAndLastNameUser($_SESSION["iduser"]);
		$Usuario->sendDeleteMsg($dataToken["status"],$usrName, $_SESSION["mail"]);
		session_unset();
		session_destroy();
		$resp=array("Success"=>"/adios");
	}
	else{
		$resp=array("Error"=>true);
	}
}
echo json_encode($resp);