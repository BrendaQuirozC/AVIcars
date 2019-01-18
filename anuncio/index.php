<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-28 18:46:54
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-28 10:16:45
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(!isset($_GET["a"]))
{
	header("Location: /");
}
if($_GET["a"]=="")
{
	header("Location: /");
}
session_start();
$sess=true;
if(empty($_SESSION))
{
	$sess=false;
}
if(!isset($_SESSION["iduser"]))
{
	$sess=false;
	$idUserSession=0;
}
else{
	$idUserSession=$_SESSION["iduser"];
}
require_once $_SERVER['DOCUMENT_ROOT'].'/php/login/address.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$coder->decode($_GET["a"]);
$anuncioid=$coder->toEncode;
$anuncio = new Anuncio;
$Venta = new Venta;
$address=new Address;
$auto=new Auto;
$Version = new Version;
$Garage = new Garage;
$Usuario = new Usuario;
$getauto= $anuncio->getCarbyAd($anuncioid)["auto"];
$garageContain= $Garage-> instanciaById($getauto);
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
$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
$llaveGarage = $garageContain[0]["o_avi_account_id"];
$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$getauto));
$Like = new Like;
$metasShare=array(
	"og"	=>	array(
		"title" => "AUTO EN VENTA!",
	    "description" => $garageContain[0]["nombreMarca"]." ".$garageContain[0]["nombreSubmarca"]." ".$garageContain[0]["nombreModelo"]." ".$garageContain[0]["nombreVersion"]." ".(($garageContain[0]["currency"]=='EUR') ? '&#128;' : '$')." ".number_format($garageContain[0]["a_avi_sell_detaill_price"], 0, '.', ',')." ".$garageContain[0]["currency"],
	    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
	    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".$_GET["a"],
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
if(isset($_SESSION["iduser"]))
{
	$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],2);
}
else{
	$colaborador = false;
}
if (isset($_GET["a"]) && $_GET["a"]!="") {
	$coder->decode($_GET["a"]);
	$AdId = $coder->toEncode;
	$infoAd=$auto->getAdExtras($AdId);
	$carArCode=new Coder($infoAd["carId"]);
	$usrArCode=new Coder($infoAd["userAd"]);
	header("Location: /perfil/autos/detalles/?cuenta=".$usrArCode->encoded."&auto=".$carArCode->encoded);
}
if($sess)
{
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
}
else
{
	include ($_SERVER['DOCUMENT_ROOT']) . '/login/header.php'; ?>
	<div class=" container-fluid main-container container-transparent">
	<?php
}
	
$notImage=false;
$imagenes = $Garage ->imagenesGenerales($getauto);
$features=array();
if(!empty($versionCar))
	$features = json_decode($versionCar[$versionNum]["c_vehicle_versions_extraSpecifications"],true);

if(empty($imagenes))
{
	$notImage='/img/noimage.png';
}
//include_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/headerAuto.php";
$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
$detalles = $Garage -> getUserdetail($garage["user"]);

$coder->encode($detalles["o_avi_userdetail_id_user"]);
$cuentaEncoded=$coder->encoded;
$userCode=new Coder($detalles["o_avi_userdetail_id_user"]);
$adDetailCar=$auto->adCar($getauto);
if(!empty($adDetailCar))
{
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
if(isset($adDetailCar["zipcode"]))
{
	$zipcodeAddressAd=$address->add($adDetailCar["zipcode"]);
}else{
	$zipcodeAddressAd = null;
}
$privacidadUser=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1; 
$privacidad=(isset($garageContain[0]["privacidad"])) ? $garageContain[0]["privacidad"] : 1;
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
if($garageContain[0]["o_avi_car_version_id"]  && $curModelo){
	$versiones=$auto->knowVersion($curModelo);
}
$conPrecio = $garageContain[0]["a_avi_sell_detaill_price"];
$adOwner = $anuncio -> getOwnerAd($anuncioid);
if($adOwner){
	$owner=false;
	if(!empty($_SESSION) && $_SESSION["iduser"]==$adOwner["owner"])
	{
	    $owner=true;
	}
}
if(isset($garageContain[0]["nombreMarca"]))
{  
	$marcaName=$garageContain[0]["nombreMarca"];
}
if(isset($garageContain[0]["nombreSubmarca"]))
{
	$submarcaName=$garageContain[0]["nombreSubmarca"];
}
	
if(isset($garageContain[0]["nombreModelo"]))
{
	$modeloAno=$garageContain[0]["nombreModelo"];
}
	
if(!empty($versiones))
{
	foreach ($versiones as $vr => $version)
	{ 
		if($version["id"]==$garageContain[0]["o_avi_car_version_id"])
		{
			$versionName=$version["version"];
		}
	} 
}
elseif (isset($garageContain[0]["nombreVersion"])){
	$versionName=$garageContain[0]["nombreVersion"];
}
if(isset($adDetailCar["idAnuncio"]))
{ 
	$comentarios= $anuncio->getCommentAd($adDetailCar["idAnuncio"]);
	$enVenta = isset($adDetailCar["idAnuncio"]);
	?>
	<div class="search sidebar sidebar-no-header sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
		<?php
		if(empty($_SESSION["iduser"]))
		{ ?>
			<p>
				<a href="/" target="_blank">
					<img onclick="modalLoginNow()" src="/img/Banner_registro_AVI.png">
				</a>
			</p>
		<?php } ?>
		<p>
			<a href="/anunciate" target="_blank">
				<img src="/img/ads/promo_ad/<?= rand(1,3)?>.png">
				Clic aqu&iacute;
			</a>
		</p>
	</div>
	<div class="content content-no-header content-no-margin nopadbott">
		<?php 
		 if($garageContain[0]["status_sell"]==2 ){ ?>
			<span class="vendidoImg"></span>
		<?php }?>
		<div class="row">
	    	<div class="container-fluid navbar-profilenavbar-profile-garage header-list auto-header visible-xs hidden-sm hidden-md hidden-lg">
			    <ul class="nav navbar-nav navbar-padding" id="header-list">
					<li><a href="/perfil/autos/detalles/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_autos.png" class="navigation-icon"> <span> Detalles</span> </a></li>
					<li class="<?= ($active=="seguidos") ? "active" : ""?>">
			     		<a href="/perfil/autos/detalles/seguidores/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>">
			     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
			     			<span> Seguidores</span> 
			     		</a>
			     	</li>
			     	<?php if($owner){ ?>
			     	<li class="<?= ($active=="docs") ? "active" : ""?>"><a href="/perfil/autos/detalles/docs/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> <span> Expediente</span> </a></li>
			     	<?php } ?>
			     	<li class="menu-side-bar">
						<a>
							<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon pointer">
							<span>Compartir</span>
							<ul class="sidebar-menu-compartir navigation-list">
								<li class="title"><strong>Compartir</strong></li>
								<li class="pointer" onclick="doShare($(this),3)" data-f="<?= $cuentaEncoded ?>" data-p="<?= $autoEncoded ?>">En AVI cars</li>
								<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".$_GET["a"] ?>">En WhatsApp</li>
								<li class="pointer" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".$_GET["a"] ?>" onclick="copyShare(this,$(this))">Copiar link </li>
							</ul> 
						</a>
					</li>
			    </ul>
			</div>
			<div class="carmenu-topAd">
				<?php $zipcodeAddressAd=$address->add($adDetailCar["zipcode"]); ?>
				<b class="addressAd">
					<?=isset($adDetailCar["street"]) ? $adDetailCar["street"]."," : ''?> 
					<?= $zipcodeAddressAd["city"]." ". $zipcodeAddressAd["state"]?>
					<?=isset($adDetailCar["zipcode"]) ? ", C.P. ".$adDetailCar["zipcode"] : ''?>
				</b>
				<ul class="ad-header-carmenu">
					<?php 
						if($owner || $colaborador)
						{ ?>
						<li class="right">
							<?php if(!empty($_SESSION) && ($detalles["o_avi_userdetail_id_user"]==$idUserSession && $garageContain[0]["status_sell"]!=2) || $colaborador){ 
								$coder-> encode($getauto);
								$autoEncoded = $coder->encoded;
								?>
								<a href="/perfil/autos/detalles/editar/?cuenta=<?= $cuentaEncoded?>&auto=<?=$autoEncoded?>" title="Configurar Perfil">
									<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png" class="pointer settings" alt="configurar perfil">
									<span>Editar</span>
								</a>
							<?php } ?>
						</li>
						<?php } 
					if(isset($_SESSION["iduser"]) && $_SESSION["iduser"]>0)
					{ ?>
						<?php if(!$owner){ ?>
						<li class="dropdown">
							<?php if(!empty($_SESSION) && $detalles["o_avi_userdetail_id_user"]!=$idUserSession){ ?>
							<a href="#" class="pointer dropdown-toggle text-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								<img class="settings" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png">
								<span>M&aacute;s</span>
							</a>
							<ul class="dropdown-menu admenu list-gird publication-list" aria-labelledby="dropdown">
								<li><a class="pointer" data-perfil="<?= $userCode->encoded?>" data-ad="<?= $_GET["a"]?>" onclick="modalToReport($(this))">Reportar </a> </li>
								
							</ul>
							<?php } ?>
						</li>
						<?php } 
					} ?>	
				</ul>
			</div>
	    	<div class="header-position garages-info">
	    		<div class="header-car">
	    			<img src="/img/logos/<?=$marcaName?>.png" alt="">
	    			<p class="h-subbrand"><?=isset($submarcaName) ? $submarcaName : ''?> <?=isset($modeloAno) ? $modeloAno : ''?></p>
	    			<p class="h-version"><?=isset($versionName) ? $versionName : ''?></p>
	    		</div>
				<div class="vignette-ad" style='background-image: url("<?=(!$notImage) ? $imagenes[0]['a_avi_car_img_car'] : $notImage ?>");'>
				</div>
				<div class="footer-car">
	    			<p><?=$garageContain[0]["currency"]=='EUR' ? '&#128;' : '$'?>&nbsp;<?= number_format($conPrecio, 0, '.', ',') ?>.<span class="cents">00</span>&nbsp;<?=$garageContain[0]["currency"]?></p>
	    		</div>
	    		<p class="h-adNegoc"><?= isset($adDetailCar["negociable"]) && $adDetailCar["negociable"]=="1" ? "Negociable" : "No Negociable" ?>
	    		</p>
	    	</div>
		</div>
		<div class="row">
			<b class="time-ad addressAd" title="<?= date("M d, y - H:i\h\\r\\s",strtotime($adDetailCar["fecha"])) ?>"><?=hace(strtotime($adDetailCar["fecha"])) ?></b>
			<div class="time title-com-share">
				<ul class="namesGandU followAd">
					<?php
					$numLikes = $Like->countLikes(5, $adDetailCar["idAnuncio"]);
					if(!$Like->alreadyLike($idUserSession, 5, $adDetailCar["idAnuncio"]))
					{ ?>
						<li class="<?=(!$owner) ? 'pointer' : ''?>" <?=(!$owner) ? 'onclick="like($(this), \''.$_GET["a"].'\', 5)" ' : ''?> >
							<span><?=$numLikes?></span>
							<img class="icon-size-menu" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
						</li>
						<?php 
					} 
					else
					{ ?>
						<li class="<?=(!$owner) ? 'pointer' : ''?>" <?=(!$owner) ? 'onclick="unlike($(this), \''.$_GET["a"].'\', 5)" ' : ''?> >
							<span><?=$numLikes?></span>
							<img class="icon-size-menu" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
						</li>
						<?php 
					} ?>
					<li>
						<a href="#comments">
						<?php if(isset($comentarios[0])){ ?>
							<span><?= ($comentarios[0]["conteo"]>99) ? "+99" : $comentarios[0]["conteo"]?></span>
							<img class="icon-size-menu" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_<?= ($comentarios[0]["icomment"]>0 ? "yellow" : "white")?>.png" alt="">
						<?php }else{?>
							<span>0</span>
							<img class="icon-size-menu" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_white.png" alt="">
						<?php }?>
						</a>
					</li>
					<li class="share">
						<span><?= $anuncio->getShares($anuncioid) ?></span>
						<span>
							<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon pointer">
							<ul class="navigation-list b_share_navlist">
								<li class="title"><strong>Compartir</strong></li>
								<li onclick="doShare($(this),5)" data-p="<?= $_GET["a"] ?>">En AVI cars</li>
								<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".$_GET["a"] ?>">En WhatsApp</li>
								<li data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".$_GET["a"] ?>" onclick="copyShare(this,$(this))">Copiar link </li>
							</ul> 
						</span>
					</li>
				</ul>	
				<?php 
				if($garageContain[0]["privacyGarage"]!=3)
				{ ?>
					<div class="list-garage">
						<?php
						$coderUser = new Coder($garageContain[0]["user"]);
						$coderAccount = new Coder($garageContain[0]["o_avi_account_id"]);
						?>
		    			<div class="head-info">
			    			<div class="carPersonal ellipsis-title" >
			    				<span class="pointer" onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$coderUser->encoded?>&garage=<?=$coderAccount->encoded?>'"> <?=$garageContain[0]["o_avi_account_name"]?></span>
			    				<img src="<?= ($garageContain[0]["privacyGarage"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png" : (($garageContain[0]["privacyGarage"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png") ?>" class="<?= ($garageContain[0]["privacyGarage"]==1) ? "private" : (($garageContain[0]["privacyGarage"]==2) ? "public" : "secret") ?>">
			    			</div>
			    			<?php 
			    			if($garageContain[0]["PrivacyUser"]!=3) 
			    			{ ?>
					    		<div class="CarName">
					    			<span class="pointer" onclick="window.location.href='/perfil/?cuenta=<?=$coderUser->encoded?>'"><?=$garageContain[0]["nameUSer"]?>&nbsp;<?=$garageContain[0]["lastNameUser"]?></span>
					    			<img src="<?= ($garageContain[0]["PrivacyUser"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png" : (($garageContain[0]["PrivacyUser"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png") ?>" class="<?= ($garageContain[0]["PrivacyUser"]==1) ? "private" : (($garageContain[0]["PrivacyUser"]==2) ? "public" : "secret") ?>">
					    		</div>
						    	<?php 
						    } ?>
				    	</div>
					</div>
					<?php 
				} ?>
			</div>
			<div class="row rowChars">
				<?php if($enVenta==true && $conPrecio > 0)
				{ ?>
					<div class="eachCarRow buy-characteristics col-sm-6 col-md-6 col-lg-6 col-xs-12">
						<h5 class="pointer">
							<a class="text-ad-green" data-toggle="modal" data-target="<?= isset($_SESSION["iduser"]) ? '#comprar' : '#confirmLoginModal'?>">
							INFO. DEL&nbsp;VENDEDOR&nbsp;
							<img class="icon-size-menu" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_viewport_features_infotraffic/MyCars_Movil_viewport_features_icon-COMPRA_infotraffic.png" alt="$"></a>
						</h5>
						<p><?= isset($adDetailCar["texto"]) ? $adDetailCar["texto"] : "" ?></p>
					</div>
				<?php }?>
				<div class="eachCarRow <?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6 col-xs-12' : ''?>">
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
	    	<div id="promosCarrusel" class="carousel carousel-cars" data-ride="carousel">
				<div class="carousel-inner carousel-inner-cars" role="listbox">
					<?php 
					if(!$notImage)
					{
			    		foreach ($imagenes as $keyimg => $imageBase) 
			    		{
			    			if($keyimg == 0)
			    			{ ?>
								<div id="vehiclePic<?=$keyimg?>" class="imgCar item active" style="background-image: url('<?=$imageBase['a_avi_car_img_car']?>');" onclick="openSlide();currSlide(<?=$keyimg?>)">
								</div>
								<?php
					  		}
					  		else
					  		{ ?>
					  			<div id="vehiclePic<?=$keyimg?>" class="imgCar item" style="background-image: url('<?=$imageBase['a_avi_car_img_car']?>');" onclick="openSlide();currSlide(<?=$keyimg?>)">
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
							<span>
								<?= $garageContain[0]["verificado"] == 1 ? "<img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?>
							</span>
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
	</div>
	<div class="content content-no-margin m-space "> <!--comentarios-->
		<?php if(isset($adDetailCar["idAnuncio"])){ ?>
		<div id="comments" class="publication-comments" style="display: block;">
			<ul>
				<?php foreach ($comentarios as $c => $comment) { 
					
					$coder->encode($comment['commentId']);
					$idCommentEncoded=$coder->encoded;
					$coder->encode($comment["authorUser"]);
					$autorCoded=$coder->encoded;
					$coder->encode($comment["authorGarage"]);
					$garageCoded=$coder->encoded;
					?>
				<li class="comment"  data-comment="<?= $idCommentEncoded?>">
					<a href="<?= ($comment["type"]==1) ? "/perfil/?cuenta=".$autorCoded : "/perfil/garage/timeline/?cuenta=".$autorCoded."&garage=".$garageCoded ?>"><img src="<?= ($comment["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $comment["imgAuthor"] ?>"></a>
					<h5><a href="<?= ($comment["type"]==1) ? "/perfil/?cuenta=".$autorCoded : "/perfil/garage/timeline/?cuenta=".$autorCoded."&garage=".$garageCoded ?>"><?= $comment["author"] ?></a></h5>
					<p><?= $comment["comentario"]?></p>
					<div class="dropdown edit-publication">
						<a class="btn dropdown-toggle edit-publication" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png" class="options">
						</a>
						<ul class="dropdown-menu list-gird-comment publication-list" aria-labelledby="dropdown">
							<?php if($comment["authorUser"]==$_SESSION["iduser"]){ ?>
							<li><a class="pointer" onclick="editCommentAd($(this))">Editar </a> </li>
							<?php } 
							if($comment["authorUser"]==$_SESSION["iduser"]||$_SESSION["iduser"]==$garageContain[0]["user"]){ ?>
							<li><a class="pointer" onclick="modalToDeleteComAd('<?= $idCommentEncoded?>')">Eliminar</a> </li>
							<?php } 
							if($comment["authorUser"]!=$_SESSION["iduser"]){ ?>
							<li><a class="pointer" data-perfil="<?=$autorCoded?>" data-adcomment="<?= $idCommentEncoded?>" onclick="modalToReport($(this))">Reportar </a> </li>
							<?php } ?>
						</ul>
					</div>
					<span class="time" title="<?= date("M d, Y - H:i",strtotime($comment["fecha"]))?>"><?= hace(strtotime($comment["fecha"]))?></span>
			    </li>
				<?php } 
				if($idUserSession){?>
				<li class="comentor">
					<?php $userData=$Usuario->getUserBasic($idUserSession); ?>
					<img class="header-comment" src="<?= ($userData["img"]=="") ? "/img/icons/avatar1.png" : $userData["img"] ?>" data-t="1" data-e="<?= $userData["id"]?>">&nbsp;<span class="commentor"><?= $userData["name"]?></span>
					<img class="moreCommentors" src="/img/icons/down.png" onclick="moreComentors($(this))">
					<ul class="navigation-list commet-dp" >
					<?php 
						$garages = $Garage -> garageNameAndImageByUsr($idUserSession);
						?>
						<li  onclick="chooseComentor($(this))" data-t="1" data-e="<?= $userData["id"]?>">
							<img src="<?= $userData["img"]?>" alt="usuario">&nbsp;<span><?= $userData["name"]?></span>
						</li>
						<?php
						 if(!empty($garages))
						{
							foreach ($garages as $g => $garage) {
							?>     				
								<li onclick="chooseComentor($(this))" data-t="2" data-e="<?= $g?>">		
									<?php if($garage["imgAvatar"]){?>
										<img src="<?=$garage["imgAvatar"]?>" alt="imagen-<?= $garage["nameAccount"]?>">&nbsp;<span><?= $garage["nameAccount"]?></span>
									<?php }
									else{ ?>
										<img src="/img/icons/avatar1.png" alt="imagen-<?= $garage["nameAccount"]?>">&nbsp;<span><?= $garage["nameAccount"]?></span>
									<?php } ?>
								</li>
							<?php
							}
						}

					?>
					</ul>
					<textarea rows="3" maxlength="160" placeholder="Escribe un comentario (m&aacute;x 160 caracteres)" class="form-style textComment"></textarea>
					<button class="btn btn-avi" onclick="comentarAd($(this))" data-p="<?= $_GET["a"]?>">Enviar</button>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
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
									<?php if(isset($extras["piesas"])) 
									{ ?>
										<p class="detailstab-color-gray detailstab-pad"><?=implode("<br>", $extras["piesas"])?></p>
									<?php }else{ ?>
										<p class='detailstab-color-gray detailstab-pad'>N/A</p>
									<?php }?>
								</td>	
							</tr>
							<tr>
								<td>Fallas menores</td>
								<td>
									<?php if(isset($extras["fallasmenores"])) 
									{ ?>
										<p class="detailstab-color-gray detailstab-pad"><?=implode("<br>", $extras["fallasmenores"])?></p>
									<?php }else{ ?>
										<p class='detailstab-color-gray detailstab-pad'>N/A</p>
									<?php }?>
								</td>
							</tr>
							<tr>
								<td>Fallas mayores</td>
								<td>
									<?php if(isset($extras["fallasmayores"])) 
									{ ?>
										<p class="detailstab-color-gray detailstab-pad"><?=implode("<br>", $extras["fallasmayores"])?></p>
									<?php }else{ ?>
										<p class='detailstab-color-gray detailstab-pad'>N/A</p>
									<?php }?>
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
	<div id="garantias" class="modal fade" role="dialog">
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
	<div id="comprar" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					Me interesa
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<h4 class="text-center">CONTACTO DEL VENDEDOR</h4>
					<?php if($adDetailCar["phone"]){ 
						$code=($adDetailCar["locationphone"]=="MX") ? "1" : "" ; ?>
						<div class="contacto <?= ($adDetailCar["phonewa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phonewa"]==1) ? "window.open('https://wa.me/".substr($adDetailCar["phonecode"], 1).$code.$adDetailCar["phone"]."','_blank')" : "" ?>">
							<?=$adDetailCar["phone"]?>
						</div>
					<?php } if($adDetailCar["phone2"]){ 
						$code=($adDetailCar["locationphone2"]=="MX") ? "1" : "" ; ?>
						<div class="contacto <?= ($adDetailCar["phone2wa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phone2wa"]==1) ? "window.open('https://wa.me/".substr($adDetailCar["phonecode2"], 1).$code.$adDetailCar["phone2"]."','_blank')" : "" ?>">
							<?=$adDetailCar["phone2"]?>
						</div>
					<?php } if($adDetailCar["phone3"]){ 
						$code=($adDetailCar["locationphone3"]=="MX") ? "1" : "" ; ?>
						<div class="contacto <?= ($adDetailCar["phone3wa"]==1) ? "wa" : "" ?>" onclick="<?= ($adDetailCar["phone3wa"]==1) ? "window.open('https://wa.me/".substr($adDetailCar["phonecode3"], 1).$code.$adDetailCar["phone3"]."','_blank')" : "" ?>">
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
							<img src="/img/icons/mail/comprasegura.png" >&nbsp;&nbsp;Tips para una cita de compra segura.
						</a>
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
	<?php 
 ?>
<div id="modalShare" class="modal fade" tabindex="-1" role="dialog">

</div>
<script src="/js/dropzone.js?l=<?= LOADED_VERSION?>"></script>
<script type="text/javascript" src="/js/add.js?l=<?= LOADED_VERSION?>"></script>
<?php
}else 
{ ?>
	<div class="row" style="margin: 126px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible.
		</h3>
		<h4 class="text-center">
			Es posible que el anuncio que busca haya sido eliminado.
			<div style="padding: 20px;">
			<input class="btn btn-default login-btn" type="button" value="Regresar" onclick="history.go(-1);">
			</div>
		</h4>
	</div>
<?php 
}
if(!$sess)
{ ?>
</div>

<input class="hidden number2" type="text" maxlength="9" id="n2" value="0">
<?php } 
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
