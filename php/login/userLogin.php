<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/01/2018
 * Time: 08:58 AM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
/*Controlador para verificar usuario y contraseña e iniciar sesion*/
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';

$database=new Database;
$db=$database->connect();
$usr = new Usuario;
if(isset($_POST["username"])){
    $usrname = $_POST["username"];
    $email=NULL;
}else{
    $email=$_POST["mail"];
    $usrname=NULL;
}
$pwd= $_POST["password"];
$session =$usr -> login($usrname, $email, $pwd);
if($session==FALSE)
{
    echo 0;
}
else{
    echo $_SESSION["iduser"];


}


