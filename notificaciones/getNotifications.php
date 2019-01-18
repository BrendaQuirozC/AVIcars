<?php

/**
 * @Author: erikfer94
 * @Date:   2018-09-13 17:39:12
 * @Last Modified by:   Brenda Quiroz
 * @Last Modified time: 2018-11-15 16:19:20
*/
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(!isset($_SESSION["usertkn"])){
	header('HTTP/1.1 403 Forbidden');
}
require_once($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicationDate.php';
date_default_timezone_set('America/Mexico_City');
$coder=new Coder;
$Notificacion = new Notificacion;
$getNotif = $Notificacion -> getNotificationByIdUser($_SESSION["iduser"],$_POST["ord"]);
$k=0;
$Seguidor = new Seguidor;
foreach ($getNotif as $n => $newNotification) 
{
	$k++;
	$coder -> encode($newNotification["idRemitente"]);
	$notificationUser = $coder->encoded;
	 ?>
<div class="people notifRequest">
	<img class="userFollowing" onclick="window.location.href='/perfil/?cuenta=<?=$notificationUser?>'" src="<?= isset($newNotification['avatarRemitente']) ? $newNotification['avatarRemitente'] : '/img/icons/avatar1.png' ?>" alt="Avatar">
	<div class="text-left no-pading seguidor-top">
		<b onclick="window.location.href='/perfil/?cuenta=<?=$notificationUser?>'"><?= $newNotification["userName"]?> <?= $newNotification["userLastName"]?></b>
		<span class="time" title="<?= date("M d,Y H:i\h\\r\\s",strtotime($newNotification["fecha"])) ?>">
			<?= hace(strtotime($newNotification["fecha"])) ?>
		</span>
		<hr>
		<div class="down row">
			<?php
			if($newNotification["idTipo"]==12 || $newNotification["idTipo"]==13 || $newNotification["idTipo"]==24)
				{ ?>
				<a class="accept btn btn-avi" onclick="confirmFollower($(this),'<?=$notificationUser?>', <?= ($newNotification["idTipo"]==12) ? 1 : (($newNotification["idTipo"]==13) ? 2 : (($newNotification["idTipo"]==24) ? 3 : "")) ?>)" <?= ($newNotification["idTipo"]==13) ? 'data-garage="'.$newNotification["idGarage"].'"' : (($newNotification["idTipo"]==24) ? 'data-auto="'.$newNotification["idAuto"].'"' : '')?> >
					<span  id="followersPage">Confirmar</span>
				</a>
				<a class="reject btn btn-avi-white" onclick="rejectFollower($(this),'<?=$notificationUser?>',<?= ($newNotification["idTipo"]==12) ? 1 : (($newNotification["idTipo"]==13) ? 2 : (($newNotification["idTipo"]==24) ? 3 : "")) ?>)" <?= ($newNotification["idTipo"]==13) ? 'data-garage="'.$newNotification["idGarage"].'"' : (($newNotification["idTipo"]==24) ? 'data-auto="'.$newNotification["idAuto"].'"' : '')?>  >
					Rechazar
				</a>
				<?php
				if($Seguidor->alreadyFollowing($_SESSION["iduser"], $newNotification["idRemitente"], 1))
				{ ?>
					<a class="unfollow hidden" onclick="unfollowingFollower($(this),'<?=$notificationUser?>',1)" >
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
						<span id="followersPage">Siguiendo</span>
					</a>
		     	<?php 
		     	} elseif($Seguidor->alreadyFollowing($_SESSION["iduser"], $newNotification["idRemitente"], 0))
		     	{ ?>
			     	<a class="unfollow hidden" onclick="unfollowingFollower($(this),'<?=$notificationUser?>',1)">
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
						<span  id="followersPage">Solicitud enviada</span>
					</a>
				<?php 
				} else
				{ ?>
					<a class="unfollow hidden" onclick="followingFollower($(this),'<?=$notificationUser?>',1)">
						<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png">
						<span  id="followersPage">Seguir</span>
					</a>
				<?php 
				} ?>
			<?php } ?>
			<b id="notifTexto" onclick="window.location.href='<?= $newNotification["url"]?>'" class="city col-xs-9"><?= $newNotification["tipo"]?>
				<?= isset($newNotification['texto']) ? ': '.$newNotification['texto'] : '' ?>	
			</b>
		</div>
	</div> 
</div>
<?php
}
if($k>0){
?>

<div class="people viewingFollowings seemore  text-center" onclick="getNotification();">
	Ver M&aacute;s
</div>
<?php } 
?>