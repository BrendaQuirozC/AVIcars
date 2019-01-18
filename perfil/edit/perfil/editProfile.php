<?php
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
session_start();
$allowed = array('png', 'jpg','jpeg');
$Usuario = new Usuario;

$avatar =""; 
$cover ="";
$bio = $_POST["biografia"];
$privacy = $_POST["privacy"];

if(isset($privacy) && $privacy=="privado")
{
	$privacy = '1';
}
elseif(isset($privacy) && $privacy=="publico")
{
	$privacy = '2';
}
$extensionAvatar = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
$extensionCover = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
if(!empty($extensionAvatar) && !in_array(strtolower($extensionAvatar), $allowed) ){
	echo 0; //Error de avatar
	exit;
}
if(!empty($extensionCover) && !in_array(strtolower($extensionCover), $allowed) ){
	echo 1; //error Imagen portada no valida"}';
	exit;
}
if(move_uploaded_file($_FILES['avatar']['tmp_name'], ($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/Avatar/'.$_FILES['avatar']['name'])){
	$avatar = '/users/'.$_SESSION["iduser"].'/Avatar/'.$_FILES['avatar']['name'];
}
if(move_uploaded_file($_FILES['portada']['tmp_name'], ($_SERVER['DOCUMENT_ROOT']).'/users/'.$_SESSION["iduser"].'/Cover/'.$_FILES['portada']['name']))
{
	$cover = '/users/'.$_SESSION["iduser"].'/Cover/'.$_FILES['portada']['name'];
}
if($Usuario -> perfilUsuario($_SESSION["iduser"], $avatar, $cover, $bio, $privacy))
{
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
	$publicacion=new Publicacion;
	if($avatar!="")
	{
		$imgAvatar=array();
		$imgAvatar[]=$avatar;
		$url="/perfil/?cuenta=".$_SESSION["iduser"];
		$publicacion->addPublicacion("",6,$_SESSION["iduser"],$imgAvatar, NULL,$url);
	}
	if($cover!="")
	{
		$imgCover=array();
		$imgCover[]=$cover;
		$url="/perfil/?cuenta=".$_SESSION["iduser"];
		$publicacion->addPublicacion("",7,$_SESSION["iduser"],$imgCover,NULL,$url);
	}
	echo 2; //'{"status":"success"}';
}
else
{
	echo 3; //'{"status":"error de carga del perfil"}';
}
exit;
?>