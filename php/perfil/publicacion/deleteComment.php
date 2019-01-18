<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-27 16:50:09
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-04 17:40:53
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
	$post=$publicacion->getPublicationByID($idPost);
	if($publicacion->isUserOwnerComment($_SESSION["iduser"],$idComment)||$post["usuarioAutor"]==$_SESSION["iduser"]||$post["usuarioDestino"]==$_SESSION["iduser"]){
		if($publicacion->deleteComment($idComment)){
			$resp["Success"]=true;	
		}
		
	}
}
echo json_encode($resp);