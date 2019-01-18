<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-27 10:30:08
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:00:10
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_SESSION)||$_SESSION["iduser"]==""){
	header('HTTP/1.1 403 Forbidden');
    header('Content-Type: application/json; charset=UTF-8');
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$Usuario=new Usuario;
$resp=array();
$mails=array();
if(isset($_POST["mails"])){
	$mails=$_POST["mails"];
}
if($Usuario->saveConfigurationMails($_SESSION["iduser"],$mails)){
	$resp["Success"]=true;
}
else{
	$resp["Error"]=true;
}
echo json_encode($resp);