<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-18 09:48:51
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-17 10:33:08
 */
if($statusGuest==3){?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
	exit();
}

$padres=$Garage->getAllFathers($_GET["garage"]); 
$privacidad=(isset($garage["privacidad"])) ? $garage["privacidad"] : 1;
$privacidadUser=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1; 
$colaboradoradmingarage=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
$privacyGarage = $Garage -> getGarageInfo($_GET["garage"]);
?>
<style type="text/css">
	#coverGar{
		background-image: url('<?= $extrasGarage["cover"] ?>');
	}
<?php if(file_exists($_SERVER["DOCUMENT_ROOT"].str_replace("/Cover/", "/Cover/cov_", $extrasGarage["cover"]))){ ?>
	@media (max-width: 767px){
		#coverGar{
			background-image: url('<?= str_replace("/Cover/", "/Cover/cov_", $extrasGarage["cover"]) ?>');
		}
	}
<?php } ?>
</style>
<div class="header head-form-garage">
	<?php
	if(!empty($extrasGarage) && $extrasGarage["cover"]!="")
	{ ?>
	<div id="coverGar" class="header-garage img-up" data-link="<?= str_replace("/Cover/", "/Cover/cov_", $extrasGarage["cover"])?>">
	<?php
	}
	else
	{ ?>
	<div class="header-garage" style="background-image: url('/img/PORTADAgarage.jpg')">
	<?php
	}
		if(!empty($extrasGarage)  && $extrasGarage["avatar"]!="") { ?>
			<img id="garagePicture" src='<?= $extrasGarage["avatar"]?>' alt="Avatar" class="imgGarage img-responsive avatar img-up">
		<?php } else { ?>
			<img id="garagePicture" src='/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg' alt="Avatar" class="imgGarage img-responsive avatar">
		<?php }
		if($privacidadUser!=3 || (isset($garage["user"]) && isset($_SESSION["iduser"]) && $garage["user"]==$_SESSION["iduser"]))
		{
			if(!empty($imgPerfil) && $imgPerfil["avatar"]!="")
			{
			?>
				<img  src='<?= $imgPerfil["avatar"]?>' alt="Avatar" class="avatar-garage img-responsive avatar name-user">
			<?php
			} 
			else
			{
			?>
				<img src="/img/icons/avatar1.png" alt="Avatar" class="avatar-garage img-responsive avatar name-user">
			<?php
			}
		}
		?>
	</div>	
		<nav class="navbar navbar-default">
			<div class="col-xs-12 top-name top-name-garage position">
				<ul class="nav">
					<li class="profile-name garage-name" >
						<?= $garage["nameAccount"]?>
						<?php if(!$owner && $garage['verified'] == 1){ ?><span><?= $garage["verified"] == 1 ? "<a href='#' data-toggle='modal' data-target='#no_owner_certified_garage'><img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'></a>" : "" ?></span><?php } ?> <?php if($owner && $garage['verified'] == 1){ ?><span><?= $garage["verified"] == 1 ? "<a href='#' data-toggle='modal' data-target='#owner_certified_garage'><img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'></a>" : "" ?></span><?php } ?>
						<?= (@$colaborador) ? (($owner) ? "<span class='propietario'>Propietario</span>" : "<span class='colaborador'>Colaborador</span>" ) : "" ?>
					</li>
					<?php if($privacidadUser!=3 || (isset($garage["user"]) && isset($_SESSION["iduser"]) && $garage["user"]==$_SESSION["iduser"])) { ?>
					<li class="name-user" style="display: inline;"><?= $detalles["o_avi_userdetail_name"]?>&ensp;<?= $detalles["o_avi_userdetail_last_name"]?></li>
					<?php } ?>
					<?php if(($owner || $following) && isset($extrasGarage["description"]))
					{ ?>
					<li class="garage-info">
						<?= $extrasGarage["description"] ?>
					</li>
					<?php }
					if(($owner || $following || $privacidad==2) && isset($privacyGarage["zip"]))
						{ 
							$add = $address-> add($privacyGarage["zip"]);
							$verifzip=$address->verifyZip($privacyGarage["zip"]);

							if($verifzip!=NULL)
							{
							?>
								<li class="garage-info">
									<?= $extrasGarage["street"]?> <?= $add["city"]?>, Edo. <?= $add["state"] ?>, <?= $add["country"] ?>,  C.P. <?= $privacyGarage["zip"] ?>
								</li>
							<?php 
							} 
					} 
					if(($owner || $following) && (isset($privacyGarage["telefono"])||isset($privacyGarage["celular"])))
					{ ?>

						<li class="garage-info">
							<?= (isset($privacyGarage["telefono"])) ? "Tel: ".$privacyGarage["telefono"] : ""?>
							<?= (isset($privacyGarage["celular"])) ? "Cel: ".$privacyGarage["celular"] : ""?>
						</li>
						<?php 
					}
					if($_SESSION["iduser"]>0){ ?>
					<ul class="icons-header-top-garage">
						<li class="right text-right">
							<a href="#" title="Privacidad" <?= ($colaboradoradmingarage) ? 'href="#" data-toggle="modal" data-target="#privacidad"' : ""?> <?= ($owner) ? 'href="#" data-toggle="modal" data-target="#privacidad"' : ""?> <?= ($privacidad==1) ? 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_private"' : (($privacidad==2) ? 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_public"' : 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_secret"')?>>
							<span>Privacidad</span>
								<img src="<?= ($privacidad==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" : (($privacidad==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png") ?>" class="settings" alt="Privacidad">
							</a>
						</li>
						<?php if($owner||$colaborador)
						{ 
							if (isset($editing)) { ?>
							<li class="<?= ($active=="configure") ? "active" : ""?> right text-right">
								<a href="/perfil/garage/timeline/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>" title="Regresar">
								<span>Regresar</span>
									<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_REGRESAR_infotraffic.png" class="settings">
								</a>
							</li>
							<?php 
							} else { ?>
								<li class="<?= ($active=="configure") ? "active" : ""?> right text-right">
								<a href="/perfil/garage/garage-autos/configurar/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>" title="Configurar Perfil">
								<span>Editar</span>
									<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png" class="settings" >
								</a>
							</li>
							<?php } 
						}
						if(!$owner){ ?>
							<li class="text-right right">
								<?php
								if($following){ ?>
									<?php if($privacidad == 1){ ?>
										<a class="pointer" data-owner="<?= $cuentaEncoded?>" data-toggle='modal' data-target='#Modal_private_g_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
											<span id="follow" <?= !$Seguidor->acepted ? "class='followWaitGarage'" : "" ?> style="text-decoration: none; color: #333;"><?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?></span>
											<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" class="settings pointer" alt="">
										</a>
									<?php } ?>
									<?php if($privacidad == 2){ ?>
										<a class="pointer" data-owner="<?= $cuentaEncoded?>" data-toggle='modal' data-target='#Modal_public_g_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
											<span id="follow" <?= !$Seguidor->acepted ? "class='followWaitGarage'" : "" ?> style="text-decoration: none; color: #333;"><?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?></span>
											<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" class="settings pointer" alt="">
										</a>
									<?php } ?>
									<?php if($privacidad == 3){ ?>
										<a class="pointer" data-owner="<?= $cuentaEncoded?>" data-toggle='modal' data-target='#Modal_secret_g_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
											<span id="follow" <?= !$Seguidor->acepted ? "class='followWaitGarage'" : "" ?> style="text-decoration: none; color: #333;"><?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?></span>
											<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" class="settings pointer" alt="">
										</a>
									<?php } ?>
								<?php }else{ ?>	
								<a class="pointer" data-owner="<?= $cuentaEncoded?>" <?= $privacidad==2 ? 'onclick="seguirPerfil(\''.$garageEncoded.'\', '.$typeFollow.');like($(this), \''.$garageEncoded.'\','.$typeFollow.')"': 'onclick="seguirPerfil(\''.$garageEncoded.'\','.$typeFollow.')"'?> title="Seguir">
									<span id="follow" class="gsolicitud" style="text-decoration: none; color: #333;">Seguir</span>
									<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="settings pointer" alt="">
								</a>
								<?php } ?>
							</li>
							<?php if(!$colaborador){ ?>
							<li class="text-right dropdown">
								<a href="#" class="pointer dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									<span>M&aacute;s</span>
									<img class="settings" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png">
								</a>
								<ul class="dropdown-menu garage list-gird publication-list" aria-labelledby="dropdown">
									<li><a class="pointer" data-perfil="<?= $cuentaEncoded?>" data-garage="<?= $garageEncoded?>" onclick="modalToReport($(this))">Reportar </a> </li>
									<li><a class="pointer" id="beforeBlock" data-to="<?= $cuentaEncoded?>">Bloquear</a> </li>
								</ul>
							</li>
						<?php }
						} ?>
					</ul>
					<?php } ?>
				</ul>
			</div>
			<div class="container-fluid navbar-profilenavbar-profile-garage header-list visible-xs hidden-sm hidden-md hidden-lg">
			    <ul class="nav navbar-nav navbar-padding" id="header-list">
					<li class="<?= ($active=="timeline") ? "active" : ""?>">
						<a href="/perfil/garage/timeline?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>">
							<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/MyGarages_Movil_boton_mini_GarageX_infotraffic.png" class="navigation-icon"> 
							<span> Timeline</span> 
						</a>
					</li>
					<li class="<?= ($active=="seguidos") ? "active" : ""?>">
			     		<a href="/perfil/garage/seguidores/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>">
			     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
			     			<span> Seguidores</span> 
			     		</a>
			     	</li>
			     	<?php if(($privacyGarage["usoId"]==1) || ($privacyGarage["usoId"]==2) || ($privacyGarage["usoId"]==3) || ($privacyGarage["usoId"]==5) || ($privacyGarage["usoId"]==7)) { ?>
			     	<li class="<?= ($active=="autos") ? "active" : ""?>">
			     		<a href="/perfil/garage/garage-autos/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>">
			     			<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
			     			<span> Autos</span> 
			     		</a>
			     	</li>
			     	<?php } ?>
			     	<?php if($owner){ ?>
			     	<li class="<?= ($active=="docs") ? "active" : ""?>">
			     		<a href="/perfil/garage/docs/?cuenta=<?= $cuentaEncoded ?>&garage=<?= $garageEncoded?>">
			     			<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
			     			<span> Expediente</span> 
			     		</a>
			     	</li>
			     	<?php } ?>
			     	<li class="menu-side-bar">
						<a class="pointer">
							<span>
								Compartir
								 
							</span>
							<ul class="sidebar-menu-compartir navigation-list">
								<li class="title"><strong>Compartir</strong></li>
								<li <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),2)"' : 'onclick=window.location.href="/"' ?>  data-f="<?= $cuentaEncoded ?>" data-p="<?= $garageEncoded ?>">En AVI cars</li>
								<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$cuentaEncoded."&garage=".$garageEncoded ?>">En WhatsApp</li>
								<li data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$cuentaEncoded."&garage=".$garageEncoded ?>" onclick="copyShare(this,$(this))">Copiar link </li>	
							</ul>
								<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon">
						</a>
					</li>
			    </ul>
			</div>
		</nav>
	</div>
	<?php
	if($owner||$colaborador){ ?>
		<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="garageCover">
			<div class="editing">	
				<label for="portadaGarage" class="filelabel">
					<img  class="edit-cover" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
					<input type='hidden' id='hiddenRoute' value=''/>
					<input type='hidden' name='garage' id='garage' value='<?= $_GET["garage"]?>'/>
				</label>
				<input name="portadaGarage" id="portadaGarage" class="inputfile" type="file" onchange="coverGarage()" />
			</div>
		</form>
		<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="garageAvatar">
			<div class="editing-garage-avatar">	
				<label for="imagenGarage" class="filelabel">
					<img  class="edit-cover" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
					<input type='hidden' id='hiddenRouteAvatar' value=''/>
					<input type='hidden' name='garage' id='garage' value='<?= $_GET["garage"]?>'/>
				</label>
				<input name="imagenGarage" id="imagenGarage" class="inputfile" type="file" onchange="avatarGarage()" />
			</div>
		</form>
	<?php
	} ?>
	<div id="profileImgModal" class="modalAv modal">
		<span class="close" onclick="closemodalImgAvatar()">&times;</span>
		<div class="modal-content">
			<img class="modal-avatar" id="modAvatar" alt="" src="">
		</div>
	</div> 
	<div class="sidebar sidebar-garege hidden-xs visible-sm visible-md visible-lg" id="sidebar">
		<ul>
			<li class="<?= ($active=="timeline") ? "active" : ""?>">
				<a href="/perfil/garage/timeline/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>">
					<span> Timeline</span> 
					<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/MyGarages_Movil_boton_mini_GarageX_infotraffic.png" class="navigation-icon"> 
				</a>
			</li>
			<?php if($owner || $privacidad!=1 || ($following && $Seguidor->acepted)) { ?>
	     	<li class="<?= ($active=="seguidos") ? "active" : ""?>">
	     		<a href="/perfil/garage/seguidores/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>">
	     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
	     			<span> Seguidores</span> 
	     		</a>
	     	</li>
			<?php
			} ?>
			<?php if(($privacyGarage["usoId"]==1) || ($privacyGarage["usoId"]==2) || ($privacyGarage["usoId"]==3) || ($privacyGarage["usoId"]==5) || ($privacyGarage["usoId"]==7)) { ?>
	     	<li class="<?= ($active=="autos") ? "active" : ""?>">
	     		<a href="/perfil/garage/garage-autos/?cuenta=<?=$cuentaEncoded?>&garage=<?= $garageEncoded?>">
	     			<span> Autos</span> 
	     			<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
	     		</a>
	     	</li>
	     	<?php } ?>
	     	<?php if($owner){ ?>
	     	<li class="<?= ($active=="docs") ? "active" : ""?>">
	     		<a href="/perfil/garage/docs/?cuenta=<?= $cuentaEncoded ?>&garage=<?= $garageEncoded?>"> 
	     			<span> Expediente</span> 
	     			<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon">
	     		</a>
	     	</li>
	     	<?php } ?>
	     	<li class="menu-side-bar">
				<a class="pointer">
					<span>
						Compartir
						<ul class="sidebar-menu-compartir navigation-list">
							<li class="title"><strong>Compartir</strong></li>
							<li <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),2)"' : 'onclick=window.location.href="/"' ?> data-f="<?= $cuentaEncoded ?>" data-p="<?= $garageEncoded ?>">En AVI cars</li>
						<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$cuentaEncoded."&garage=".$garageEncoded ?>">En WhatsApp</li>
						<li data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$cuentaEncoded."&garage=".$garageEncoded ?>" onclick="copyShare(this,$(this))">Copiar link </li>								</ul> 
					</span>
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon">
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
	<div class="sidebar sidebar-garege sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
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
	<script src="/js/edit.js"></script>
	<script type="text/javascript">
		function navigationFather(e){
			if(e.hasClass("open"))
			{
				e.removeClass("open");
				$('#listaNavegacion').css('visibility','hidden');
			}
			else{
				e.addClass("open");
				$('#listaNavegacion').css('visibility','visible');
			}

		}
		$(".name-user").click(function(){
			var geturl=location.search.substr(1).split("&");
			var $urlUser="";
			geturl.forEach(function(element) {
  				//queryDict[element.split("=")[0]] = item.element("=")[1]
  				if(element.split("=")[0]=="cuenta")
  				{
  					$urlUser="?cuenta="+element.split("=")[1];
  				}
  			});
  			if($urlUser!="")
  			{
  				location.href="/perfil/"+$urlUser;
  			}
		});
	</script>
