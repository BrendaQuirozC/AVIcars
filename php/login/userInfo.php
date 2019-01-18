<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 18/01/2018
 * Time: 10:21 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
/*Controlador para el inicio de sesion y obtiene la informacion del usuario por si la quiere cambiar*/
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/login/address.php';
require_once ($_SERVER['DOCUMENT_ROOT']) ."/php/Utilities/coder.php";
session_start();
$id = $_SESSION["iduser"];
$coder = new Coder();
$address = new Address;
$database=new Database;
$db=$database->connect();
$usr = new Usuario;
$nombre = $_POST["changeName"];
$apellidos = $_POST["changeLastName"];
$apodo = $_POST["changeNameUser"];
$correo = $_POST["signUpEmail"];
$telefono= array($_POST["signUpPhone"],$_POST["phonecode"]);
$wacell=(isset($_POST["cellphonewa"])) ? $_POST["cellphonewa"] : 0;
$celular= array($_POST["signUpCellPhone"],$_POST["cellphonecode"],$wacell);
$genero= $_POST["selectGender"];
$zip= $_POST["signUpZipcode"];
$calle= $_POST["signUpStreet"];
$add = $address-> add($zip);
$addjson = json_encode($add);
$verifzip = $address->verifyZip($zip);
$verify = false;
$bio = $_POST["biografia"];
$response=array();
$born=$_POST["anoNac"]."-".$_POST["mesNac"]."-".$_POST["diaNac"];
if($_SESSION["method"]!="WP")
{
    $verify = true;
}
elseif($_SESSION["method"]=="WP" && isset($_POST["pw"]) && $usr->login($_SESSION["user"], null, $_POST["pw"], true))
{
    $verify = true;
}
else
{
    $response["Error"] = "Contraseña incorrecta";
}
if($verify)
{
    if($correo!="")
    {
        if($usr->verifyEmail($correo,$id))
        {
            $response["Error"]="El correo ya existe.";
            $verify = false;
        }
        else
        {
            
            if($usr->isNewMail($id,$correo)){
                if(!$usr->updateEmail($id, $correo))
                {
                    $verify = false;
                    $response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde.";
                }
                else{
                    $usr->uncofirmUser($id);
                    $usr->sendNewMailMail($correo,$nombre." ".$apellidos, $id);
                    $_SESSION["mail"]=$correo;
                    $response["Message"]="Debes confirmar tu nuevo correo, se ha enviado un nuevo correo de confirmaci&oacute;n a $correo";
                }
            }
        }
    }
    if($apodo!="" && $verify)
    {

        if($usr->verifyUserName($apodo, $id))
        {
            $response["Error"]="El usuario ya existe.";
            $verify = false;
        }
        else
        {
            if(!$usr->updateUserName($id, $apodo))
            {
                $verify = false;
                $response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde.";
            }
            else
            {
                $_SESSION["user"]=$apodo;
                $response["Success"]="Informaci&oacute;n Actualizada";
            }
        }
    }
    if($verify)
    {
        if($usr->completarUserDetail($id, $telefono, $celular, $genero,$nombre,$apellidos,$born))
        {
            $response["Success"]="Informaci&oacute;n Actualizada";
        }
        else
        {
            $response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde.";
        }
        if($zip!="")
        {
            if($verifzip==NULL)
            {
                $response["Error"]="No se encontr&oacute; codigo postal";
            }else {
                $usAdres = $usr->verifyAdress($id);
                if ($usAdres==NULL) {
                    if($usr->enviarUserAddress($id, $calle, $zip))
                    {
                        $response["Success"]="Informaci&oacute;n Actualizada";
                    }
                    else
                    {
                        $response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde.";
                    }
                }
                else{
                    if($usr->completarUserAddress($id, $calle, $zip))
                    {
                        $response["Success"]="Informaci&oacute;n Actualizada";
                    }
                    else
                    {
                        $response["Error"]="Ocurrio un error inesperado, intente m&aacute;s tarde.";
                    }
                }
            }
        }
    }
}
if($usr -> perfilUsuario($_SESSION["iduser"], $bio))
{
   $response["Success"]=$_SESSION["usertkn"];
}
else
{
    unset($response["Success"]);
    $response["Error"]="No se pudo guardar la informaci&oacute;n del perfil.";
}
echo json_encode($response);
unset($response);
