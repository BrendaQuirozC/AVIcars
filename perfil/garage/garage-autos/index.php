<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/01/2018
 * Time: 12:54 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["garage"])){
	header("Location: /");
}
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/login/address.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$garageEncoded=$_GET["garage"];
$coder->decode($_GET["garage"]);
$_GET["garage"]=$coder->toEncode;
$address = new Address;
$Garage = new Garage;
$detalles = array();
$Usuario = new Usuario;
$garageCurr=0;
$privacyGarage = $Garage -> getGarageInfo($_GET["garage"]);
if(($privacyGarage["usoId"]==4) || ($privacyGarage["usoId"]==6)) {
	header("Location: /");
}
if(isset($_GET["garage"])){
	$garageCurr=$_GET["garage"];
	$garage=$Garage->accountById($garageCurr);
}
$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$garageCurr));
$show=true;
if(!isset($_SESSION["iduser"]) && $garage["privacidad"]==2){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
$colaboradorCont=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],3);
if(isset($_GET["cuenta"]) && isset($_GET["garage"]))
{
	$cuenta = $_GET["cuenta"];
    $nombreCuenta= $Usuario->getGarage($_GET["garage"]);
	$garage = $Garage ->accountById($_GET["garage"]);
	if(empty($garage)){
		$show=false;
	}
	else
	{
		$garages = $Garage -> account($garage["user"]);
		$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
		$detalles = $Garage -> getUserdetail($garage["user"]);
		$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
		if(empty($infoPerfil)){
			$show=false;
		}else{
			if(!empty($detalles))
			{
				$Version = new Version;
				$instancia = array();
				$llaveGarage = $_GET["garage"];
				$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
				$privacyGarage = $Garage -> getGarageInfo($_GET["garage"]);
				$active="autos";
				$Like = new Like;
			}
			else{
				$show=false;
			}
		}
	}
}
else{
	$show=false;
}
if(isset($garage))
{
	if($garage["user"]!=$_GET["cuenta"]){
		$show=false;
	}
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
	if(!$owner && ((isset($garage["privacidad"])) ? $garage["privacidad"] : 1)==3  && !$following && !$Seguidor->acepted|| $blocked){ 
		$show=false;
	}
	else
	{
		$metasShare=array(
			"og"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
			    "site_name" => "AVI cars",
			    "type" => "website"
			),
			"tw"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
			    "image:alt" => "AVI cars",
			    "card" => "summary_large_image"
			)
		);
	}
}
if($show)
{

	include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
	$garageContain = $Garage ->accountInstancia($_GET["garage"],(($owner) ? true : (($following) ? true : false)));
	$secretlessGarageContain = $Garage -> secretlessAccountInstancia($_GET["garage"]);
			
		?>
		<div class="content" id="autosContent">

		<?php
		if($_SESSION["iduser"]==$cuenta||$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],2)){ ?>
			<button class="btn cuenta-btns btn-block text-center " type="button" onclick="newCarModal($(this))"> 
				<?= (empty($garageContain)) ? "A&ntilde;ade tu primer auto aqu&iacute;" : "A&ntilde;ade un auto aqu&iacute;"?>
			</button>
    
		<?php }
		if(empty($garageContain))
		{ ?>
			<h3 class="text-center">A&uacute;n no hay autos disponibles.</h3>
			<?php
		}
		else
		{ ?>
			<h5>Total de autos en este garage: <?=($owner) ? sizeof($garageContain) : sizeof($secretlessGarageContain)?></h5>
			<?php
		}
		?>
		</div>
		<script type="text/javascript">
			var c='<?= $cuentaEncoded ?>';
			var acc='<?= $garageEncoded ?>';
		</script>
		<script type="text/javascript" src="/js/autos.js?l=<?= LOADED_VERSION?>"></script>
		<?php
}
else
{
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>