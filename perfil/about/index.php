<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-04 17:21:08
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-04 14:05:11
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Garage = new Garage;
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
	$detalles = $Usuario -> getUserdetail($cuenta);
	if(!empty($_SESSION) && $_SESSION["iduser"]==$_GET["cuenta"])
	{
		$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
	}
	if(!empty($_SESSION))
	{
		$detallesOwner = $Garage -> getUserdetail($_SESSION["iduser"]);
		$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_GET["cuenta"]));
	}
	else
	{
		$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_GET["cuenta"]));
	}
	if(!empty($detalles)){
		$metasShare=array(
			"og"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
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
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!$owner && ((isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1)==3 && !$following && !$Seguidor->acepted)
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
	$publicaciones=$publicacion->getAllPublicationsByUser($cuenta);
	$active=null;
	$Like = new Like;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
	if($owner || $privacidad!=1 || ($following && $Seguidor->acepted))
	{
	?>

	<div class="content">
		<table class="table">
			<tr>
				<th colspan="2"><h3>Descripci&oacute;n</h3></th>
			</tr>
			<tr>
				<td colspan="2"><?= isset($infoPerfil["bio"]) ? $infoPerfil["bio"]: ""?></td>
			</tr>
			<tr>
				<th colspan="2"><h3>Informaci&oacute;n B&aacute;sica</h3></th>
			</tr>
			<tr>
				<td>Fecha de Nacimiento</td>
				<td><?= isset($detalles["fechaNacimiento"]) ? date("d M, Y",strtotime($detalles["fechaNacimiento"])) : "Sin especificar"?></td>
			</tr>
			<tr>
				<td>Sexo</td>
				<td><?=isset($detalles["genero"]) ? $detalles["genero"]: "Sin especificar"?></td>
			</tr>
			<tr>
				<td>Localidad</td>
				<td><?=isset($detalles["a_avi_useraddress_street"]) ? $detalles["a_avi_useraddress_street"]: ""?> <?= ($detalles["a_avi_useraddress_zip_code"]) ? $detalles["municipio"].", ".$detalles["estado"] : "Sin especificar" ?></td>
			</tr>
			<tr>
				<th colspan="2"><h3>Contacto</h3></th>
			</tr>
			<tr>
				<td>Correo</td>
				<td><?=isset($detalles["o_avi_user_email"]) ? $detalles["o_avi_user_email"]: "Sin especificar"?></td>
			</tr>
			<tr>
				<td>Tel&eacute;fono</td>
				<td><?=isset($detalles["o_avi_userdetail_phone"]) ? $detalles["o_avi_userdetail_phone"]: "Sin especificar"?></td>
			</tr>
		</table>
		<?php if ($detalles["o_avi_userdetail_id_user"] != $_SESSION["iduser"]) { ?>
			<a class="pointer infoReport" data-perfil="<?=$detalles['o_avi_userdetail_id_user']?>"onclick="modalToReport($(this))">Reportar Usuario</a>
		<?php } ?>
		
	</div>
	<?php
	}
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 }
 else {
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/perfil/header.php";
 	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
 }

 ?>
