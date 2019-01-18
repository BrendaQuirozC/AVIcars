<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-02 12:15:47
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-12-18 09:02:00
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["auto"])){
	header("Location: /");
}
if(isset($_GET["auto"]))
{
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
	require_once($_SERVER['DOCUMENT_ROOT']).'/php/login/address.php';
	require_once($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
	require_once($_SERVER["DOCUMENT_ROOT"])."/php/likes/Like.php";
	require_once($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
	require_once($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
	require_once($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
	require_once($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
	$coder = new Coder();
	$cuentaEncoded=$_GET["cuenta"];
	$coder->decode($_GET["cuenta"]);
	$_GET["cuenta"]=$coder->toEncode;
	$autoEncoded=$_GET["auto"];
	$coder->decode($_GET["auto"]);
	$_GET["auto"]=$coder->toEncode;
	$Venta = new Venta;
	$address=new Address;
	$auto=new Auto;
	$Version = new Version;
	$Garage = new Garage;
	$Usuario = new Usuario;
    $imgPerfil = $Usuario->getImgPerfil($_GET["cuenta"]);
	$garageContain= $Garage-> instanciaById($_GET["auto"]);
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
	$descriptionmeta="Auto ".$garageContain[0]["i_avi_account_car_alias"];
	if($garageContain[0]["privacyGarage"]!=3){
		$descriptionmeta.=" del Garage ".$garageContain[0]["o_avi_account_name"].(($garageContain[0]["PrivacyUser"]!=3) ? " de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"] : "");
	}
	$adDetailCar=$auto->adCar($_GET["auto"]);
	$enVenta = isset($adDetailCar["idAnuncio"]);
	if(!$enVenta){
		$metasShare=array(
			"og"	=>	array(
				"title" => "AVI cars by Infotraffic | Auto",
			    "description" => $descriptionmeta,
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/noimage.png"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$cuentaEncoded."&auto=".$autoEncoded,
			    "site_name" => "AVI cars",
			    "type" => "website"
			),
			"tw"	=>	array(
				"title" => "AVI cars by Infotraffic | Auto",
			    "description" => $descriptionmeta,
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/noimage.png"),
			    "image:alt" => "AVI cars",
			    "card" => "summary_large_image"
			)
		);
	}
	else{
		$metasShare=array(
			"og"	=>	array(
				"title" => "AUTO EN VENTA!",
			    "description" => $garageContain[0]["nombreMarca"]." ".$garageContain[0]["nombreSubmarca"]." ".$garageContain[0]["nombreModelo"]." ".$garageContain[0]["nombreVersion"]." ".(($garageContain[0]["currency"]=='EUR') ? '&#128;' : '$')." ".number_format($garageContain[0]["a_avi_sell_detaill_price"], 0, '.', ',')." ".$garageContain[0]["currency"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$cuentaEncoded."&auto=".$autoEncoded,
			    "site_name" => "AVI cars",
			    "type" => "website"
			),
			"tw"	=>	array(
				"title" => "AUTO EN VENTA!",
			    "description" => $garageContain[0]["nombreMarca"]." ".$garageContain[0]["nombreSubmarca"]." ".$garageContain[0]["nombreModelo"]." ".$garageContain[0]["nombreVersion"]." ".(($garageContain[0]["currency"]=='EUR') ? '&#128;' : '$')." ".number_format($garageContain[0]["a_avi_sell_detaill_price"], 0, '.', ',')." ".$garageContain[0]["currency"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
			    "image:alt" => "AVI cars",
			    "card" => "summary_large_image"
			)
		);
	}
	if(!isset($_SESSION["iduser"]) && ($garageContain[0]["privacidad"]!=3||$enVenta)){
		$_SESSION["iduser"]=0;
		$coder->encode(0);
		$_SESSION["usertkn"]=$coder->encoded;
		$_SESSION["loads"]=1;
	}
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
	if(!isset($garage["user"]) || $garage["user"]!=$_GET["cuenta"] ||$blocked || !isset($cuentaEncoded))
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
	if($garageContain[0]["privacidad"]==3 && !$following && !$owner && !$enVenta){ 
		$privacidad=3; ?>
			<div class="row" style="margin: 80px 0px 25px 0px;">
				<h3 class="text-center">
					Esta p&aacute;gina no est&aacute; disponible
				</h3>
			</div>
		<?php
		exit;
	}
	$notImage=false;
	$notCover=false;
	$imagenes = $Garage ->imagenesGenerales($_GET["auto"]);
	$coverAuto =$Garage-> imagenPortada($_GET["auto"]);
	$features=array();
	if(!empty($versionCar))
		$features = json_decode($versionCar[$versionNum]["c_vehicle_versions_extraSpecifications"],true);

	if(empty($imagenes))
	{
		$notImage='/img/noimage.png';
	}
	if(empty($coverAuto))
	{
		$notCover='/img/noimage.png';
	}
	
	if(!empty($adDetailCar))
	{
		$zipcodeAddressAd=$address->add($adDetailCar["zipcode"]);
		$metpagos=json_decode($adDetailCar["metodoPago"],true);
		$debTransfer = $metpagos["debTransfer"];
		$credit = $metpagos["credit"];
		$bankCredit = $metpagos["bankCredit"];
		$carfinance = $metpagos["carfinance"];
		$changeHighPrice = $metpagos["changeHighPrice"];
		$changeLowPrice = $metpagos["changeLowPrice"];
		$leasing = $metpagos["leasing"];
	}else{
		$metpagos=array();
	}

	$currMarca=null;
	$curSubMarca=null;
	$curModelo=null;
	$claseCar=null;
	$fuel=null;
	$doors=null;
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
	$extras=json_decode($garageContain[0]["extras"],true);
	$fuel=$garageContain[0]["fuel"];
	$doors=$garageContain[0]["doors"];
	$ventanas=$garageContain[0]["ventanas"];
	$interior=$garageContain[0]["interior"];
	$km = $garageContain[0]["o_avi_car_km"];
	$potencia = $garageContain[0]["potencia"];
	$hologram = $garageContain[0]["hologram"];
	$duenos = $garageContain[0]["dueno"];
	$facturaEmpresa = $garageContain[0]["fempresa"];
	$facturaLote = $garageContain[0]["flote"];
	$facturaAseguradora = $garageContain[0]["faseguradora"];
	$facturaPfisica = $garageContain[0]["fpersonafisica"];
	$motores = $auto->getEngineType();
	$catalogoFuel = $auto->getTypeFuel();
	$catalogoTrans = $auto->getTypeTrans();
	$colores = $Venta->colorCar();
	$clases = $auto->getClass();
	$claseCar=$garageContain[0]["clase"];
	$marcas=$auto->getMarcas();
	$submarcas=$auto->getSubMarcas($currMarca);
	$modelos=$auto->getModels($currMarca,$curSubMarca);

	if($garageContain[0]["o_avi_car_version_id"]  && $curModelo)
		$versiones=$auto->knowVersion($curModelo);
	$active="timeline";
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/headerAuto.php";
	}
	?>

<div class="min-content m-space">
	<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'row' : ''?> rowChars">
		<?php if($enVenta==true && $conPrecio > 0)
		{ ?>
			<div class="eachCarRow buy-characteristics col-sm-6 col-md-6 col-lg-6 col-xs-12">
				<h5 class="pointer">
					<a class="text-ad-yellow" data-toggle="modal" data-target="<?= isset($_SESSION["iduser"]) && $_SESSION["iduser"]!=0 ? '#comprar' : '#confirmLoginModal'?>">
						CONTACTAR AL&nbsp;VENDEDOR
						<img class="icon-size-menu" src="/img/icons/info_icon_yellow.png">
					</a>
				</h5>
				<p class="<?= isset($adDetailCar["texto"]) ? 'fitAdText' : "" ?>"><?= isset($adDetailCar["texto"]) ? $adDetailCar["texto"] : "" ?></p>
				<?php if(isset($_SESSION["iduser"]) && $_SESSION["iduser"]!=0)
				{ ?>
					<ul class="contacto-icons">
						<?php
						if($adDetailCar["phone"] && !$adDetailCar["phone2"] && !$adDetailCar["phone3"])
						{ ?>
							<li id="call1" onclick="window.open('tel://<?=$adDetailCar["phone"]?>')">
								<img title="LLamar" class="ph" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_Telephone_infotraffic.png">
							</li>
							<?php 
						} elseif(!$adDetailCar["phone"] && $adDetailCar["phone2"] && !$adDetailCar["phone3"]){ ?>
							<li id="call2" onclick="window.open('tel://<?=$adDetailCar["phone2"]?>')">
								<img title="LLamar" class="ph" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_Telephone_infotraffic.png">
							</li>
							<?php 
						} elseif(!$adDetailCar["phone"] && !$adDetailCar["phone2"] && $adDetailCar["phone3"]){ ?>
							<li id="call3" onclick="window.open('tel://<?=$adDetailCar["phone3"]?>')">
								<img title="LLamar" class="ph" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_Telephone_infotraffic.png">
							</li>
							<?php 
						} elseif(($adDetailCar["phone"] && $adDetailCar["phone2"]) || ($adDetailCar["phone"] && $adDetailCar["phone3"]) || ($adDetailCar["phone2"] && $adDetailCar["phone3"])){ ?>
							<li id="call4" id="contactCall" data-toggle="modal" data-target="<?= isset($_SESSION["iduser"]) && $_SESSION["iduser"]!=0 ? '#callContact' : '#confirmLoginModal'?>" >
								<img title="LLamar" class="ph" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_Telephone_infotraffic.png">
							</li>
							<?php 
						} 
						if($adDetailCar["phonewa"]==1 && !$adDetailCar["phone2wa"]==1 && !$adDetailCar["phone3wa"]==1)
						{ 
							$code=($adDetailCar["locationphone"]=="MX") ? "1" : "" ;?>
							<li onclick="<?= ($adDetailCar["phonewa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode"].$code.$adDetailCar["phone"]."','_blank')" : "" ?>">
								<img title="Enviar Whatsapp" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp-VERDE_infotraffic.png">
							</li>
							<?php 
						}
						elseif(!$adDetailCar["phonewa"]==1 && $adDetailCar["phone2wa"]==1 && !$adDetailCar["phone3wa"]==1)
						{ 
							$code=($adDetailCar["locationphone2"]=="MX") ? "1" : "" ; ?>
							<li onclick="<?= ($adDetailCar["phone2wa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode2"].$code.$adDetailCar["phone2"]."','_blank')" : "" ?>">
								<img title="Enviar Whatsapp" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp-VERDE_infotraffic.png">
							</li>
							<?php 
						} elseif(!$adDetailCar["phonewa"]==1 && !$adDetailCar["phone2wa"]==1 && $adDetailCar["phone3wa"]==1)
						{ 
							$code=($adDetailCar["locationphone3"]=="MX") ? "1" : "" ;  ?>
							<li onclick="<?= ($adDetailCar["phone3wa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode3"].$code.$adDetailCar["phone3"]."','_blank')" : "" ?>">
								<img title="Enviar Whatsapp" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp-VERDE_infotraffic.png">
							</li>
							<?php 
						}
						elseif(($adDetailCar["phonewa"]==1 && $adDetailCar["phone2wa"]==1) || ($adDetailCar["phone3wa"]==1) && $adDetailCar["phonewa"]==1 || ($adDetailCar["phonewa"]==1 && $adDetailCar["phone3wa"]==1))
						{ ?>
							<li data-toggle="modal" data-target="<?= isset($_SESSION["iduser"]) && $_SESSION["iduser"]!=0 ? '#whatsContact' : '#confirmLoginModal'?>">
								<img title="Enviar Whatsapp" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp-VERDE_infotraffic.png">
							</li>
							<?php 
						} 

						if($adDetailCar["email"] && !$adDetailCar["email2"]){ ?>
							<li class="mail" onclick="location.href = 'mailto:<?= $adDetailCar["email"]?>?Subject=Hola%20me%20interesó%20tu%20anuncio%20de%20AVICars'" target="_top">
								<img title="Mandar correo" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_eMail_infotraffic.png">
							</li>
							<?php
						}
						elseif(!$adDetailCar["email"] && $adDetailCar["email2"]){ ?>
							<li class="mail" onclick="location.href = 'mailto:<?= $adDetailCar["email2"]?>?Subject=Hola%20me%20interesó%20tu%20anuncio%20de%20AVICars'" target="_top">
								<img title="Mandar correo" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_eMail_infotraffic.png">
							</li>
							<?php
						}
						elseif($adDetailCar["email"] || $adDetailCar["email2"]){ ?>
							<li data-toggle="modal" data-target="<?= isset($_SESSION["iduser"]) && $_SESSION["iduser"]!=0 ? '#mailContact' : '#confirmLoginModal'?>">
								<img title="Mandar correo" class="ml" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_eMail_infotraffic.png">
							</li>
							<?php
						}

						if(!$owner && $enVenta && $conPrecio > 0 && $garageContain[0]["status_sell"]!=2 && $_SESSION["iduser"]!=0)
						{ ?>
							<li>
								<?php $idUserSession=$_SESSION["iduser"];
							 	$coderAd = new Coder($adDetailCar["idAnuncio"]);
								$numLikes = $Like->countLikes(5, $adDetailCar["idAnuncio"]);
								if(!$Like->alreadyLike($idUserSession, 5, $adDetailCar["idAnuncio"]))
								{ ?>
									<img title="Me Interesa" id="wheelAd" class="fire" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off_small.png" onclick="like($(this), '<?= $coderAd->encoded?>', 5)">
								<?php } 
					    		else
					    		{ ?>
					    			<img title="Ya no Interesa" id="wheelAd" class="fire2" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" onclick="unlike($(this), '<?= $coderAd->encoded ?>', 5)">
					    		<?php 
							    } ?>
							</li>
							<?php 
						} ?>
					</ul>
					<?php 
				} ?>
			</div>
			<?php 
		}?>
		<div class="eachCarRow CarSRow <?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6 col-xs-12' : ''?>">
			<div class="clearfix top-characteristics">
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
					<?php 
					if($claseCar){
						foreach ($clases as $cl => $clase){ 
							if($claseCar==$cl)
							{ 
								?>
								<p class='detailstab-chars'>
									<img class="char-icon-car" src='<?=($enVenta)==true && $conPrecio > 0 ? $clase["outline"] : $clase["outline"]?>' alt="clase">&emsp;<?= $clase["description"]?>
								</p>
								<?php
							} 
						}
					}
					else
					{ ?>
						<p class='detailstab-chars'>
							<img class="char-icon-car" src='<?=($enVenta)==true && $conPrecio > 0 ? "/img/auto-icons/black_monovolumen.png" : "/img/auto-icons/black_monovolumen.png"?>'>&emsp;No indica
						</p>
					<?php } ?>
				</div>
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
					<?php if($doors) 
					{ ?>
						<p class="detailstab-chars">
							<img class="char-icon" src="/img/auto-icons/black_door.png">&emsp;<?=$doors?> Puertas
						</p>
					<?php }else{ ?>
						<p class='detailstab-chars'>
							<img class="char-icon" src="/img/auto-icons/black_door.png">&emsp;No indica
						</p>
					<?php }?>
				</div>
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
					<?php 
					if($fuel){
						foreach ($catalogoFuel as $f => $fl) {
							if($f==$fuel){ ?>
								<p class='detailstab-chars'>
									<img class="char-icon" src="/img/auto-icons/black_gasoline.png">&emsp;<?= $fl?>
								</p>
							<?php }
						}
					}
					else
					{ ?>
						<p class='detailstab-chars'>
							<img class="char-icon" src="/img/auto-icons/black_gasoline.png">&emsp;No indica
						</p>
					<?php } ?>
				</div>
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
					<?php if($interior) 
					{ ?>
						<p class="detailstab-chars">
							<img class="char-icon" src="/img/auto-icons/black_car_seat.png">
							&emsp;<?=$interior?></p>
					<?php }else{ ?>
						<p class='detailstab-chars'>
							<img class="char-icon" src="/img/auto-icons/black_car_seat.png">
							&emsp;No indica
						</p>
					<?php }?>
				</div>
			</div>
			<div class="clearfix bottom-characteristics">
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
					<?php
					if($garageContain[0]["trans"])
					{
						foreach ($catalogoTrans as $ct => $trans) {
							if($garageContain[0]["trans"]==$ct)
							{
							?>
							<p class='detailstab-chars'>
								<img class="char-icon" src="<?= $trans["nombre"]=='Manual' ? '/img/auto-icons/tmanual_b.png' :  '/img/auto-icons/tautomatica_b.png'?>">
								&emsp;<?=$trans["nombre"]?>
							</p>
							<?php
							}	
						} 
					}
					else
					{ ?>
						<p class='detailstab-chars'>
							<img class="char-icon" src='/img/auto-icons/tautomatica_b.png'>
							&emsp;No indica
						</p>
					<?php } ?>
				</div>
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
					<p class="detailstab-chars">
						<?php 
						if($garageContain[0]["engineType"]){ 
							foreach ($motores as $mt => $motor)
							{
								if($garageContain[0]["engineType"]==$mt)
								{ ?>
									<img class="char-icon" src="/img/auto-icons/black_motor.png">
									&emsp;<?= $motor?>
								<?php } 
							}
						}
						else
						{ ?>
							<img class="char-icon" src="/img/auto-icons/black_motor.png">
							&emsp;No indica
						<?php } ?>
					</p>
				</div>
				<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-12 control-label pointer vermas-h" data-toggle="modal" data-target="#auto_vermas">
					<p class="detailstab-chars">
						&plus;&emsp;Ver m&aacute;s
					</p>
				</div>
			</div>
		</div>
	</div>
	<?php 
	if(!$notImage)
	{ ?>
		<div id="promosCarrusel" class="carousel carousel-cars" data-interval="false">
			<div class="carousel-inner carousel-inner-cars" role="listbox">
				<?php 
				if(!$notImage)
				{
		    		foreach ($imagenes as $keyimg => $imageBase) 
		    		{
		    			if($keyimg == 0)
		    			{ ?>
							<div id="vehiclePic<?=$keyimg?>" class="imgCar item active" onclick="openSlide();currSlide(<?=$keyimg?>)"><img class="img_01 imgCar item active" src="<?=$imageBase['a_avi_car_img_car']?>">
							</div>
							<?php
				  		}
				  		else
				  		{ ?>
				  			<div id="vehiclePic<?=$keyimg?>" class="imgCar item" onclick="openSlide();currSlide(<?=$keyimg?>)"><img class="img_01 imgCar item" src="<?=$imageBase['a_avi_car_img_car']?>">
							</div>
				  			<?php
				  		}
			  		}
		  		}
		  		else
		  		{ ?>
					<div class="item active" style="background-image: url('<?=$notImage?>');">
					</div>
		  		<?php }?>
			</div>
		</div> 
		<div class="previewImg" id="slider-thumbs">
	        <?php $imagesCount = count($imagenes); ?>
	        <ul  id="ul-previewImg">
	            <?php foreach ($imagenes as $keyimg => $imageBase) 
		    		{ ?>
	            <li class="<?= ($imagesCount < 7) ? 'autoSpace' : 'calcSpace' ?>" id="carousel-selector-<?=$keyimg?>" style='background-image: url("<?=$imageBase["a_avi_car_img_car"]?>");'>
	            </li>
	            <?php } ?>
	        </ul>                 
		</div>
		<div class="backImgSlide">
			<button type="button" class="glyphicon glyphicon-chevron-left" onclick="leftSlide()"></button>
		</div>
		<div class="ImgSlide" >
			<button type="button" class="glyphicon glyphicon-chevron-right" onclick="rightSlide()"></button>
		</div>
		<?php 
	} ?>
	<h2 class="about">Sobre el Auto</h2>
	<div class="table-sC rel-position table-responsive">
		<table class="characteristics">
			<tr>
				<td class="car-name">
					<b class="aliasb">Alias:</b> 
					<b><?= $garageContain[0]["i_avi_account_car_alias"]?></b>
					<?php if(!$owner && $garageContain[0]["verificado"] == 1){ ?><span>
						<?= $garageContain[0]["verificado"] ? "<a href='#' data-toggle='modal' data-target='#no_owner_certified_auto'><img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'></a>" : "" ?>
					</span><?php } ?>
					<?php if($owner && $garageContain[0]["verificado"] == 1){ ?><span>
						<?= $garageContain[0]["verificado"] ? "<a href='#' data-toggle='modal' data-target='#owner_certified_auto'><img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?>
					</span><?php } ?>
				</td>
				<td>
					<p class="stated detailstab-pad">
						<?php 
						if($garageContain[0]["i_avi_account_car_state"]==1)
						{ ?>
							<b>En impecable estado</b>
							<?php 
							for ($i=1; $i <= 5 ; $i++) 
							{ ?>
								<img src="/img/auto-icons/black_star.png">
								<?php 
							}
						} 
						elseif ($garageContain[0]["i_avi_account_car_state"]==2)
						{ ?>
							<b>En buen estado</b>
							<?php 
							for ($i=1; $i <= 4 ; $i++) 
							{ ?>
								<img src="/img/auto-icons/black_star.png">
								<?php 
							} ?>
							<img src="/img/auto-icons/black_star_outline.png">
							<?php
						}
						elseif ($garageContain[0]["i_avi_account_car_state"]==3)
						{ ?>
							<b>En estado regular</b>
							<?php 
							for ($i=1; $i <= 3 ; $i++) 
							{ ?>
								<img src="/img/auto-icons/black_star.png">
								<?php 
							} 
							for ($i=1; $i <= 2 ; $i++) 
							{ ?>
							<img src="/img/auto-icons/black_star_outline.png">
							<?php }
						}
						elseif ($garageContain[0]["i_avi_account_car_state"]==4)
						{ ?>
							<b>En mal estado</b>
							<?php 
							for ($i=1; $i <= 2 ; $i++) 
							{ ?>
								<img src="/img/auto-icons/black_star.png">
								<?php 
							} 
							for ($i=1; $i <= 3 ; $i++) 
							{ ?>
							<img src="/img/auto-icons/black_star_outline.png">
							<?php }
						}
						elseif ($garageContain[0]["i_avi_account_car_state"]==5)
						{ ?>
							<b>En estado accidentado</b>
							<?php ?>
								<img src="/img/auto-icons/black_star.png">
								<?php
							for ($i=1; $i <= 4 ; $i++) 
							{ ?>
							<img src="/img/auto-icons/black_star_outline.png">
							<?php }
						}
						elseif ($garageContain[0]["i_avi_account_car_state"]==6)
						{ ?>
							<b>Estado: no camina</b>
							<?php 
							for ($i=1; $i <= 5 ; $i++) 
							{ ?>
							<img src="/img/auto-icons/black_star_outline.png">
							<?php }
						} 
						else
						{ ?>
							<b>Estado:</b><b> No indica</b>
						<?php }?>
					</p>
				</td>
			</tr>
			<tr>
				<td>
					<?php if($km) 
					{ ?>
						<p class="detailstab-pad">
							<?php 
							$chars = strlen($km);
							$restingChars = (6 - $chars);
							if($restingChars > 0){
								for($emptyBox = 1; $emptyBox<= $restingChars; $emptyBox++ )
								{ ?>
								    <b class="km-format">0 </b>
								<?php 
								}
							}
							for($char = 0; $char< $chars; $char++ )
							{ ?>
							    <b class="km-format"> <?=$km[$char]?> </b>
							<?php 
							} ?> KM
						</p>
						<?php 
					}
					else
					{ 
						for($emptyBox = 1; $emptyBox<= 6; $emptyBox++ )
						{ ?>
						    <b class="km-format">0 </b>
						<?php 
						} ?> KM
					<?php } ?>
				</td>
				<td>
					<?php if($garageContain[0]["o_avi_car_color"]){ 
						foreach ($colores as $colorkey => $color) { 
							if($garageContain[0]["o_avi_car_color"]==$colorkey){
							?>
							<p class='detailstab-pad'>
								<img class=' mini-icons' src="<?=$color["img"]?>">
								&ensp;Color:&emsp;<?=$color["nombre"]?>
							</p>
						<?php }
						}
					}
					else
					{ ?>
						<p class='detailstab-pad'>
							Color: No indica
						</p>
					<?php } ?>
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="content" id="posts">
	<h5>Timeline del Auto: <?= $garageContain[0]["i_avi_account_car_alias"]?></h5>
</div>
<div id="equipamientoInterior" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Equipamiento Interior</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<?php
						//print_r($features["Motor"]);
						if(isset($features["Equipamiento"]["Interior"]))
						{
							echo "<table class='table'>";
							echo $Version->getFullCaratersiticas($features["Equipamiento"]["Interior"], "", 0, 0);
							echo "</table>";
						}
						else
						{
							echo "Sin especificar";
						}
						?>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="equipamientoExterior" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Equipamiento Exterior</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<?php
						//print_r($features["Motor"]);
						if(isset($features["Equipamiento"]["Exterior"]))
						{
							echo "<table class='table'>";
							echo $Version->getFullCaratersiticas($features["Equipamiento"]["Exterior"], "", 0, 0);
							echo "</table>";
						}
						else
						{
							echo "Sin especificar";
						}
						?>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="garantias" class="modal fade garantias-z" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Garant&iacute;as</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<table class='table'>
						<tr>
							<td>F&aacute;brica:&ensp;</td>
							<td>
								<?php
								if($extras["garantia"]["fabrica"])
								{  
									echo $extras["garantia"]["fabrica"];
								} else { ?>
									Sin especificar
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>Verndedor:&ensp;</td>
							<td>
								<?php
								if($extras["garantia"]["vendedor"])
								{  
									echo $extras["garantia"]["vendedor"];
								} else { ?>
									Sin especificar
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>Usuario:&ensp;</td>
							<td>
								<?php
								if($extras["garantia"]["usuario"])
								{  
									echo $extras["garantia"]["usuario"];
								} else { ?>
									Sin especificar
								<?php } ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="seguridad" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Seguridad</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<?php
						//print_r($features["Motor"]);
						if(isset($features["Seguridad"]) && !empty($features["Seguridad"]))
						{
							echo "<table class='table'>";
							echo $Version->getFullCaratersiticas($features["Seguridad"], "", 0, 0);
							echo "</table>";
						}
						else
						{
							echo "Sin especificar";
						}
						?>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="trendireccion" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Dirección</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<?php
						if(isset($features["Tren Motriz"]["Dirección"]))
						{
							echo "<table class='table'>";
							echo $Version->getFullCaratersiticas($features["Tren Motriz"]["Dirección"], "", 0, 0);
							echo "</table>";
						}
						else
						{
							echo "Sin especificar";
						}
					?>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="trenfrenos" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Frenos</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<?php
						if(isset($features["Tren Motriz"]["Frenos"]))
						{
							echo "<table class='table'>";
							echo $Version->getFullCaratersiticas($features["Tren Motriz"]["Frenos"], "", 0, 0);
							echo "</table>";
						}
						else
						{
							echo "Sin especificar";
						}
					?>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="desempeno" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Desempeño</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<?php
						if(isset($features["Desempeño"]))
						{
							echo "<table class='table'>";
							echo $Version->getFullCaratersiticas($features["Desempeño"], "", 0, 0);
							echo "</table>";
						}
						else
						{
							echo "Sin especificar";
						}
					?>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="auto_vermas" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">M&aacute;s Carater&iacute;sticas</h4>
			</div>
			<div class="modal-body row">
				<div class="table-sC">
					<table class="moreChars">
						<tr>
							<td>Ventanas</td>
							<td>
								<?php if($ventanas) 
								{ ?>
									<p class="detailstab-color-gray detailstab-pad"><?=$ventanas?></p>
								<?php }else{ ?>
									<p class='detailstab-color-gray detailstab-pad'>Sin Especificar</p>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>Potencia</td>
							<td>
								<?php if($potencia) 
								{ ?>
									<p class="detailstab-color-gray detailstab-pad">
										<?=$potencia?>&ensp;Hp</p>
								<?php }else{ ?>
									<p class='detailstab-color-gray detailstab-pad'>Sin Especificar</p>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>N&uacute;m de Pasajeros</td>
							<td>
								<p class="detailstab-color-gray detailstab-pad"><?=$extras["interiores"]["num pasajeros"]?></p>
							</td>
						</tr>
						<tr>
							<td>N&uacute;m de Filas de Asientos</td>
							<td>
								<p class="detailstab-color-gray detailstab-pad"><?=$extras["interiores"]["filas asientos"]?></p>
							</td>
						</tr>
						<tr>
							<td>N&uacute;m de&ensp;Due&ntilde;os Anteriores</td>
							<td>
								<?php if($duenos) 
								{ ?>
								  <p class="detailstab-color-gray detailstab-pad"><?=$duenos?></p>
								<?php }else{ ?>
									<p class='detailstab-color-gray detailstab-pad'>Sin Especificar</p>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>Factura a nombre de</td>
							<td>
								<p class='p-title-detail'>&ensp;</p>
								<?php 
								if($facturaEmpresa=="1") { ?>
									<p class="detailstab-color-gray detailstab-pad ">Empresa</p>
								<?php }
								if($facturaLote=="1") { ?>
									<p class="detailstab-color-gray detailstab-pad">Lote</p>
								<?php }
								if($facturaAseguradora=="1") { ?>
									<p class="detailstab-color-gray detailstab-pad">Aseguradora</p>
								<?php }
								if($facturaPfisica=="1") { ?>
									<p class="detailstab-color-gray detailstab-pad">Due&ntilde;o propio</p>
								<?php }
								if ($facturaEmpresa=="0" && $facturaLote=="0" && $facturaAseguradora=="0" && $facturaPfisica=="0") { ?>
									<p class='detailstab-color-gray detailstab-pad'>Sin Especificar</p>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>Holograma</td>
							<td>
								<?php if($hologram) 
								{ ?>
									<p class="detailstab-color-gray detailstab-pad"><?=$hologram?></p>
								<?php }else{ ?>
									<p class='detailstab-color-gray detailstab-pad'>Sin Especificar</p>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>Garant&iacute;a(s)</td>
							<td>
								<?php if($extras["garantia"]["fabrica"] || $extras["garantia"]["vendedor"] || $extras["garantia"]["usuario"]) 
								{ ?>
									 <p class="detailstab-color-gray detailstab-pad"><label data-toggle="modal" data-target="#garantias" class="control-label btn-conditions pointer"> VER M&Aacute;S</label></p>
								<?php }else{ ?>
									<p class='detailstab-color-gray detailstab-pad'>Sin Especificar</p>
								<?php }?>
							</td>
						</tr>
						<tr>
							<td>Piezas faltantes</td>
							<td>
								<p class="detailstab-color-gray detailstab-pad"><?=(isset($extras["piesas"]) && $extras["piesas"] != '') ? implode("<br>", $extras["piesas"]) : 'N/A'?></p>
							</td>	
						</tr>
						<tr>
							<td>Fallas menores</td>
							<td>
								<p class="detailstab-color-gray detailstab-pad"><?= (isset($extras["fallasmenores"]) && $extras["fallasmenores"] != '') ? implode("<br>", $extras["fallasmenores"]) : 'N/A'?></p>
							</td>
						</tr>
						<tr>
							<td>Fallas mayores</td>
							<td>
								<p class="detailstab-color-gray detailstab-pad"><?=(isset($extras["piesas"]) && $extras["piesas"] != '') ? implode("<br>", $extras["fallasmayores"]) : 'N/A'?></p>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="exteriores" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Exteriores</h4>
			</div>
			<div class="modal-body row text-center">
				<div class="table-sC">
					<table>
						<tr>
							<td>Distancia entre Ejes</td>
							<td>	
								<?= $extras["Exteriores"]["Distancia entre Ejes"] ?>			
							</td>
						</tr>
						<tr class="top-gray">
							<th colspan="2">
								Ancho entre V&iacute;as
							</th>
						</tr>
						<tr align="right">
							<td>
								Delanteras
							</td>
							<td>
								<?= $extras["Exteriores"]["Ancho entre Vías Delanteras"] ?>
							</td>
						</tr>
						<tr align="right">
							<td>
								Traseras
							</td>
							<td>
								<?= $extras["Exteriores"]["Ancho entre Vías Traseras"] ?>
							</td>
						</tr>
						<tr class="top-gray">
							<td>
								Altura Total
							</td>
							<td>
								<?= $extras["Exteriores"]["Altura Total"] ?>
							</td>
						</tr>
						<tr>
							<td>Distancia al piso</td>
							<td>
								<?= $extras["Exteriores"]["Distancia al piso"] ?>
							</td>
						</tr>
						<tr>
							<td>
								&Aacute;ngulo M&aacute;ximo de Ataque
							</td>
							<td>
								<?= $extras["Exteriores"]["Angulo max de ataque"] ?>
							</td>
						</tr>
						<tr>
							<td>
								Circunferencua M&iacute;nima Giro
							</td>
							<td>
								<?= $extras["Exteriores"]["Circunferencia de Giro"] ?>
							</td>
						</tr>
						<tr>
							<td>
								Distribuci&oacute;n del peso en Eje Delantero
							</td>
							<td>
								<?= $extras["Exteriores"]["Peso Eje Delatero"] ?>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="comprar" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<h4 class="text-center">CONTACTA CON EL VENDEDOR</h4>
				<?php if($adDetailCar["phone"]){ 
					$code=($adDetailCar["locationphone"]=="MX") ? "1" : "" ; ?>
					<div class="contacto <?= ($adDetailCar["phonewa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phonewa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode"].$code.$adDetailCar["phone"]."','_blank')" : "" ?>">
						<?=$adDetailCar["phone"]?>
					</div>
				<?php } if($adDetailCar["phone2"]){ 
					$code=($adDetailCar["locationphone2"]=="MX") ? "1" : "" ; ?>
					<div class="contacto <?= ($adDetailCar["phone2wa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phone2wa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode2"].$code.$adDetailCar["phone2"]."','_blank')" : "" ?>">
						<?=$adDetailCar["phone2"]?>
					</div>
				<?php } if($adDetailCar["phone3"]){ 
					$code=($adDetailCar["locationphone3"]=="MX") ? "1" : "" ; ?>
					<div class="contacto <?= ($adDetailCar["phone3wa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phone3wa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode3"].$code.$adDetailCar["phone3"]."','_blank')" : "" ?>">
						<?=$adDetailCar["phone3"]?>
					</div>
				<?php } if($adDetailCar["email"]) { ?>
					<div class="contacto mail">
						<a href="mailto:<?= $adDetailCar["email"]?>?Subject=Hola%20me%20interesó%20tu%20anuncio%20de%20AVICars" target="_top"><?= $adDetailCar["email"]?></a>
					</div>
				<?php } if($adDetailCar["email2"]) { ?>
					<div class="contacto mail">
						<a href="mailto:<?= $adDetailCar["email2"]?>?Subject=Hola%20me%20interesó%20tu%20anuncio%20de%20AVICars" target="_top"><?= $adDetailCar["email2"]?></a>
					</div>
				<?php } ?>
				<h4 class="text-center">FORMAS DE PAGO</h4> 
				<?php if(isset($debTransfer) && $debTransfer == 1) { ?>
					<p><img class="payment-check" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/check_mark.png">&nbsp;&nbsp;D&Eacute;BITO O TRANSFERENCIA</p>
				<?php } 
				if( (isset($credit) && $credit == 1) || (isset($bankCredit) && $bankCredit == 1)) { ?>
					<p><img class="payment-check" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/check_mark.png">&nbsp;&nbsp;CR&Eacute;DITO/CR&Eacute;DITO BANCARIO</p>
				<?php }
				if(isset($carfinance) && $carfinance == 1) { ?>
				 	<p><img class="payment-check" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/check_mark.png">&nbsp;&nbsp;AUTOFINANCIAMIENTO</p>
				<?php } ?>
				<?php 
				if(isset($changeHighPrice) && $changeHighPrice == 1) 
				{ ?>
				 	<p><img class="payment-check" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/check_mark.png">&nbsp;&nbsp;CAMBIO POR AUTO DE MENOR PRECIO</p>
				<?php }
				if(isset($changeLowPrice) && $changeLowPrice == 1) { ?>
					<p><img class="payment-check" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/check_mark.png">&nbsp;&nbsp;CAMBIO POR AUTO DE MAYOR PRECIO</p>
				<?php }
				if(isset($leasing) && $leasing == 1) { ?>
				 	<p><img class="payment-check" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/check_mark.png">&nbsp;&nbsp;ARRENDAMIENTO</p>
				<?php } ?>
				<?php if((!isset($debTransfer) || $debTransfer != 1) && (!isset($credit) || $credit != 1) && (!isset($bankCredit) || $bankCredit != 1) && (!isset($carfinance) || $carfinance != 1) && (!isset($changeHighPrice) || $changeHighPrice != 1) && (!isset($changeLowPrice) || $changeLowPrice != 1) && (!isset($leasing) || $leasing != 1))
				{ ?>
					<p>Sin Especificar</p>
				<?php } ?>
				<p class="linkInteresa">
					<a href="https://apoyovial.net/2018/09/26/tips-para-una-cita-segura/" target="_blank">
						<img src="/img/icons/mail/comprasegura.png">&nbsp;&nbsp;Tips para una cita de compra segura.
					</a>
					<?php
					if(!$owner && $enVenta && $conPrecio > 0 && $garageContain[0]["status_sell"]!=2 )
					{
						if(!$Like->alreadyLike($idUserSession, 5, $adDetailCar["idAnuncio"]))
						{ ?>
				    		<button class="h-adSale pointer" onclick="like($(this), '<?= $coderAd->encoded?>', 5)">
				    			Me Interesa el anuncio
				    		</button>
			    		<?php } 
			    		else
			    		{ ?>
				    		<button class="h-adSale pointer" onclick="unlike($(this), '<?= $coderAd->encoded ?>', 5)">
				    			Ya no me interesa el anuncio
				    		</button>
				    		<?php 
			    		}
			    	} ?>
				</p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div class='modal fade' id='confirmLoginModal' role='dialog'> 
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Inicia Sesi&oacute;n</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<p class="col-xs-12">Para poder contactar al vendedor por favor inicia sesi&oacute;n o crea una cuenta nueva.</p>
				</div>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal" id="closeModalMail">Cerrar</button>
				| <button type="button" class="btn modal-btns" onclick="window.location.href='/'">&iexcl;Iniciar Sesi&oacute;n!</button>
			</div>
		</div>
	</div>
</div>
<div id="callContact" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">LLAMA AL VENDEDOR</h4>
			</div>
			<div class="modal-body">
				<?php if($adDetailCar["phone"]){ 
					$code=($adDetailCar["locationphone"]=="MX") ? "1" : "" ; ?>
					<div class="contacto callDirect pointer" onclick="window.open('tel://<?=$adDetailCar["phone"]?>')">
						<?=$adDetailCar["phone"]?>
					</div>
				<?php } if($adDetailCar["phone2"]){ 
					$code=($adDetailCar["locationphone2"]=="MX") ? "1" : "" ; ?>
					<div class="contacto callDirect pointer" onclick="window.open('tel://<?=$adDetailCar["phone2"]?>')">
						<?=$adDetailCar["phone2"]?>
					</div>
				<?php } if($adDetailCar["phone3"]){ 
					$code=($adDetailCar["locationphone3"]=="MX") ? "1" : "" ; ?>
					<div class="contacto callDirect pointer" onclick="window.open('tel://<?=$adDetailCar["phone3"]?>')">
						<?=$adDetailCar["phone3"]?>
					</div>
				<?php } ?>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="whatsContact" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">MENSAJE AL VENDEDOR</h4>
			</div>
			<div class="modal-body">
				<?php if($adDetailCar["phone"] && $adDetailCar["phonewa"]==1){ 
					$code=($adDetailCar["locationphone"]=="MX") ? "1" : "" ; ?>
					<div class="contacto <?= ($adDetailCar["phonewa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phonewa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode"].$code.$adDetailCar["phone"]."','_blank')" : "" ?>">
						<?=$adDetailCar["phone"]?>
					</div>
				<?php } if($adDetailCar["phone2"] && $adDetailCar["phone2wa"]==1){ 
					$code=($adDetailCar["locationphone2"]=="MX") ? "1" : "" ; ?>
					<div class="contacto <?= ($adDetailCar["phone2wa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phone2wa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode2"].$code.$adDetailCar["phone2"]."','_blank')" : "" ?>">
						<?=$adDetailCar["phone2"]?>
					</div>
				<?php } if($adDetailCar["phone3"] && $adDetailCar["phone3wa"]==1){ 
					$code=($adDetailCar["locationphone3"]=="MX") ? "1" : "" ; ?>
					<div class="contacto <?= ($adDetailCar["phone3wa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phone3wa"]==1) ? "window.open('https://wa.me/".$adDetailCar["phonecode3"].$code.$adDetailCar["phone3"]."','_blank')" : "" ?>">
						<?=$adDetailCar["phone3"]?>
					</div>
				<?php } ?>
				</p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="mailContact" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">CORREO AL VENDEDOR</h4>
			</div>
			<div class="modal-body">
				<?php if($adDetailCar["email"]) { ?>
					<div class="contacto mail">
						<a href="mailto:<?= $adDetailCar["email"]?>?Subject=Hola%20me%20interesó%20tu%20anuncio%20de%20AVICars" target="_top"><?= $adDetailCar["email"]?></a>
					</div>
				<?php } if($adDetailCar["email2"]) { ?>
					<div class="contacto mail">
						<a href="mailto:<?= $adDetailCar["email2"]?>?Subject=Hola%20me%20interesó%20tu%20anuncio%20de%20AVICars" target="_top"><?= $adDetailCar["email2"]?></a>
					</div>
				<?php } ?>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="mensaje" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Anunciar mi Auto</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="form-group col-xs-12">
						<label>¿Cuanto vale tu auto?</label>
						<input type="number" id="precio" name="precio" value="0" min="0" class="form-control form-style">
					</div>
					<div class="form-group col-xs-12">
						<div class="publicar">
							<textarea class="" id="publicationAnunciar" rows="5" placeholder="Escribe tu anuncio."></textarea>
							<button onclick="addImageAnunciar()" class="icon" id="iconImgAnunciar" type="button"><i class="glyphicon glyphicon-picture"></i></button>
							<div id="imgDropAnunciar" style="display:none;">
			                	<form id="imgPublicAnunciar" action="/php/perfil/publicacion/uploadImage.php" class="dropzone needsclick" method="post" enctype="multipart/form-data">

									<button class="icon" type="button" onclick="removeAllImagesAnunciar()">x</button>
			                		<div class="fallback">
									    <input name="file"  type="file" multiple />
								   	</div>
			                	</form>
		                	</div>
						</div>
						
					</div>
					<input type="hidden" name="version" id="versionInput" value="" class="">
				</div>	
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-avi" onclick="adverticing()">¡ANUNCIAR!</button>
			</div>
		</div>
	</div>
</div>
<div id="sellCarModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Vender auto</h4>
			</div>
			<div class="modal-body row text-center">
				<p>&iexcl;Felicidades! Ha vendido su auto. Favor de confirmar que esto es correcto.</p>
				<p class="warnSellCar">* Una vez confirmado este cambio  <b>no podr&aacute; ser revertido.</b> ¿Continuar? </p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">CANCELAR</button> |
				<button type="button" class="btn modal-btns" id="soldCar" data-a="" data-dismiss="modal">CONFIRMAR</button>
			</div>
		</div>
	</div>
</div>
<div id="modalShare" class="modal fade" tabindex="-1" role="dialog">

</div>
<script src="/js/dropzone.js?l=<?= LOADED_VERSION?>"></script>
<script type="text/javascript">
	var lastPost=0;
	var search=true;
	var s="a";
	var u='<?= $autoEncoded?>';
</script>
<script type="text/javascript" src="/js/timeline.js?l=<?= LOADED_VERSION?>"></script>
<script type="text/javascript" src="/js/detallesAuto.js?l=<?= LOADED_VERSION?>"></script>
<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
<script type="text/javascript" src="/js/plugin-zoom/jquery.mlens-1.7.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
    $(".img_01").mlens(
    {
        imgSrc: $(".img_01").attr("data-big"),   // path of the hi-res version of the image
        lensShape: "circle",                // shape of the lens (circle/square)
        lensSize: 160,                  // size of the lens (in px)
        borderSize: 2,                  // size of the lens border (in px)
        borderColor: "#fff",                // color of the lens border (#hex)
        borderRadius: 0,                // border radius (optional, only if the shape is square)
        imgOverlay: $(".img_01").attr("data-overlay"), // path of the overlay image (optional)
        overlayAdapt: false, // true if the overlay image has to adapt to the lens size (true/false)
		zoomLevel: 1.5
    });
});
</script>