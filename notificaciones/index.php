<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-21 11:32:12
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:58:20
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
if(isset($_SESSION["iduser"]))
{
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
	$Garage = new Garage;
	$coder = new Coder();
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
	$follows="seguidos";
	$active= "none";
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!empty($detalles))
{
	$cuenta=$_SESSION["iduser"];
	$Notificacion = new Notificacion;
	$Notificacion->seeNotifications($cuenta);
	
	$Seguidor = new Seguidor;
	$coder -> encode($cuenta);
	$cuentaEncoded = $coder->encoded;
?>

<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
	<ul>
		<li >
			<a href="/perfil/?cuenta=<?=$cuentaEncoded?>">
				<span class="miPerfilText"> Mi Perfil</span>
				<img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user-menu">
			</a>
		</li>
		<li >
     		<a href="/perfil/seguidores/?cuenta=<?=$cuentaEncoded?>">
     			<span>Mis Seguidores</span> 
     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon">
     		</a>
     	</li>
		<li>
			<a href="/perfil/garage/?cuenta=<?=$cuentaEncoded?>"> 
				<span> Mis Garages</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon">
			</a>
		</li>
		<li>
			<a href="/perfil/autos/?cuenta=<?=$cuentaEncoded?>">
				<span> Mis Autos</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
			</a>
		</li>
		<li>
      		<a href="/perfil/docs/?cuenta=<?=$cuentaEncoded?>">
      			<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
      			<span> Mi Expediente</span> 
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
	<p>
		<a href="/anunciate" target="_blank">
			<img src="/img/ads/promo_ad/<?= rand(1,3)?>.png">
			Clic aqu&iacute;
		</a>
	</p>
</div>
<div class="content content-no-header followMenu" style="padding-bottom: 13px;">
	<ul class="followSubmenu">
		<li title="notificaciones" class="active notifSt" onclick="location.href='/notificaciones'">
				<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_NOTIFICACIONES_infotraffic.png" class="icon-follower"> 
				<span>Notificaciones</span>
		</li>
		<li title="siguendo" class="followSt" onclick="location.href='/siguiendo'">
				<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_SIGUIENDO_infotraffic.png" class="icon-following"> 
				<span>Siguiendo</span>
		</li>
	</ul>
</div>
<div class="siguiendo active content">
	
</div>
<script type="text/javascript" src="/js/notificacion.js?l=<?= LOADED_VERSION?>"></script>
<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
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
 ?>