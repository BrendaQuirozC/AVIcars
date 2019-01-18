<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-05 17:41:56
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-13 11:36:10
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
if(empty($_POST)){
	header("Location: /");
}
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$Garage = new Garage;
$coder = new Coder();
$detalles = array();
$sess=true;
if(empty($_SESSION))
{
	$sess=false;
}
if(!isset($_SESSION["iduser"]))
{
	$sess=false;
}
if($_POST["desde"]===""){
	$_POST["desde"]=0;
}
if($_POST["hasta"]===""){
	$_POST["hasta"]=0;
}

$arrayAdvancedSearch=$_POST;
$jsonAdvancedSearch=json_encode($_POST);
if($sess)
{
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
    $nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $agrega = $Usuario -> agregando($nCuenta, $cuenta);
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
}
else{
	include ($_SERVER['DOCUMENT_ROOT']) . '/login/header.php';?>

  	<div class=" container-fluid main-container container-transparent">
	<?php
}

?>
		<div class="search <?= !isset($_SESSION["iduser"]) ? 'searchTop' : '' ?> sidebar sidebar-no-header sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
			<?php
			if(empty($_SESSION["iduser"]))
			{ ?>
				<p>
					<a href="/" target="_blank">
						<img src="/img/Banner_registro_AVI.png">
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
		<div class="search <?= !isset($_SESSION["iduser"]) ? 'searchMargin' : '' ?> content content-no-header content-no-margin nopadbott" style="margin-bottom: 100px;">
			<div id="results" class="search-content active">
				
			</div>
			
		</div>

		<script type="text/javascript">
			var l=0;
			var busqueda=JSON.parse('<?= $jsonAdvancedSearch ?>');
			busqueda.list=0;
			var search=true;
			function searchAdvanced(){
				$(".seemore").remove();
				xhr = new XMLHttpRequest();
			    var url = "search.php";
			    xhr.open("POST", url, false);
			    xhr.setRequestHeader("Content-type", "application/json");
			    xhr.onreadystatechange = function () { 
			    	if(this.status==200)
			        {
			            msg=this.response;
			            $("#results").append(msg);
			            search=true;
			            busqueda.list+=10;
			        }
			        else{
			        	search=false;
			        }
			    	
			    }
			    xhr.send(JSON.stringify(busqueda));
			    
			}
			$(document).ready(function(){
				searchAdvanced();
				
			});
		</script>
<?php 
if($sess)
{
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php'; 
}
else
{ ?>
	</div>
	<script type="text/javascript" src="/js/logoutsearch.js?l=<?= LOADED_VERSION?>"></script>
<?php include ($_SERVER['DOCUMENT_ROOT']) . '/login/footer.php';
}

?>