<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-17 13:51:33
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-15 10:52:56
 */

$liker=false;
if($statusGuest==3){?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			El usuario al que intentas acceder a&uacute;n no confirma su cuenta
		</h3>
	</div>

<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
	exit();
}
$privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1; 
?>
<style type="text/css">
	#coverPicture{
		background-image: url('<?= $imgPerfil["cover"]?>');
	}
<?php if(file_exists($_SERVER["DOCUMENT_ROOT"].str_replace("/Cover/", "/Cover/cov_", $imgPerfil["cover"]))){ ?>
	@media (max-width: 767px){
		#coverPicture{
			background-image: url('<?= str_replace("/Cover/", "/Cover/cov_", $imgPerfil["cover"]) ?>');
		}
	}
<?php } ?>
</style>
<div class="header head-form">
	<?php
	if(!empty($imgPerfil) && $imgPerfil["cover"]!="")
	{
	?>
	<div id="coverPicture" class="header-profile img-up" data-up="2" data-link="<?= str_replace("/Cover/", "/Cover/cov_", $imgPerfil["cover"]) ?>" style="">			

    <?php
	}
	else
	{
	?>
	<div class="header-profile" style="background-image: url('/img/portada.jpg')">			
	<?php
	} ?>
		
	<?php
	if(!empty($imgPerfil)  && $imgPerfil["avatar"]!="")
	{ ?>
		<img id="profilePicture" class="avatar-profile img-up" data-up="1" src='<?= $imgPerfil["avatar"]?>' alt="Avatar" >
		<?php
	}
	else
	{ ?>
		<img class="avatar-profile" src='/img/icons/avatar1.png' alt="Avatar">
	<?php
	}
	?>
	</div>	
	<nav class="navbar navbar-default profileHead">
		<div class="col-xs-12 icons-header-top">
			<?php if($_SESSION["iduser"]>0){ ?>
			<ul>
				<li>
				<?php
				if(!$owner){
					if($following){ ?>
						<?php if($privacidad == 1){ ?>
						<a class="pointer" data-toggle='modal' data-target='#Modal_private_p_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
							<span id="follow" <?= !$Seguidor->acepted ? "class='followWait'" : "" ?> style="text-decoration: none; color: #333;">
								<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>
							</span>
							<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" class="settings pointer" alt="">
						</a>
						<?php } ?>
						<?php if($privacidad == 2){ ?>
						<a class="pointer" data-toggle='modal' data-target='#Modal_public_p_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
							<span id="follow" <?= !$Seguidor->acepted ? "class='followWait'" : "" ?> style="text-decoration: none; color: #333;">
								<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>
							</span>
							<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" class="settings pointer" alt="">
						</a>
						<?php } ?>
						<?php if($privacidad == 3){ ?>
						<a class="pointer" data-toggle='modal' data-target='#Modal_secret_p_header' title="<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>">
							<span id="follow" <?= !$Seguidor->acepted ? "class='followWait'" : "" ?> style="text-decoration: none; color: #333;">
								<?= $Seguidor->acepted ? "Siguiendo" : "Solicitud enviada" ?>
							</span>
							<img src="<?= $Seguidor->acepted ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" : "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" ?>" class="settings pointer" alt="">
						</a>
						<?php } ?>
					<?php }else{ ?>	
						<a class="pointer" <?= $privacidad==2 ? 'onclick="seguirPerfil(\''.$cuentaCoded.'\', '.$typeFollow.');like($(this), \''.$cuentaCoded.'\','.$typeFollow.')"': 'onclick="seguirPerfil(\''.$cuentaCoded.'\','.$typeFollow.')"'?> title="Seguir">
							<span id="follow" class="psolicitud" style="text-decoration: none; color: #333;">Seguir</span>
							<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="settings pointer" alt="">
						</a>
					<?php } ?>
				<?php	
				}else{ 
					if (isset($editing)) { ?>
					<a href="/perfil/?cuenta=<?=$cuentaCoded?>" title="Regresar al Perfil">
						<img class="settings" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_REGRESAR_infotraffic.png">
						<span>Regresar</span>
					</a>
					<?php 
					} else { ?>
					<a href="/perfil/edit/cuenta/" title="Configurar Perfil">
						<img class="settings" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png">
						<span>Editar</span>
					</a>
					<?php }
				} ?>
				</li>
				<li class="dropdown">
					<a title="Privacidad" <?= ($owner) ? 'href="#" data-toggle="modal" data-target="#privacidad"' : ""?> <?= ($privacidad==1) ? 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_private"' : (($privacidad==2) ? 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_public"' : 'href="#" data-toggle="modal" data-target="#Modal_privacidad_header_profile_garage_car_secret"')?>>
						<img src="<?= ($privacidad==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" : (($privacidad==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png") ?>" class="settings" alt="Privacidad">
						<span>Privacidad</span>
					</a>
				<?php
				if(!$owner){ ?>
					<a href="#" class="pointer dropdown-toggle text-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
						<img class="settings" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png">
						<span>M&aacute;s</span>
					</a>
					<ul class="dropdown-menu dropdown-menu-left list-gird publication-list" aria-labelledby="dropdown">
						<li><a class="pointer" data-perfil="<?= $cuentaCoded?>" onclick="modalToReport($(this))">Reportar </a> </li>
						<li><a class="pointer" id="beforeBlock" data-to="<?= $cuentaCoded?>">Bloquear</a> </li>
					</ul>
				<?php } ?>
				</li>
			</ul>	
			<?php }else{ ?>
			<ul>
				<li style="visibility: hidden;">
				 AVI cars
				</li>
			</ul>
			<?php } ?>		
		</div>
		<div class="col-xs-12 text-center top-name position">
			<ul class="nav infoprofile-margin">
				<li class="profile-name" >
					<?= $detalles["o_avi_userdetail_name"]?>&thinsp;<?= $detalles["o_avi_userdetail_last_name"]?> 
					<?php if(!$owner && $detalles["verified"] == 1)
					{ ?>
						<span><?= $detalles["verified"] ? "<a href='#' data-toggle='modal' data-target='#no_owner_certified_profile'><img class='icon-verified head' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'></a>" : "" ?></span>
						<?php 
					} ?>
					<?php if($owner && $detalles["verified"])
					{ ?>
						<span><?= $detalles["verified"] == 1 ? "<a href='#' data-toggle='modal' data-target='#owner_certified_profile'><img class='icon-verified head' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'></a>" : "" ?></span>
						<?php 
					} ?>
				</li>
				<li class="sub-profile-name"><?= $detalles["bio"]?></li>
				<li class="sub-profile-name"><?= ($detalles["a_avi_useraddress_zip_code"]) ? $detalles["municipio"].", ".$detalles["estado"] : "" ?></li>
			</ul>
		</div>
		<div class="container-fluid navbar-profile header-list visible-xs hidden-sm hidden-md hidden-lg">
		    <ul class="nav navbar-nav navbar-padding" id="header-list">
		   	<?php if(!$owner && $privacidad!=1 || ($following && $Seguidor->acepted)) 
		   	{ ?>
				<li class="<?= ($active=="profile") ? "active" : ""?>">
					<a href="/perfil/?cuenta=<?=$cuentaCoded?>">
						<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_miTimeline_infotraffic.png" class="navigation-icon">
						<span> Timeline</span>
					</a>
				</li>
				<?php 
	    	} ?>	 
	        	<li class="<?= ($active=="seguidos") ? "active" : ""?>">
		     		<a href="/perfil/seguidores/?cuenta=<?=$cuentaCoded?>">
		     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
		     			<span><?php if($owner) { ?> Mis <?php }?> Seguidores</span> 
		     		</a>
		     	</li>   
		      	<li class="<?= ($active=="garage") ? "active" : ""?>">
		      		<a href="/perfil/garage/?cuenta=<?=$cuentaCoded?>">
		      			<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon"> 
		      			<span> <?php if($owner) { ?> Mis <?php }?> Garages</span> 
		      		</a>
		      	</li>
		      	<li class="<?= ($active=="auto") ? "active" : ""?>">
		      		<a href="/perfil/autos/?cuenta=<?=$cuentaCoded?>">
		      			<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
		      			<span> <?php if($owner) { ?> Mis<?php }?> Autos</span> 
		      		</a>
		      	</li>
		      	<?php if($owner){ ?>
		      	<li class="<?= ($active=="docs") ? "active" : ""?>">
		      		<a href="/perfil/docs/?cuenta=<?=$cuentaCoded?>">
		      			<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
		      			<span> Mi Expediente</span> 
		      		</a>
		      	</li>
				<?php } else
				{ 
					if ($privacidad != 3) 
					{ ?>
						<li class="menu-side-bar">
							<a>
								<span>Compartir</span>
								<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon pointer">
								<ul class="sidebar-menu-compartir navigation-list">
									<li class="title"><strong>Compartir</strong></li>
									<li <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),1)"' : 'onclick=window.location.href="/"' ?> data-p="<?= $cuentaCoded?>">En AVI cars</li>
									<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/?cuenta=".$cuentaCoded ?>">En WhatsApp</li>
									<li data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/?cuenta=".$cuentaCoded ?>" onclick="copyShare(this,$(this))">Copiar link </li>
								</ul> 
							</a>
						</li>
						<?php 
					}
				} ?>
		    </ul>
		</div>
	</nav>
</div>
<?php if($owner){ ?>
<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="formCover">
	<div class="editing">	
		<label for="portada" class="filelabel">
			<img  class="edit-cover" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
			<input type='hidden' id='hiddenRoute' value=''/>
		</label>
		<input name="portada" id="portada" class="inputfile form-type" type="file" onchange="cover()" />
	</div>
</form>
<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="formAvatar">
	<div class="editing-avatar">	
		<label for="avatar" class="filelabel">
			<img  class="edit-cover" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
			<input type='hidden' id='hiddenRouteAvatar' value=''/>
		</label>
		<input name="avatar" id="avatar" class="inputfile" type="file" onchange="avatarAng()" />
	</div>
</form>
<?php } ?>
<div id="profileImgModal" class="modalAv modal">
	<span class="close" onclick="closemodalImgAvatar()">&times;</span>
	<div class="modal-content">
		<img class="modal-avatar" id="modAvatar" alt="" src="">
	</div>
</div>
<div class="sidebar hidden-xs visible-sm visible-md visible-lg" id="sidebar">
	<ul>
		<?php if(!$owner && $privacidad!=1 || ($following && $Seguidor->acepted)) 
		{ ?>
			<li class="<?= ($active=="profile") ? "active" : ""?>">
				<a href="/perfil/?cuenta=<?=$cuentaCoded?>">
					<span> Timeline</span>
					<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_miTimeline_infotraffic.png" class="navigation-icon"> 
				</a>
			</li>
			<?php 
		} ?>
		<li class="<?= ($active=="seguidos") ? "active" : ""?>">
     		<a href="/perfil/seguidores/?cuenta=<?=$cuentaCoded?>">
     			<span><?php if($owner) { ?> Mis <?php }?>Seguidores</span> 
     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
     		</a>
     	</li>
		<li class="<?= ($active=="garage") ? "active" : ""?>">
			<a href="/perfil/garage/?cuenta=<?=$cuentaCoded?>"> 
				<span> <?php if($owner) { ?> Mis <?php }?> Garages</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon">
			</a>
		</li>
		<li class="<?= ($active=="auto") ? "active" : ""?>">
			<a href="/perfil/autos/?cuenta=<?=$cuentaCoded?>">
				<span> <?php if($owner) { ?> Mis <?php }?> Autos</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
			</a>
		</li>
		<?php if($owner){ ?>
		<li class="<?= ($active=="docs") ? "active" : ""?>">
			<a href="/perfil/docs/?cuenta=<?=$cuentaCoded?>">
				<span> Mi Expediente</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
			</a>
		</li>
		<?php } else{ ?>
			<?php if($privacidad!=1 || ($following && $Seguidor->acepted)) { ?>
		<li class="menu-side-bar">
			<a class="pointer">
				<span>
					Compartir
					<ul class="sidebar-menu-compartir navigation-list">
						<li class="title"><strong>Compartir</strong></li>
						<li <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),1)"' : 'onclick=window.location.href="/"' ?> data-p="<?= $cuentaCoded?>">En AVI cars</li>
						<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/?cuenta=".$cuentaCoded ?>">En WhatsApp</li>
						<li data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/?cuenta=".$cuentaCoded ?>" onclick="copyShare(this,$(this))">Copiar link </li>
					</ul> 
				</span>
					<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" class="navigation-icon pointer">
			</a>
		</li>
			<?php } ?>
		<?php } ?>
	</ul>
	<p><a href="https://apoyovial.net/acerca-de/" target="_blank">Acerca de</a></p>
	<p><a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">T&eacute;rminos y Condiciones</a></p>
	<p><a href="/buzon" target="_blank">Sugerencias</a></p>
	<p><a href="/Aviso_de_Privacidad_AVIcars.pdf" target="_blank">Aviso de Privacidad</a></p>
	<p><a href="/ayuda" target="_blank">Ayuda</a></p>
	<p class="marca">ApoyoVial&reg; 2018</p>
</div>
<div class="sidebar sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
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
		<a class="perfilpromo" href="/anunciate" target="_blank">
			<img src="/img/ads/promo_ad/<?= rand(1,3)?>.png">
			Clic aqu&iacute;
		</a>
	</p>
</div>
<script src="/js/edit.js"></script>
