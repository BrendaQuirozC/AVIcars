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
	$active="garage";
	$Like = new Like;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
?>
<div class="content" id="garagesContent">
	<div class="col-xs-12 text-center">
		<?php if($owner){ ?>
		<button class="btn cuenta-btns  text-center " type="button" onclick="create($(this))"> A&ntilde;ade un nuevo garage</button>
		<?php } ?>
	</div>
	<div class=" col-md-12 col-xs-12 garages">
		<div class="title-garage"><?= ($owner) ? "Mis" : "" ?> Garages: <?= ($owner) ? $Garage->getTotalGaragesUser($cuenta) : $Garage->getTotalNoSecretGaragesUser($cuenta) ?></div>
	</div>
	<?php if($_SESSION["iduser"]!=0) { ?>
	<div class=" col-md-12 col-xs-12 garages">
		<div class="title-garage">Colaborando: <?= $Garage->TotalColaboratingGarage($cuenta) ?></div>
	</div>
	<?php } ?>
	
	
</div>
<script type="text/javascript">
	var c='<?= $cuentaCoded ?>'
</script>
<script type="text/javascript" src="/js/garages.js?l=<?= LOADED_VERSION?>"></script>
<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
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
