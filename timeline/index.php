<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-18 13:22:33
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-14 12:23:57
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Garage = new Garage;
$share=new Share;
$detalles = array();
if(isset($_SESSION["iduser"]))
{
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
    //$nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
    $privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!empty($detalles))
{
	$Like = new Like;
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$publicacion=new Publicacion;
	$active="timeline";
	$cuenta=$_SESSION["iduser"];
	$coder->encode($cuenta);
	$cuentaCoded=$coder->encoded;
	?>
<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
	<ul>
		<li class="<?= ($active=="profile") ? "active" : ""?>">
			<a href="/perfil/?cuenta=<?=$cuentaCoded?>">
				<span class="miPerfilText"> Mi Perfil</span>
				<img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user-menu">
			</a>
		</li>
		<li class="<?= ($active=="seguidos") ? "active" : ""?>">
     		<a href="/perfil/seguidores/?cuenta=<?=$cuentaCoded?>">
     			<span>Mis Seguidores</span> 
     			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tire-off.png" class="navigation-icon"> 
     		</a>
     	</li>
		<li class="<?= ($active=="garage") ? "active" : ""?>">
			<a href="/perfil/garage/?cuenta=<?=$cuentaCoded?>"> 
				<span> Mis Garages</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon">
			</a>
		</li>
		<li class="<?= ($active=="auto") ? "active" : ""?>">
			<a href="/perfil/autos/?cuenta=<?=$cuentaCoded?>">
				<span> Mis Autos</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
			</a>
		</li>
		<li class="<?= ($active=="docs") ? "active" : ""?>">
			<a href="/perfil/docs/?cuenta=<?=$cuentaCoded?>">
				<span> Mi Expediente</span> 
				<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
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
<div class="content content-no-header" id="posts">

</div>
<script type="text/javascript">
	var lastPost=0;
	var search=true;
	var s="u";
	var u='<?= $cuentaCoded?>';
</script>
<script type="text/javascript" src="/js/timeline.js?l=<?= LOADED_VERSION?>"></script>
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
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 }
 	include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
 ?>
