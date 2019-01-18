<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-18 11:21:06
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:56:15
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
session_start();
$anuncio = new Anuncio;
$resp=array();
$coder->decode($_POST["c"]);
if($anuncio->isUserOwnerComment($_SESSION["iduser"],$coder->toEncode)){
	if($anuncio->editComment($coder->toEncode,$_POST["t"])){
		$resp["Success"]=$_POST["t"];
	}
}
echo json_encode($resp);