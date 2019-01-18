<?php 
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])){
	header("Location: /");
}
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$instance=new Instancia;
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
	$detalles = $Garage -> getUserdetail($cuenta);		
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
if(!isset($_SESSION["iduser"]) && $infoPerfil["privacidad"]==2){
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
	$active="seguidos";
	$Like = new Like;
	$t=1;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
	$Seguidor = new Seguidor;
	$pendingFollowerALl = $Seguidor -> getCountwantFollowBy($cuenta,$t);
	$followersALl = $Seguidor -> getCountFollowers($cuenta,$t);
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
		var tgtFollowing='<?= $cuentaCoded?>';
		var typeFollowing=1;
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
				},
			})
		}
		<?php
		if($owner && $privacidad ==1 )
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
			<?php if($owner && $privacidad ==1 )
			{ ?>
			getPendingFollowers(tgtFollowing);
			<?php } ?>
		});
	</script>
	<script type="text/javascript" src="/js/followers.js?l=<?= LOADED_VERSION?>"></script>
	<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
}
else 
{
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/perfil/header.php"; ?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
} ?>