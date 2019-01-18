<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-15 09:30:54
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:51:24
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(isset($_GET["auto"]))
{
	session_start();
	require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
	require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
	require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
	require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
	$coder = new Coder($_GET["cuenta"]);
	$Version = new Version;
	$Garage = new Garage;
	$Usuario = new Usuario;

    $imgPerfil = $Usuario->getImgPerfil($_GET["cuenta"]);
	$garageContain= $Garage-> instanciaById($_GET["auto"]);
	if(empty($garageContain))
	{
		header("Location: /");
	}
	$versionCar=array();
	$versionNum = $garageContain[0]["o_avi_car_version_id"];
	$nombreGar = $garageContain[0]["o_avi_account_name"];
	if($garageContain[0]["o_avi_car_version_id"]!="")
	{
		$versionCar = $Version->feature($garageContain[0]["o_avi_car_version_id"]);
	}
	$cuenta  = $_GET["cuenta"];
	$detalles = $Garage -> getUserdetail($cuenta);
	//para el arbol del usuario y su cuenta

	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
	$llaveGarage = $garageContain[0]["o_avi_account_id"];
	$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
	$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"]));
	$Like = new Like;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
	// botones de usuario y arbol
	$notImage=false;
	$imagenes = $Garage ->imagenesGenerales($_GET["auto"]);
	//echo "<pre>";
	//print_r($garageContain);
	//echo "</pre>";
	$features=array();
	if(!empty($versionCar))
		$features = json_decode($versionCar[$versionNum]["c_vehicle_versions_extraSpecifications"],true);

	if(empty($imagenes))
	{
		$notImage='/img/noimage.png';
	}
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/headerAuto.php";
?>
<div class="content">
	<div class="row">
	</div>
</div>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
