<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-24 16:27:24
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:57:13
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["auto"])){
	header("Location: /");
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/php/login/address.php';
$address=new Address;
$Garage = new Garage;
$detalles = array();
if(!isset($_GET["auto"]))
{
	header("Location: /");
}
$Usuario = new Usuario;
//$nCuenta= $Usuario->getCuenta($cuenta);
$auto=new Auto;
$nombreCuenta= $Usuario->getGarage();
$garageContain= $Garage-> instanciaById($_GET["auto"]);
$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
$cuenta = $garage["user"];
$detalles = $Garage -> getUserdetail($cuenta);
if(empty($garageContain))
{
	header("Location: /");
}
$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"]));
$Like = new Like;
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
$privacidad=($garageContain[0]["privacidad"]) ? $garageContain[0]["privacidad"] :1;
if($garage["user"]!=$_SESSION["iduser"] && $privacidad==3)
{
	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
}
elseif(!empty($detalles))
{
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
	$publicacion=new Publicacion;
	$publicaciones=$publicacion->getAllPublicationsByAuto($_GET["auto"]);
	$notImage=false;
	$imagenes = $Garage ->imagenesGenerales($_GET["auto"]);
	$versionCar=array();
	$versionNum = $garageContain[0]["o_avi_car_version_id"];
	if($garageContain[0]["o_avi_car_version_id"]!="")
	{
		$versionCar = $Version->feature($garageContain[0]["o_avi_car_version_id"]);
	}
	if(empty($imagenes))
	{
		$notImage='/img/noimage.png';
	}
	$adDetailCar=$auto->adCar($_GET["auto"]);
	$zipcodeAddressAd=$address->add($adDetailCar["zipcode"]);
	$currMarca=null;
	$curSubMarca=null;
	$curModelo=null;
	if($garageContain[0]["o_avi_car_version_id"])
	{
		$versionCar = $Version->feature($garageContain[0]["o_avi_car_version_id"]);
		if(!empty($versionCar))
		{

			$autoObj=$versionCar[$garageContain[0]["o_avi_car_version_id"]];
			$currMarca=$autoObj["C_Vehicle_Brand_System_ID"];
			$curSubMarca=$autoObj["C_Vehicle_SubBrand_System_ID"];
			$curModelo=$autoObj["C_Vehicle_Model_System_ID"];
		}
	}
	else
	{
		$currMarca=$garageContain[0]["brand"];
		$curSubMarca=$garageContain[0]["subbrand"];
		$curModelo=$garageContain[0]["model"];
	}
	$marcas=$auto->getMarcas();
	$submarcas=$auto->getSubMarcas($currMarca);
	$modelos=$auto->getModels($currMarca,$curSubMarca);
	if($garageContain[0]["o_avi_car_version_id"]  && $curModelo)
		$versiones=$auto->knowVersion($curModelo);
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/auto/headerAuto.php';
?>
<div class="content" id="posts">
	
</div>
<div id="comprar" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">CONTACTO DEL VENDEDOR</h4>
			</div>
			<div class="modal-body text-center">
				<h5>Tel&eacute;fono: <?= $adDetailCar["phone"] ? $adDetailCar["phone"] : "Sin especificar"?></h5>
				<h5>Correo Electronico: <?= $adDetailCar["email"] ? $adDetailCar["email"] : "Sin especificar"?></h5>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var lastPost=0;
	var search=true;
	var s="a";
	var u=<?= $_GET["auto"]?>;
</script>
<script type="text/javascript" src="/js/timeline.js?l=<?= LOADED_VERSION?>"></script>
 <?php
 }
 else 
 {
 	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
	
 }
 	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 	include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
 ?>
