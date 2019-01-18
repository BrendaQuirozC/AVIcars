<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 11/01/2018
 * Time: 12:14 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Follow/Seguidor.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();

$usr = new Usuario;
$garage = new Garage;
$publicacion=new Publicacion;
$Seguidor=new Seguidor(2);
$Like=new Like;
$apodo = $_POST["signUpUsername"];
$contrasena= $_POST["signUpPassword"];
$correo= $_POST["signUpEmail"];
$nombre= $_POST["signUpName"];
$apellido= $_POST["signUpLastName"];
$nacimiento= $_POST["signUpBirthdate"];
$response=array();
if($usr->verifyEmail($correo)){
    $response["Error"]="El correo ya existe.";
}elseif($usr->verifyUserName($apodo)){
    $response["Error"]="El usuario ya existe.";
}else{
    $verifpwd=$usr->hashPassword($contrasena);
    if($lastId = $usr->enviarUser($apodo,$verifpwd,$correo)){
    	$dataUser=array("o_avi_userdetail_name"=>$nombre,"o_avi_userdetail_last_name"=>$apellido,"o_avi_userdetail_birth_date"=>$nacimiento);
    	$usr-> createDir($lastId);
    	
    	$Seguidor->seguir(1,$lastId,1);
    	$Like->likeit($lastId,2,1);
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
				    		$usr->login(NULL, $correo, $contrasena);
				    		$publicacion->addPublicacion("Bienvenido a ApoyoVial.",8,$lastId);
				    		$publicacion->addPublicacion("Este es tu primer Garage!. $apodo",1,$lastId,null,null,$url);
				    		$response["Success"]=$_SESSION["usertkn"];
				    		$usr->sendConfirmationMail($correo,$nombre, $lastId);
				    		
				    	}
				    	else
				    	{
				    		$response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde 1.";
				    	}
			    	}
			    	else
			    	{
			    		$response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde 2.";
			    	}
		    		
			    }
			    else
			    {
			    	$response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde 2.5.";
			    }
		    }
		    else
		    {
		    	$response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde 3.";
		    }
	  	}
	  	else
	  	{
	  		$response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde 3.";
	  	}
    }
    else
    {
    	$response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde 4.";
    }
    
}
echo json_encode($response);
