<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-21 10:34:24
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-12-18 09:02:20
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
if(empty($infoPerfil) || ($statusGuest==3)){?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>

<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
	exit();
}
$Like = new Like;
$privacidadUser=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1; 
$privacidad=(isset($garageContain[0]["privacidad"])) ? $garageContain[0]["privacidad"] : 1;
$colaboradoradmingarageauto=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],1);
$colaboradorcomercialgarageauto=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],2);
if($currMarca){
	foreach ($marcas as $mc => $marca) 
	{
		if($mc==$currMarca)
		{
			$marcaName=$marca;
		}
	} 
}
elseif(isset($garageContain[0]["nombreMarca"])){
	$marcaName=$garageContain[0]["nombreMarca"];
}
if($curSubMarca){
	foreach ($submarcas as $sb => $sbmarca) 
	{
		if($sbmarca["id"]==$curSubMarca)
		{
			$submarcaName=$sbmarca["submarca"];
		}
	} 
}
elseif(isset($garageContain[0]["nombreSubmarca"])){
	$submarcaName=$garageContain[0]["nombreSubmarca"];
}
if($curModelo){
	foreach ($modelos as $md => $modelo) 
	{
		if($modelo["id"]==$curModelo)
		{
			$modeloAno=$modelo["modelo"];
		}
	} 
}
elseif(isset($garageContain[0]["nombreModelo"])){
	$modeloAno=$garageContain[0]["nombreModelo"];
}
if(!empty($versiones)){
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
$enVenta = isset($adDetailCar["idAnuncio"]);
$conPrecio = $garageContain[0]["a_avi_sell_detaill_price"];
?>
<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
	<ul>
		<li class="<?= ($active=="timeline") ? "active" : ""?>"> <a href="/perfil/autos/detalles/?cuenta=<?=$cuentaEncoded?>&auto=<?=$autoEncoded?>"><span> Detalles</span><img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_autos.png" class="menuAuto-w navigation-icon"> </a></li>
		
     	<li class="<?= ($active=="seguidos") ? "active" : ""?>">
     		<a href="/perfil/autos/detalles/seguidores/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>">
     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off_small.png" class="navigation-icon"> 
     			<span> Seguidores</span> 
     		</a>
     	</li>
     	<?php if($owner||$colaborador){ ?>
     	<li class="<?= ($active=="docs") ? "active" : ""?>"> <a href="/perfil/autos/detalles/docs/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><span> Expediente</span><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="menuAuto-w navigation-icon"> </a></li>
       	<?php } ?>
       	<li class="menu-side-bar">
			<a>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon pointer">
				<span class="pointer">Compartir</span>
				<ul class="sidebar-menu-compartir navigation-list">
					<li class="title"><strong>Compartir</strong></li>
					<li class="pointer" <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),3)"' : 'onclick=window.location.href="/"' ?> data-f="<?= $cuentaEncoded ?>" data-p="<?= $autoEncoded ?>">En AVI cars</li>
					<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$cuentaEncoded."&auto=".$autoEncoded ?>">En WhatsApp</li>
					<li class="pointer" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$cuentaEncoded."&auto=".$autoEncoded ?>" onclick="copyShare(this,$(this))">Copiar link </li>
				</ul> 
			</a>
		</li>
    </ul>
	<p><a href="https://apoyovial.net/acerca-de/" target="_blank">Acerca de</a></p>
	<p><a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">T&eacute;rminos y Condiciones</a></p>
	<p><a href="/buzon" target="_blank">Sugerencias</a></p>
	<p><a href="/Aviso_de_Privacidad_AVIcars.pdf" target="_blank">Aviso de Privacidad</a></p>
	<p><a href="/ayuda" target="_blank">Ayuda</a></p>
	<p class="marca">ApoyoVial&reg; 2018</p>
	
</div>
<div class="sidebar sidebar-no-header sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
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
<?php 
	$coderUser = new Coder($garageContain[0]["user"]);
	$coderAccount = new Coder($garageContain[0]["o_avi_account_id"]);
?>
<div class="content content-no-header content-img <?= ($enVenta) ? 'nopadbott': ''?>">
	<?php if($garageContain[0]["status_sell"]==2 ){ ?>
		<span class="vendidoImg"></span>
	<?php } ?>
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
		     	<?php if($owner||$colaborador){ ?>
		     	<li class="<?= ($active=="docs") ? "active" : ""?>"><a href="/perfil/autos/detalles/docs/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> <span> Expediente</span> </a></li>
		     	<?php } ?>
		     	<li class="menu-side-bar">
					<a>
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon pointer">
						<span>Compartir</span>
						<ul class="sidebar-menu-compartir navigation-list">
							<li class="title"><strong>Compartir</strong></li>
							<li class="pointer" <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),3)"' : 'onclick=window.location.href="/"' ?> data-f="<?= $cuentaEncoded ?>" data-p="<?= $autoEncoded ?>">En AVI cars</li>
							<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$cuentaEncoded."&auto=".$autoEncoded ?>">En WhatsApp</li>
							<li class="pointer" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$cuentaEncoded."&auto=".$autoEncoded ?>" onclick="copyShare(this,$(this))">Copiar link </li>
						</ul> 
					</a>
				</li>
		    </ul>
		</div>
		<div class="<?=($enVenta && $conPrecio > 0) ? 'carmenu-topAd' :'carmenu-top'?>">
			<?php 
			if ($enVenta && $conPrecio > 0) 
			{ 
				$zipcodeAddressAd=$address->add($adDetailCar["zipcode"]); ?>
				<b class="addressAd">
					<?=isset($adDetailCar["street"]) ? $adDetailCar["street"]."," : ''?> 
					<?= $zipcodeAddressAd["city"]." ". $zipcodeAddressAd["state"]?>
					<?=isset($adDetailCar["zipcode"]) ? ", C.P. ".$adDetailCar["zipcode"] : ''?>
				</b>
			<?php }?>
			<ul class="ad-header-carmenu">
				<?php 
				if($_SESSION["iduser"]>0)
				{ ?>
					<li class="right dropdown text-right">
						<a href="#" title="Privacidad" <?= ($colaboradoradmingarageauto) ? 'href="#" data-toggle="modal" data-target="#privacidad"' : (($colaboradorcomercialgarageauto) ? 'href="#" data-toggle="modal" data-target="#privacidad"' : "")?> <?= ($owner) ? 'href="#" data-toggle="modal" data-target="#privacidad"' : ""?> <?= ($privacidad==1) ? 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_private"' : (($privacidad==2) ? 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_public"' : 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_secret"')?>>
							<img src="<?= ($privacidad==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" : (($privacidad==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png") ?>" class="settings" alt="Privacidad">
							<span>Privacidad</span>
						</a>
					</li>
					<?php 
					if($owner||$colaborador)
					{ ?>
					<li class="right">
						<a  href="/perfil/autos/detalles/editar/?cuenta=<?=$cuentaEncoded?>&auto=<?= $autoEncoded?>" title="Configurar Perfil">
							<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png" class="pointer settings" alt="configurar perfil">
							<span>Editar</span>
						</a>
					</li>
					<?php } if(!$owner){ ?>
					<li class="dropdown">
						<a href="#" class="pointer dropdown-toggle text-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<img class="settings" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png">
							<span>M&aacute;s</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-left list-gird publication-list" aria-labelledby="dropdown">
							<li><a class="pointer" data-perfil="<?= $cuentaEncoded?>" data-car="<?= $autoEncoded?>" onclick="modalToReport($(this))">Reportar </a> </li>
							<?php $codeUSer=new Coder( $_GET["cuenta"]); ?>
							<li><a class="pointer" id="beforeBlock" data-to="<?= $cuentaEncoded?>">Bloquear</a> </li>
						</ul>
					</li>
					<?php } 
				} ?>	
			</ul>
		</div>
    	<div class="header-position garages-info">
    		<div class="header-car">
				<?php if(isset($garageContain[0]["brand"])) { ?>
	    			<img src="/img/logos/<?=$marcaName?>.png" alt="">
				<?php } ?>
    			<p class="h-subbrand"><?= isset($submarcaName) ? $submarcaName : ''?> <?=isset($modeloAno) ? $modeloAno : ''?></p>
    			<?php if($enVenta && $conPrecio > 0) 
    			{ ?>
    			<p class="h-version"><?=isset($versionName) ? $versionName : ''?></p>
    			<?php 
    			} ?>
    		</div>
			<div class="vignette" style='background-image: url("<?=(!$notCover) ? $coverAuto[0]['a_avi_car_img_car'] : (!$notImage ? $imagenes[0]['a_avi_car_img_car'] : $notImage) ?>");'>
			</div>
			<div class="footer-car">
				<?php if($enVenta && $conPrecio > 0) 
    			{ ?>
    			<p><?=$garageContain[0]["currency"]=='EUR' ? '&#128;' : '$'?>&nbsp;<?= number_format($conPrecio, 0, '.', ',') ?>.<span class="cents">00</span>&nbsp;<?=$garageContain[0]["currency"]?></p>
    			<?php 
    			} else { ?>
    			<p><?=isset($versionName) ? $versionName : ''?></p>
    			<?php 
    			} ?>
    		</div>
    		<?php if($enVenta && $conPrecio > 0) 
    		{ ?>
	    		<p class="h-adNegoc">
	    			<?= isset($adDetailCar["negociable"]) && $adDetailCar["negociable"]=="1" ? "Negociable" : "No Negociable" ?>
		    	</p>
		    	<?php 
	    	} 
			if(!$owner && $enVenta && $conPrecio > 0 && $garageContain[0]["status_sell"]!=2 && $_SESSION["iduser"]!=0)
			{ 
				$idUserSession=$_SESSION["iduser"];
			 	$coderAd = new Coder($adDetailCar["idAnuncio"]);
				$numLikes = $Like->countLikes(5, $adDetailCar["idAnuncio"]);
				if(!$Like->alreadyLike($idUserSession, 5, $adDetailCar["idAnuncio"])){ ?>
	    		<p class="h-adSale pointer" onclick="like($(this), '<?= $coderAd->encoded?>', 5)">
	    			Me Interesa
	    		</p>
	    		<?php } 
	    		else
	    		{ ?>
	    		<p class="h-adSale pointer" onclick="unlike($(this), '<?= $coderAd->encoded ?>', 5)">
	    			Ya no me interesa
	    		</p>
	    		<?php 
	    		}
		    }?>
    	</div>
	</div>
	<div class="menu-paddtop">
		<?php 
		if($colaborador!=1 && $_SESSION["iduser"]!=0)
		{ ?>
			<div class="follow-car">
				<?php if($following)
				{ ?>
					<?php if($privacidad == 1){ ?>
					<a class="pointer" data-toggle='modal' data-target='#Modal_private_a_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
						<span id="follow" <?= !$Seguidor->acepted ? "class='followWait'" : "" ?> style="text-decoration: none; color: #333;"><?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?></span>
						<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" alt="">
					</a>
					<?php } ?>
					<?php if($privacidad == 2){ ?>
					<a class="pointer" data-toggle='modal' data-target='#Modal_public_a_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
						<span id="follow" <?= !$Seguidor->acepted ? "class='followWait'" : "" ?> style="text-decoration: none; color: #333;"><?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?></span>
						<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" alt="">

					</a>
					<?php } ?>
					<?php if($privacidad == 3)
					{ ?>
						<a class="pointer" data-toggle='modal' data-target='#Modal_secret_a_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
							<span id="follow" <?= !$Seguidor->acepted ? "class='followWait'" : "" ?> style="text-decoration: none; color: #333;"><?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?></span>
							<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" alt="">

						</a>
					<?php 
					}
				} else
				{ ?>	
					<a class="pointer" data-owner="<?= $garage["user"]?>" <?= $privacidad==2 ? 'onclick="seguirPerfil(\''.$autoEncoded.'\', '.$typeFollow.');like($(this), \''.$autoEncoded.'\','.$typeFollow.')"': 'onclick="seguirPerfil(\''.$autoEncoded.'\','.$typeFollow.')"'?> title="Seguir">
						<span id="follow" class="asolicitud" style="text-decoration: none; color: #333;">Seguir</span>
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" alt="">
					</a>

					<?php 
				} ?>
			</div>
			<?php 
		} 
		else
		{	
			if(isset($garageContain[0]["status_sell"]) && $garageContain[0]["status_sell"]!=2 && $_SESSION["iduser"]!=0)
			{
				$coderAd = new Coder($adDetailCar["idAnuncio"]); ?>
				<button type="button" class="btn soldStyle" data-car="<?= $autoEncoded ?>" data-ad="<?= $coderAd->encoded ?>" onclick="toSold($(this))">
					<img class="price_img" title="Marcar como vendido" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_viewport_features_infotraffic/MyCars_Movil_viewport_features_icon-COMPRA_infotraffic.png">
				</button>
				<?php 
			}
		}
		if($garageContain[0]["privacyGarage"]!=3){ ?>
		<div class="col-xs-12 icons-header-top infogarage-car-top">
			<ul class="namesGandU nameGarage">
				<ul class="garage-title">
					<div class="list-garage">
						<div class="head-info">
							<div class="carPersonal ellipsis-title" >
								<span class="pointer" onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$coderUser->encoded?>&garage=<?=$coderAccount->encoded?>'"> <?=$garageContain[0]["o_avi_account_name"]?>
								</span>
								<img src="<?= ($garageContain[0]["privacyGarage"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png" : (($garageContain[0]["privacyGarage"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png") ?>" class="<?= ($garageContain[0]["privacyGarage"]==1) ? "private" : (($garageContain[0]["privacyGarage"]==2) ? "public" : "secret") ?>">
							</div>
							<?php if($garageContain[0]["PrivacyUser"]!=3)
							{ ?>
							<div class="CarName ellipsis-title">
								<span class="pointer" onclick="window.location.href='/perfil/?cuenta=<?=$coderUser->encoded?>'"><?=$garageContain[0]["nameUSer"]?>&nbsp;<?=$garageContain[0]["lastNameUser"]?>	
								</span>
								<img src="<?= ($garageContain[0]["PrivacyUser"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png" : (($garageContain[0]["PrivacyUser"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png") ?>" class="<?= ($garageContain[0]["PrivacyUser"]==1) ? "private" : (($garageContain[0]["PrivacyUser"]==2) ? "public" : "secret") ?>">
							</div>
							<?php 
							} ?>
						</div>
					</div>
				</ul>
			</ul>
		 </div>
	<?php } ?>
		<div class="table-sC table-responsive">
		</div>
	</div>
</div>

