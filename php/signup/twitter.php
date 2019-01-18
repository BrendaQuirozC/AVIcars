<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-11-21 17:16:00
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-21 17:19:24
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Follow/Seguidor.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();

$usr = new Usuario;
$garage=new Garage;
$publicacion=new Publicacion;
$Seguidor=new Seguidor(2);
$Like=new Like;
session_start();
$gid= $_SESSION["idprev"];
$apodo = $_SESSION["arroba"];
$correo= $_SESSION["mail"];
$nombre= $_SESSION["name"];
$apellido= "";
$response=array();
if(isset($_SESSION["redirect"]))
	$response["redirect"]=$_SESSION["redirect"];
else
	$response["redirect"]="";
session_destroy();
session_unset();
if($usr->verifyEmail($correo)){
	$usr -> loginExternal($apodo, $correo, NULL,"TW");
	$response["Success"]=$_SESSION["iduser"];
}else{

	if($lastId = $usr ->enviarUser($apodo,null,$correo,"TW",$gid,1))
	{
		$usr-> createDir($lastId);
		
    	$Seguidor->seguir(1,$lastId,1);
    	$Like->likeit($lastId,2,1);
		$dataUser=array("o_avi_userdetail_name"=>$nombre,"o_avi_userdetail_last_name"=>$apellido);
	  	if($usr->enviarUserDetails($lastId, $dataUser))
	  	{
	  		if($usr->perfilUsuario($lastId))
	  		{
		  		if($idGarage=$usr->crearCuenta($lastId, "El Garage de $nombre",1,1))
		  		{
		  			if($garage->aUserAccount($lastId, $idGarage))
		  			{
		  				$coder->encode($lastId);
			    		$lasiIdCoded=$coder->encoded;
			    		$coder->encode($idGarage);
			    		$idGarageCoded=$coder->encoded;
			    		$url = "/perfil/garage/timeline/?cuenta=".$lasiIdCoded."&garage=".$idGarageCoded;
		  				if($usr->crearDetallesCuenta($idGarage))
				    	{
			  				
				  			$usr->sendConfirmationMail($correo,$nombre, $lastId);
				  			$usr -> loginExternal($apodo, $correo, NULL,"TW");
			  				$publicacion->addPublicacion("Bienvenido a ApoyoVial.",8,$lastId);
					    	$publicacion->addPublicacion("Este es tu primer Garage!.",2,$lastId,null,null,$url);
							$response["nuevo"]=true;
							$response["Success"]=$_SESSION["iduser"];
						}
		  			}
		  			else
		  			{
		  				$response["Error"]="Ocurrio un error inesperado, intente más tarde.";
		  			}
		  		}
		  		else
		  		{
		  			$response["Error"]="Ocurrio un error inesperado, intente más tarde.";		
		  		}
		  	}
		  	else
		  	{
		  		$response["Error"]="Ocurrio un error inesperado, intente más tarde.";
		  	}
	  	}
	  	else
	  	{
	  		$response["Error"]="Ocurrio un error inesperado, intente más tarde.";
	  	}
		
	}
	else
	{
		$response["Error"]="Ocurrio un error inesperado, intente más tarde.";
	}
}
echo json_encode($response);

