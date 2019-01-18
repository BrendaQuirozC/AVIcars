<?php

/**
 * @Author: BrendaQuiroz
 * @Date:   2019-01-11 16:50:46
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-16 10:50:30
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
$Garage = new Garage;
$Publicacion = new Publicacion;
$coder = new Coder();
$detalles = array();
$imgMeta="";
$tag = $_GET["src"];
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
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
}
else{
	include ($_SERVER['DOCUMENT_ROOT']) . '/login/header.php';?>

  	<div class=" container-fluid main-container container-transparent">
	<?php
}
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
		<small class="more">M&aacute;s de "<?= $_GET["src"]?>"</small>

	</div>
<?php 
} 
else { ?>
	<div class="sidebar sidebar-login hidden-xs visible-sm visible-md visible-lg" id="sidebar">
	    <ul>
	        <li>
	            <a href="/>">
	                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/etiqueta_infotraffic.png" class="icons-login"> 
	                <span class="login-s">Anuncia tu Auto</span>
	            </a>
	        </li>
	        <li>
	            <a href="https://seguros.apoyovial.app/" target="_blank">
	                <span class="login-s">Seguros cobertura amplia</span> 
	                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/seguro_infotraffic_40px.png" class="icons-login"> 
	            </a>
	        </li>
	        <li>
	            <a href="/"> 
	                <span class="login-s">Pr&eacute;stamo inmediato</span> 
	                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/moneda_infotraffic.png" class="navigation-icon">
	            </a>
	        </li>
	        <li>
	            <a href="/">
	                <span class="login-s">Compra inmediata</span> 
	                <img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/billete_infotraffic.png" class="icons-login" > 
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

	<div class="sidebar sidebar-login sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
	    <?php
	    if(empty($_SESSION["iduser"]))
	    { ?>
	        <p class="pointer">
	            <img data-toggle="modal" data-target="#inicieSesion" src="/img/Banner_registro_AVI.png">
	        </p>
	    <?php } ?>
	    <p>
	        <a class="perfilpromo" href="/anunciate" target="_blank">
	            <img src="/img/ads/promo_ad/<?= rand(1,3)?>.png">
	            Clic aqu&iacute;
	        </a>
	    </p>
	</div>
	<div class="content" id="posts">
	</div>
<?php } ?>
<script type="text/javascript">
	var lastPost=0;
    var search=true;
    var s="src";
    var u='<?= $_GET["src"]?>';
</script>
<script type="text/javascript" src="/js/timeline.js?l=<?= LOADED_VERSION?>"></script>
<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>