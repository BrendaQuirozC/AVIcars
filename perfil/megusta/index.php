<?php 
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';

require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
$Garage = new Garage;
$detalles = array();
if(isset($_GET["cuenta"]))
{
	$cuenta = $_GET["cuenta"];
	$Usuario = new Usuario;
    $nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $agrega = $Usuario -> agregando($nCuenta, $cuenta);	
	$detalles = $Garage -> getUserdetail($cuenta);
}
if(!empty($detalles))
{
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
?>
<div class="header">
	<div class="header-profile ">
	  <img src="/img/icons/avatar1.png" alt="Avatar" class="img-thumbnail img-responsive avatar">
	</div>	
	<nav class="navbar navbar-default">
		<div class="col-xs-12 text-right">
			<a href="#">
				<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" class="privacy" alt="">
			</a>
		</div>
		<div class="col-xs-12 text-right">
			<a href="/perfil/edit/cuenta/index.php">
				<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_llave_infotraffic.png" class="settings" alt="configurar perfil">
			</a>
		</div>
		<div class="container-fluid navbar-profile header-list">
		    <ul class="nav navbar-nav navbar-padding">
		      <li><a href="/perfil/?cuenta=<?=$cuenta?>"><img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_miTimeline_infotraffic.png" class="newspaper-width"> <span> Timeline</span></a></li>
		      <li><a href="/perfil/garage/?cuenta=<?=$cuenta?>"><img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="icon-width"> <span> Mis Garages</span> </a></li>
		      <li><a href="/perfil/autos/?cuenta=<?=$cuenta?>"><img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="icon-width"> <span> Mis Autos</span> </a></li>
		      <li class="active"><a href="#"><img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_meGustaRecibidos_infotraffic.png" class="icon-width"> <span> Seguidores</span> </a></li>
		      <li><a href="/perfil/mensajes/?cuenta=<?=$cuenta?>"><img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_mensajes_infotraffic.png" class="icon-width"> <span> Mensajes</span> </a></li>
		    </ul>
		</div>
	</nav>
</div>
<?php
	// botones de usuario y arbol
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/usrHeader.php';
?>

<div class="content col-xs-8 col-md-8">
	<div class="row">
	    <div class="col-xs-12">
	    	<div class="col-xs-4 col-sm-2 no-pading">
	    		<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_seleccion_favoritosl_infotraffic.png" class="img-thumbnail icon-big-size" alt="">

	    	</div>
	    	<div class="col-xs-6 col-sm-8 text-left no-pading">
				<div class="text-left publication-top"><h5>Erik&ensp;Fernando</h5></div>
				<div class="text-left" style="font-size: 10px;">Le gusta tu publicaci&oacute;n <b>Se vende</b></div>
			</div>
			<div class="col-xs-2 col-sm-2 no-pading">
				<img src="/img/icons/avatar1.png" alt="Avatar" class="img-thumbnail avatar publication-img icon-big-size" style="float: right;">
			</div>
	    </div>
	</div>
	<div class="row space">
	    <div class="col-xs-12">
	    	<div class="col-xs-4 col-sm-2 no-pading">
	    		<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_seleccion_favoritosl_infotraffic.png" class="img-thumbnail icon-big-size" alt="">
	    	</div>
	    	<div class="col-xs-6 col-sm-8 text-left no-pading">
				<div class="text-left publication-top"><h5>Brenda y 2 personas m&aacute;s </h5></div>
				<div class="text-left" style="font-size: 10px;">Le gusta tu garage <b>Lomas verdes</b></div>
			</div>
			<div class="col-xs-2 col-sm-2 no-pading">
				<img src="/img/icons/avatar1.png" alt="Avatar" class="img-thumbnail avatar publication-img icon-big-size" style="float: right;">
			</div>
	    </div>
	</div>
	<div class="row space">
	    <div class="col-xs-12">
	    	<div class="col-xs-4 col-sm-2 no-pading">
	    		<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_home_publicacion_comentarios_infotraffic.png" class="img-thumbnail  icon-big-size" alt="">
	    	</div>
	    	<div class="col-xs-6 col-sm-8 text-left no-pading">
				<div class="text-left publication-top"><h5>Erik&ensp;Fernando</h5></div>
				<div class="text-left" style="font-size: 10px;">Coment&oacute; tu publicaci&oacute;n <b>Se vende</b></div>
			</div>
			<div class="col-xs-2 col-sm-2 no-pading">
				<img src="/img/icons/avatar1.png" alt="Avatar" class="img-thumbnail avatar publication-img icon-big-size" style="float: right;">
			</div>
	    </div>
	</div>
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
 ?>
 <?php
 }
 else {
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