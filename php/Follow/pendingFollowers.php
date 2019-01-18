<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-13 16:05:12
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:58:26
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["usertkn"])){
	header('HTTP/1.1 403 Forbidden');
}

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
switch ($_POST["t"]) {
	case 1:
		if($_SESSION["usertkn"]!=$_POST["tgt"]){
			$show=false;
		}
		break;
	case 2:
		$garage=$Garage->accountById($cuenta);
		if($_SESSION["iduser"]!=$garage["user"]){
			$show=false;
		}
		break;
	case 3:
		$instance=$Instancia->getInfoinstance($cuenta);
		if($_SESSION["iduser"]!=$instance["idUser"]){
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
$pendingFollower = $Seguidor -> wantFollowBy($cuenta,$_POST["t"],$_POST["c"]);
$k=0;
foreach ($pendingFollower as $pf => $futureFollower) {
	$k++;
	$coder->encode($futureFollower["seguidor"]);
	$seguidorCoded=$coder->encoded;
	?>
	<div class="people followRequest">
		<img class="userFollowing" onclick="window.location.href='/perfil/?cuenta=<?=$seguidorCoded?>'" src="<?= isset($futureFollower['avatar']) ? $futureFollower['avatar'] : '/img/icons/avatar1.png' ?>" alt="Avatar">
		<div class="text-left no-pading seguidor-top">
			</a>
			<b onclick="window.location.href='/perfil/?cuenta=<?=$seguidorCoded?>'"><?= $futureFollower["nombre"]?>&ensp;<?=$futureFollower["apellido"]?></b>
			<hr>
				<a class="accept btn btn-avi" <?= ($_POST["t"]==3) ? 'data-auto="'.$cuenta.'"' : ""?> <?= ($_POST["t"]==2) ? 'data-garage="'.$cuenta.'"' : ""?> onclick="confirmFollower($(this),'<?=$seguidorCoded?>',<?= $_POST["t"]?>)" >
					<span  id="followersPage">Confimar</span>
				</a>
				<a class="reject btn btn-avi-white" <?= ($_POST["t"]==3) ? 'data-auto="'.$cuenta.'"' : ""?> <?= ($_POST["t"]==2) ? 'data-garage="'.$cuenta.'"' : ""?> onclick="rejectFollower($(this),'<?=$seguidorCoded?>',<?= $_POST["t"]?>)" >
					Rechazar
				</a>
				<?php
				if($Seguidor->alreadyFollowing($_SESSION["iduser"], $futureFollower["seguidor"], 1))
				{ ?>
					<a class="unfollow hidden" onclick="unfollowingFollower($(this),'<?=$seguidorCoded?>','<?=$typeFollow?>')" >
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
						<span id="followersPage">Siguiendo</span>
					</a>
		     	<?php 
		     	} elseif($Seguidor->alreadyFollowing($_SESSION["iduser"], $futureFollower["seguidor"], 0))
		     	{ ?>
			     	<a class="unfollow hidden " onclick="unfollowingFollower($(this),'<?=$seguidorCoded?>','<?=$typeFollow?>')">
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
						<span  id="followersPage">Solicitud enviada</span>
					</a>
				<?php 
				} else
				{ ?>
					<a class="unfollow hidden" <?= $privacidad==2 ? 'onclick="followingFollower($(this),\''.$seguidorCoded.'\', '.$typeFollow.')"': 'onclick="followingFollower($(this),\''.$seguidorCoded.'\','.$typeFollow.')"'?>>
						<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png">
						<span  id="followersPage">Seguir</span>
					</a>
				<?php 
				} ?>
			<b class="city"><?= $futureFollower["city"]?></b>
		</div>
	</div>
	</div>
<?php
	
}
if($k>0){
?>

<div class="people viewingFollowings seemore  text-center" onclick="getPendingFollowers(tgtFollowing);">
	Ver M&aacute;s
</div>
<?php } ?>