<?php 
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$Garage = new Garage;
$detalles = array();
if(isset($_SESSION["iduser"]))
{
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
	$coder = new Coder();
    $nombreCuenta= $Usuario->getGarage();
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
	$follows="seguidos";
	$active= "none";
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!empty($detalles))
{
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$cuenta=$_SESSION["iduser"];
	$Seguidor = new Seguidor;
	$following = $Seguidor -> siguiendo($cuenta);
	$coder -> encode($cuenta);
	$cuentaEncoded = $coder->encoded;
?>

<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
	<ul>
		<li>
			<a href="/perfil/?cuenta=<?=$cuentaEncoded?>">
				<span class="miPerfilText"> Mi Perfil</span>
				<img src="<?= isset($imgPerfilOwnCuenta["avatar"]) ? $imgPerfilOwnCuenta["avatar"] : "/img/icons/avatar1.png" ?>" class="icon-width active-perfil icon-user-menu">
			</a>
		</li>
		<li>
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
		<li >
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
<div class="content content-no-header followMenu" style="padding-bottom: 2px;">
	<ul class="followSubmenu">
		<li title="notificaciones" class="notifSt"  onclick="location.href='/notificaciones'">
				<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_NOTIFICACIONES_infotraffic.png" class="icon-follower"> 
				<span>Notificaciones</span>
		</li>
		<li title="siguiendo" class="active followSt" onclick="location.href='/siguiendo'">
				<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_SIGUIENDO_infotraffic.png" class="icon-following"> 
				<span>Siguiendo</span>
		</li>
	</ul>
	<div class="menu nav navbar-nav navbar-padding">
		<ul>
			<li id="personas" data-target="personas" class="followingOp active">
				<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_personas.png">
				<span>Perfiles</span>
			</li>
			<li data-target="garages" class="followingOp ">
				<img class="followgarages" src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_garage.png">
				<span>Garages</span>
			</li>
			<li data-target="autos" class=" followingOp">
				<img  src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_autos.png">
				<span>Autos</span>
			</li>
			<li data-target="anuncios" class="followingOp">
				<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/vendeTuAuto_180px.png">
				<span>Anuncios</span>
			</li>
			<li data-target="publicacion" class="followingOp">
				<img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_publicaciones.png">
				<span>Posts</span>
			</li>
		</ul>
	</div>
</div>
<div class="siguiendo content active" data-content="personas">
	<p>Perfiles</p>
	<?php
	$k=0;
	foreach ($following as $f => $userFollowed)
	{ 
		$k++;
		$coder -> encode($userFollowed["siguiendo"]);
		$userFollowedCode = $coder->encoded; ?>
	<div class="people viewingFollowings" data-test="no">
		<img class="userFollowing" onclick="window.location.href='/perfil/?cuenta=<?=$userFollowedCode?>'" src="<?= isset($userFollowed['avatar']) ? $userFollowed['avatar'] : '/img/icons/avatar1.png' ?>" alt="Avatar">
		<div class="text-left no-pading seguidor-top">
			<b onclick="window.location.href='/perfil/?cuenta=<?=$userFollowedCode?>'"><?= $userFollowed["nombre"]?>&ensp;<?=$userFollowed["apellido"]?></b>
			<hr>
			<!--Valida si el perfil del usuario es privado, si lo es, abre la modal que le corresponde-->
			<?php if($userFollowed['privacidad'] == 1){ ?>
			<a class="unfollow unfollow-profile<?= $userFollowed['siguiendo']?>" data-elemento="<?= $userFollowed['siguiendo']?>" onclick="enviarDatosModalPerfil($(this),'<?=$userFollowedCode?>',1,'<?= $userFollowed["nombre"]?>','<?= $userFollowed["apellido"]?>');" data-toggle='modal' data-target='#Modal_private_p'>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
				Dejar de Seguir
			</a>
			<?php } ?>
			<!--Valida si el perfil del usuario es público, si lo es, abre la modal que le corresponde-->
			<?php if($userFollowed['privacidad'] == 2){ ?>
			<a class="unfollow unfollow-profile<?= $userFollowed['siguiendo']?>" data-elemento="<?= $userFollowed['siguiendo']?>" onclick="enviarDatosModalPerfil($(this),'<?=$userFollowedCode?>',1,'<?= $userFollowed["nombre"]?>','<?= $userFollowed["apellido"]?>');" data-toggle='modal' data-target='#Modal_public_p'>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
				Dejar de Seguir
			</a>
			<?php } ?>
			<!--Valida si el perfil del usuario es secreto, si lo es, abre la modal que le corresponde-->
			<?php if($userFollowed['privacidad'] == 3){ ?>
			<a class="unfollow unfollow-profile<?= $userFollowed['siguiendo']?>" data-elemento="<?= $userFollowed['siguiendo']?>" onclick="enviarDatosModalPerfil($(this),'<?=$userFollowedCode?>',1,'<?= $userFollowed["nombre"]?>','<?= $userFollowed["apellido"]?>');" data-toggle='modal' data-target='#Modal_secret_p'>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
				Dejar de Seguir
			</a>
			<?php } ?>
			<b class="city"><?= $userFollowed["city"]?></b>
		</div>
	</div>
	<?php
	} ?>
	<div class="people viewingFollowings seemore  text-center" onclick="getFollowers($(this))" data-target="personas">
		Ver m&aacute;s
	</div>
</div>
<div class="siguiendo content" data-content="garages">
	<p>Garages</p>
</div>
<div class="siguiendo content" data-content="autos">
	<p>Autos</p>
</div>
<div class="siguiendo content" data-content="anuncios">
	<p>Anuncios</p>
</div>
<div class="siguiendo content" data-content="publicacion">
	<p>Posts</p>
</div>
<script type="text/javascript" src="/js/siguiendo.js?l=<?= LOADED_VERSION?>"></script>
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
