<?php

/**
 * @Author: erikfer94
 * @Date:   2019-01-03 12:08:47
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2019-01-08 11:54:21
 */
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$usuario=new Usuario;
$user=array();
$login=true;
if($_POST["c"]!="G+"&&$_POST["c"]!="FB"){
	$login=false;
	session_start();
	if(isset($_SESSION["tokenTwitter"])){
		$login=true;
	}
	session_destroy();
}
if($login&&$usuario->verifyEmail($_POST["m"])){
	if($usuario->loginExternal(null,$_POST["m"])){
		$detalle=$usuario->getUserdetail($_SESSION["iduser"]);
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
}
else{
	$user=array("Error"=>true);
}
$_SESSION["method"]=$_POST["c"];
echo json_encode($user);