<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-21 17:03:38
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 10:14:01
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(isset($_POST["name"]))
{
	session_start();
	require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Publicacion/publicacion.php";
	$publicacion=new Publicacion;
	$resp=array();
	$pseudoNameImg = $publicacion->searchTempImages($_SESSION["user"].'/'.$_POST["name"], $_SESSION["iduser"]);
	if($pseudoNameImg!="")
	{
		if($publicacion ->publicacionDelOneImgTmp($_SESSION["user"].'/'.$_POST["name"]))
		{		
			unlink(($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/'.$pseudoNameImg);
			$resp["Success"]=$pseudoNameImg;
		}
		else
		{
			$resp["Error"]=true;

		}
	}
	else
	{
		$resp["Error"]=true;
	}
	echo json_encode($resp);
}