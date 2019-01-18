<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-03-28 14:20:27
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-04 12:51:06
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
$gid= $_POST["id"];
$apodo = $_POST["mail"];
$correo= $_POST["mail"];
$nombre= $_POST["name"];
$apellido= $_POST["lname"];
$response=array();
if($usr->verifyEmail($correo)){
	$usr -> loginExternal($apodo, $correo, NULL,"G+");
	$response["Success"]=$_SESSION["iduser"];
}else{

	if($lastId = $usr ->enviarUser($apodo,null,$correo,"G+",$gid,1))
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
				  			$usr -> loginExternal($apodo, $correo, NULL,"G+");
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