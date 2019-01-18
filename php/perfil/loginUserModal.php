<?php

/**
 * @Author: erikfer94
 * @Date:   2019-01-03 17:21:48
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2019-01-03 17:25:32
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
    $user=array("Error"=>true);
}
else{
    $detalle=$usr->getUserdetail($_SESSION["iduser"]);
	$nac=date_create($detalle["fechaNacimiento"]);
	$today=date_create("now");
	$diff=date_diff($nac,$today);
	$edad=$diff->format("%y");
	$user=array(
		"mail" => $detalle["o_avi_user_email"],
		"edad" => $edad,
		"cp" => $detalle["a_avi_useraddress_zip_code"],
		"nombre" => $detalle["o_avi_userdetail_name"],
		"apellido" => $detalle["o_avi_userdetail_last_name"],
		"telefono" => $detalle["phone"]
	);

}

echo json_encode($user);