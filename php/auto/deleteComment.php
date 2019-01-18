<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-18 11:33:43
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:56:09
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
session_start();
$anuncio = new Anuncio;
$resp=array();
$coder->decode($_POST["c"]);
$comment=$anuncio->getCommentByID($coder->toEncode);
$owner=$anuncio->getOwnerAd($comment["adv"]);
if($anuncio->isUserOwnerComment($_SESSION["iduser"],$coder->toEncode)||$owner["owner"]==$_SESSION["iduser"]){
	if($anuncio->deleteComment($coder->toEncode)){
		$resp["Success"]=true;	
	}
}
echo json_encode($resp);