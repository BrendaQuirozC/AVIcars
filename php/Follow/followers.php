<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-13 16:05:12
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-17 09:25:05
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once($_SERVER['DOCUMENT_ROOT']).'/php/Instancia/Instancia.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
$Seguidor=new Seguidor;
$coder = new Coder();
$Usuario = new Usuario;
$Garage = new Garage;
$Instancia = new Instancia;
$coder->decode($_POST["tgt"]);
$cuenta=$coder->toEncode;
$show=true;
if(!isset($_SESSION["usertkn"])){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
switch ($_POST["t"]) {
	case 1:
		if(!$Usuario->UserAccessToUser($_SESSION["iduser"],$cuenta)){
			$show=false;
		}
		break;
	case 2:
		if(!$Garage->UserAccessToGarage($_SESSION["iduser"],$cuenta)){
			$show=false;
		}
		break;
	case 3:
		if(!$Instancia->UserAccessToInstance($_SESSION["iduser"],$cuenta)){
			$show=false;
		}
		break;
	default:
		$show=false;
		break;
}
if(!$show){
	header('HTTP/1.1 403 Forbidden');
}
$follower = $Seguidor -> seguidores($cuenta,$_POST["t"],$_POST["c"]);
$k=0;
foreach ($follower as $f => $userFollower) 
{ 
	$k++;
	$coder->encode($userFollower["seguidor"]);
	$seguidorCoded=$coder->encoded;
	if($Usuario->UserAccessToUser($_SESSION["iduser"],$userFollower["seguidor"])||$userFollower["privacidad"]==1||$userFollower["privacidad"]==2){ ?>
	<div class="people viewingFollowingsProfile">
		<img class="userFollowing" onclick="window.location.href='/perfil/?cuenta=<?=$seguidorCoded?>'" src="<?= isset($userFollower['avatar']) ? $userFollower['avatar'] : '/img/icons/avatar1.png' ?>" alt="Avatar">
		<div class="text-left no-pading seguidor-top">
			<b onclick="window.location.href='/perfil/?cuenta=<?=$seguidorCoded?>'"><?= $userFollower["nombre"]?>&ensp;<?=$userFollower["apellido"]?></b>
			<hr>
			<?php
			if($Seguidor->alreadyFollowing($_SESSION["iduser"], $userFollower["seguidor"], 1))
			{ ?>
				<?php if($userFollower["privacidad"] == 1){ ?>
				<a class="unfollow unfollow-profile<?= $userFollower['seguidor']?>" data-elemento="<?= $userFollower['seguidor']?>" data-toggle='modal' data-target='#Modal_private_p_followers' onclick="enviarDatosModalPerfilSeguidores($(this),'<?=$seguidorCoded?>',1,'<?=$userFollower['nombre']?>','<?=$userFollower['apellido']?>')" >
					<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
					<span id="followersPage">Siguiendo</span>
				</a>
				<?php } ?>
				<?php if($userFollower["privacidad"] == 2){ ?>
				<a class="unfollow unfollow-profile<?= $userFollower['seguidor']?>" data-elemento="<?= $userFollower['seguidor']?>" data-toggle='modal' data-target='#Modal_public_p_followers' onclick="enviarDatosModalPerfilSeguidores($(this),'<?=$seguidorCoded?>',1,'<?=$userFollower['nombre']?>','<?=$userFollower['apellido']?>')" >
					<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
					<span id="followersPage">Siguiendo</span>
				</a>
				<?php } ?>
				<?php if($userFollower["privacidad"] == 3){ ?>
				<a class="unfollow unfollow-profile<?= $userFollower['seguidor']?>" data-elemento="<?= $userFollower['seguidor']?>" data-toggle='modal' data-target='#Modal_secret_p_followers' onclick="enviarDatosModalPerfilSeguidores($(this),'<?=$seguidorCoded?>',1,'<?=$userFollower['nombre']?>','<?=$userFollower['apellido']?>')" >
					<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
					<span id="followersPage">Siguiendo</span>
				</a>
				<?php } ?>
	     	<?php 
	     	} elseif($Seguidor->alreadyFollowing($_SESSION["iduser"], $userFollower["seguidor"], 0))
	     	{ ?>
		     	<a class="unfollow" onclick="unfollowingFollower($(this),'<?=$seguidorCoded?>',1)">
					<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
					<span  id="followersPage">Solicitud enviada</span>
				</a>
			<?php 
			}
			elseif($userFollower["seguidor"]==$_SESSION["iduser"])
			{ } 
			elseif($_SESSION["iduser"]==0)
	     	{ ?>
				<img class="follow-icon-login" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png">
			<?php 
			}
			else
			{ ?>
				<a class="unfollow" <?= $userFollower["privacidad"]==2 ? 'onclick="followingFollower($(this),\''.$seguidorCoded.'\', 1)"': 'onclick="followingFollower($(this),\''.$seguidorCoded.'\',1)"'?>>
					<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png">
					<span  id="followersPage">Seguir</span>
				</a>
			<?php 
			} ?>
			<b class="city"><?= $userFollower["city"]?></b>
		</div>
	</div>
<?php
	}
}
if($k>0){
?>
<div class="people viewingFollowings seemore  text-center" onclick="getFollowers(tgtFollowing);">
	Ver m&aacute;s
</div>
<?php } ?>