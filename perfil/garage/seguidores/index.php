<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-07-17 16:32:11
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-17 09:33:57
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["garage"])){
	header("Location: /");
}
require_once($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once ($_SERVER['DOCUMENT_ROOT']) .'/php/login/address.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$garageEncoded=$_GET["garage"];
$coder->decode($_GET["garage"]);
$_GET["garage"]=$coder->toEncode;
$address = new Address;
$Garage = new Garage;
$Usuario = new Usuario;
$detalles = array();
$garageCurr=0;
if(isset($_GET["garage"])){
	$garageCurr=$_GET["garage"];
	$garage=$Garage->accountById($garageCurr);
}
$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$garageCurr));
$show=true;
if(isset($_GET["cuenta"]) && isset($_GET["garage"]))
{
	$cuenta = $_GET["cuenta"];
	$nombreCuenta= $Usuario->getGarage($_GET["garage"]);
	$garage = $Garage ->accountById($_GET["garage"]);
	if(empty($garage)){
		$show=false;
	}
	else{
		$garages = $Garage -> account($garage["user"]);
		$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
		$detalles = $Garage -> getUserdetail($garage["user"]);
		$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
	    if(empty($infoPerfil))
	    {
			$show=false;
		}else{
			if(!empty($detalles))
			{
				$Version = new Version;
				
				$instancia = array();
				$llaveGarage = $_GET["garage"];
				$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
				$privacyGarage = $Garage -> getGarageInfo($_GET["garage"]);
				$active="seguidos";
				$Like = new Like;
				
			}
			else{
				$show=false;
			}
		}	
	}
}else
{
	$show=false;
}
if(isset($garage)){
	if($garage["user"]!=$_GET["cuenta"]){
		$show=false;
	}
}
else{
	$show=false;
}
if(!isset($_SESSION["iduser"]) && $garage["privacidad"]==2){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(isset($garage))
{
	if($owner == $_SESSION["iduser"]){
		$Seguidor = new Seguidor;
	}
	if(!$owner && ((isset($garage["privacidad"])) ? $garage["privacidad"] : 1)==3  && !$following && !$Seguidor->acepted || $blocked){ 
		$show=false;
	}
	else{
		$metasShare=array(
			"og"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
			    "site_name" => "AVI cars",
			    "type" => "website"
			),
			"tw"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
			    "image:alt" => "AVI cars",
			    "card" => "summary_large_image"
			)
		);
	}
}
else
{
	$show=false;
}
if($show)
{ 	
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
	$garageContain = $Garage ->accountInstancia($_GET["garage"]);
	$followTo=$_GET["garage"];
	$t=2;
	$pendingFollowerALl = $Seguidor -> getCountwantFollowBy($_GET["garage"],$t);
	$followersALl = $Seguidor -> getCountFollowers($_GET["garage"],$t);
	if($owner)
	{ ?>
		<div class="solicitudes content">
			<div class="menu2">
				<span>Solicitudes</span> <span class="num"><?=$pendingFollowerALl?> </span>
			</div>
		</div>
	<?php 
	}?>
	<div class="siguiendo active content">
		<div class="menu2">
			<span>Seguidores</span> <span class="num"><?=$followersALl?> </span>
		</div>
	</div>
	<script type="text/javascript">
		var tgtFollowing='<?= $garageEncoded?>';
		var typeFollowing=2;
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
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/perfil/header.php";
		?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 ?>