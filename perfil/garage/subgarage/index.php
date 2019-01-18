<?php 


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
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder($_GET["cuenta"]);
$Garage = new Garage;
$detalles = array();
$Usuario = new Usuario;
$garageCurr=0;
if(isset($_GET["garage"])){
	$garageCurr=$_GET["garage"];
	$garage=$Garage->accountById($garageCurr);
}
$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$garageCurr));
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
$show=true;
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
if(isset($_GET["cuenta"]) && isset($_GET["garage"]))
{
	$cuenta = $_GET["cuenta"];
    $nombreCuenta= $Usuario->getGarage($_GET["garage"]);
	$garage = $Garage ->accountById($_GET["garage"]);
	if(empty($garage)){
		$show=false;
	}
	else{
		$garages = $Garage->accountByFather($_GET["garage"]);
		$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
		$detalles = $Garage -> getUserdetail($garage["user"]);
		$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
		$secretlessGarages = $Garage -> secretlessByFather($_GET["garage"]);
		if(empty($infoPerfil)){
			$show=false;
		}else{
			if(!empty($detalles))
			{
				$Version = new Version;
				$instancia = array();
				$llaveGarage = $_GET["garage"];
				$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
				$active="subgarage";
				$Like = new Like;
				if(!$owner && ((isset($garage["privacidad"])) ? $garage["privacidad"] : 1)==3  && !$following && !$Seguidor->acepted){ 
					$show=false;
				}
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
if($garage["user"]!=$_GET["cuenta"]){
	$show=false;
}
if($show){ 
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
	?>
		
	<div class="content">
		<div class=" col-md-12 col-xs-12 garages">
			<div class="title-garage">Garages en&ensp;"<?= $garage["nameAccount"]?>" Total: <?= ($owner) ? sizeof($garages) : sizeof($secretlessGarages)?></div>
		</div>
	
		<?php
		foreach ($garages as $llaveGarage => $garage)
		{		
			$numLikes = $garage["likesGarage"];
			$sharedGarage = $garage["sharedGarage"];
			//var_dump($garageContain);
			if(!$owner && $garage["type"]==3)
			{
				continue;
			} 
			else 
			{ 
				$followingGarage=array();
				if(!$owner)
				{
					$followingGarage=$seguidorGarages->followingTo($_SESSION["iduser"], $garage["idAccount"]);
				}
				?>

				<div class="row space">
					<div class="col-xs-12 list-garage">
			    		<div class="list-garage-header img-garage">
			    			<?php
			    			if(empty($garage["avatar"]))
			    			{ ?>
			    				<img onclick="showGarage($(this))" data-garage='<?= $garage["idAccount"]?>' data-usuario='<?= $cuenta?>' class="pointer img-profile" title="Garage vacio" src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" alt="">
			    			<?php	
			    			} else {?>
			    				<div class="">
			    					<img onclick="showGarage($(this))" data-garage='<?= $garage["idAccount"]?>' data-usuario='<?= $cuenta?>' class="pointer img-profile" title="<?= $garage["nameAccount"]?>" src="<?=$garage["avatar"]?>" alt="">
			    				</div>
			    			<?php } ?>
			    			<div class="head-info">
				    			<div class="personal" data-garage='<?= $llaveGarage?>'>
				    				<?= $garage["nameAccount"]?>
				    				<img src="<?= ($garage["type"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/LogIn_Movil_icono_candado_infotraffic.png" : (($garage["type"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_icon_Perfil-Pública_infotraffic.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_Ojo-INvisible_infotraffic.png") ?>" class="settings" alt="Privacidad">
				    			</div>
					    		<hr>
					    		<div class="time">
					    			<ul id="ulgarage">
				    					<?php 
				    					
				    					if(!$Like->alreadyLike($_SESSION["iduser"], 2, $garage["idAccount"])){ ?>
				    					<li class="pointer" <?= (!$owner && (empty($followingGarage) || (!empty($following) && $followingGarage["aceptado"]==0)) ) ? 'onclick="seguirPerfil('.$garage["idAccount"].', 2);like($(this), '.$garage["idAccount"].', 2)"' : 'onclick="like($(this), '.$garage["idAccount"].', 2)"'?>>
				    						<span class="likes-garages"><?= $numLikes?></span>
				    						<img data-toggle="modal" class="img-thumbnail icon-size" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
				    					</li>
				    					<?php }else{ ?>
			    						<li class="pointer" onclick="unlike($(this), <?=$garage["idAccount"]?>, 2)">
				    						<span class="likes-garages"><?= $numLikes?></span>
				    						<img data-toggle="modal" class="img-thumbnail icon-size" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
				    					</li>
				    					<?php } ?>
				    					<li class="navigation-icon pointer" onclick="shareThis($(this))">
				    						<span><?= ($sharedGarage>99) ? "+99" : $sharedGarage?></span>
				    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2<?= ($sharedGarage>0 ? "-SELECTED" : "")?>_infotraffic.png" alt="">
				    					</li>
				    				</ul>	
				    				<ul class="navigation-list">
										<li class="pointer" onclick="doShare($(this),2)" data-f="<?= $_GET["cuenta"] ?>" data-p="<?= $garage["idAccount"] ?>">En AVI cars</li>
										<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$_GET["cuenta"] ."&garage=".$garage["idAccount"]?>">En WhatsApp</li>
										<li class="pointer" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$_GET["cuenta"] ."&garage=".$garage["idAccount"]?>" onclick="copyShare(this,$(this))">Copiar link </li>
									</ul>	
					    		</div>
				    		</div>
			    		</div>
			    	</div>
			    </div>
			<?php
			}
		}
		?>
			
				
		</div>
	</div>
<?php
	
}
else
{
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