<?php
/**
 * User: Brenda Quiroz
 * Date: 23/03/2018
 * Time: 03:00 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Follow/Seguidor.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$garage=new Garage;
$usr = new Usuario;
$publicacion=new Publicacion;
$Seguidor=new Seguidor(2);
$Like=new Like;

$database=new Database;
$db=$database->connect();
$fbid= $_POST["id"];
$apodo = $_POST["email"];
$correo= $_POST["email"];
$nombre= $_POST["first_name"];
$apellido= $_POST["last_name"];


if($usr->verifyEmail($correo)){
    $usr -> loginExternal($apodo, $correo, NULL,"FB");
    $response["Success"]=$_SESSION["iduser"];
    
}else{
    $genero= $_POST["gender"];
    switch ($genero) {
        case 'female':
        $genero=1;
        break;
        case 'male':
        $genero=2;
        break;
        default:
        $genero=3;
        break;
    }
    if($lastId = $usr->enviarUser($apodo,null,$correo,"FB",$fbid,1)){
        $usr-> createDir($lastId);
        $dataUser=array("o_avi_userdetail_name"=>$nombre,"o_avi_userdetail_last_name"=>$apellido,"o_avi_userdetail_gender"=>$genero);
        
        $Seguidor->seguir(1,$lastId,1);
        $Like->likeit($lastId,2,1);
        if($usr->enviarUserDetails($lastId, $dataUser)){
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
                            $session =$usr -> loginExternal($apodo, $correo, NULL, "FB");
                            $publicacion->addPublicacion("Bienvenido a ApoyoVial.",8,$lastId);
                            $publicacion->addPublicacion("Este es tu primer Garage! $apodo.",2,$lastId,null,null,$url);
                            $usr->sendConfirmationMail($correo,$nombre, $lastId);
                            $response["Success"]=$_SESSION["iduser"];
                            $response["nuevo"]=true;
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