<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/01/2018
 * Time: 12:54 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])){
	header("Location: /");
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Garage = new Garage;
$share=new Share;
$detalles = array();
if(isset($_GET["cuenta"]))
{
	$coder->decode($_GET["cuenta"]);
	$cuentaCoded=$_GET["cuenta"];
	$_GET["cuenta"]=$coder->toEncode;
	$cuenta = $_GET["cuenta"];
	$Usuario = new Usuario;
    $nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $agrega = $Usuario -> agregando($nCuenta, $cuenta);
    $imgPerfil = $Usuario->getImgPerfil($_GET["cuenta"]);
    $infoPerfil = $Usuario->getInfoPerfil($_GET["cuenta"]);
    if(empty($infoPerfil)){
    	header("Location: /timeline");
    }
	$detalles = $Garage -> getUserdetail($cuenta);
	if(!empty($_SESSION) && $_SESSION["iduser"]==$_GET["cuenta"])
	{
		$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["usertkn"]));
	}
	else
	{
		$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$cuentaCoded));
	}
	if(!empty($detalles)){
		$metasShare=array(
			"og"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/?cuenta=".$cuentaCoded,
			    "site_name" => "AVI cars",
			    "type" => "website"
			),
			"tw"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
			    "image:alt" => "AVI cars",
			    "card" => "summary_large_image"
			)
		);
	}
}
if(!isset($_SESSION["iduser"]) && $infoPerfil["privacidad"]!=3){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!$owner && ((isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1)==3 && !$following && !$Seguidor->acepted|| $blocked)
{
	$privacidad=$infoPerfil["privacidad"];
	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
}
elseif(!empty($detalles))
{
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$publicacion=new Publicacion;
	$active="profile";
	$Like = new Like;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
	if($owner || $privacidad!=1 || ($following && $Seguidor->acepted))
	{
	?>
	<div class="content" id="posts">
		<h5>Timeline de: <?= $detalles["o_avi_userdetail_name"]?>  <?= $detalles["o_avi_userdetail_last_name"]?></h5>
	</div>
	<script type="text/javascript">
		var lastPost=0;
		var search=true;
		var s="c";
		var u='<?= $cuentaCoded?>';
	</script>
	<script type="text/javascript" src="/js/timeline.js"></script>
	<?php
	}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 ?>
 <?php
 }
 else 
 {
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/perfil/header.php";
 	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 }
 	include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
 ?>
