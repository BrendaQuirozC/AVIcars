<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-07-18 16:13:14
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-17 12:02:07
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["auto"])){
	header("Location: /");
}
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/login/address.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$autoEncoded=$_GET["auto"];
$coder->decode($_GET["auto"]);
$_GET["auto"]=$coder->toEncode;
$address=new Address;
$Garage = new Garage;
$Usuario = new Usuario;
$auto=new Auto;
$detalles = array();
$garageContain= $Garage-> instanciaById($_GET["auto"]);
$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
$cuenta = $garage["user"];
$detalles = $Garage -> getUserdetail($cuenta);
if(!empty($garageContain) && isset($_SESSION["iduser"]))
{
	$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],2);
	$colaboradorCont=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],3);
}
else
{
	$colaborador=null;
	$colaboradorCont=null;
}
if(empty($garageContain))
{
	$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"]));
	$privacidad=3;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php'; ?>
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
	<?php
	exit;
}
$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"]));

$metasShare=array(
	"og"	=>	array(
		"title" => "AVI cars by Infotraffic | Perfil",
	    "description" => "Auto ".$garageContain[0]["i_avi_account_car_alias"]." del Garage ".$garageContain[0]["o_avi_account_name"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
	    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
	    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
	    "site_name" => "AVI cars",
	    "type" => "website"
	),
	"tw"	=>	array(
		"title" => "AVI cars by Infotraffic | Perfil",
	    "description" => "Auto ".$garageContain[0]["i_avi_account_car_alias"]." del Garage ".$garageContain[0]["o_avi_account_name"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
	    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
	    "image:alt" => "AVI cars",
	    "card" => "summary_large_image"
	)
);
if(!isset($_SESSION["iduser"]) && ($garageContain[0]["privacidad"]==2 || $enVenta)){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
$privacidad=($garageContain[0]["privacidad"]) ? $garageContain[0]["privacidad"] : 1;
if($garage["user"]!=$_GET["cuenta"]||$blocked)
{
	$privacidad=3; ?>
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
	<?php
	exit;
}
if($garageContain[0]["privacidad"]==3 && !$following && !$owner)
{ 
	$privacidad=3; ?>
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
	<?php
	exit;
}
if(!empty($detalles))
{
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
	if($owner == $_SESSION["iduser"]){
		$Seguidor = new Seguidor;
	}
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
	$zipcodeAddressAd=null;
	if (isset($adDetailCar["zipcode"]))
	{
		$zipcodeAddressAd=$address->add($adDetailCar["zipcode"]);
	}
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

	$active="seguidos";
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/auto/headerAuto.php';

	$followTo=$_GET["auto"];
	$t=3;
	$pendingFollowerALl = $Seguidor -> getCountwantFollowBy($followTo,$t);
	$followersALl = $Seguidor -> getCountFollowers($followTo,$t);
	if($owner)
	{ ?>
		<div class="solicitudes content">
			<div class="menu2">
				<span>Solicitudes</span> <span class="num"><?=$pendingFollowerALl?> </span>
			</div>
		</div>
		<?php 
	} ?>
	<div class="siguiendo active content">
		<div class="menu2">
			<span>Seguidores</span> <span class="num"><?=$followersALl?> </span>
		</div>
	</div>
	<script type="text/javascript">
		var tgtFollowing='<?= $autoEncoded?>';
		var typeFollowing=3;
		var i=0;
		var p=0;
		function getFollowers(f){
			$.ajax({
				url : "/php/Follow/followers.php",
				data : "tgt="+f+"&c="+i+"&t="+typeFollowing,
				type : "POST",
				success : function(msg){
					i++;
					$(".siguiendo").find(".seemore").remove();
					$(".siguiendo").append(msg);
				}
			})
		}
		<?php
		if($owner)
		{ ?>
		function getPendingFollowers(f){
			$.ajax({
				url : "/php/Follow/pendingFollowers.php",
				data : "tgt="+f+"&c="+p+"&t="+typeFollowing,
				type : "POST",
				success : function(msg){
					p++;
					$(".solicitudes").find(".seemore").remove();
					$(".solicitudes").append(msg);
				}
			})
		}
		<?php } ?>
		$(document).ready(function(){
			getFollowers(tgtFollowing);
			<?php if($owner)
			{ ?>
			getPendingFollowers(tgtFollowing);
			<?php } ?>
		});
	</script>
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
