<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-27 16:01:46
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-04 17:41:49
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
session_start();
$publicacion=new Publicacion;
$resp=array();
$coder->decode($_POST["c"]);
$idComment=$coder->toEncode;
$comentario=$publicacion->getComment($idComment);
$idPost=$comentario["post"];
if($publicacion->UserAccessToPublication($_SESSION["iduser"],$idPost))
{
	if($publicacion->isUserOwnerComment($_SESSION["iduser"],$idComment)){
		if($publicacion->editComment($idComment,$_POST["t"])){
			$resp["Success"]=$_POST["t"];
		}
		
	}
}
echo json_encode($resp);