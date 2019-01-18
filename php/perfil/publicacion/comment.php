<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-11 14:21:44
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-21 12:14:03
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicationDate.php';
date_default_timezone_set('America/Mexico_City');
$coder = new Coder();
$coder->decode($_POST["p"]);
$postCoded=$coder->toEncode;
$coder->decode($_POST["el"]);
$userComment=$coder->toEncode;
$publicacion=new Publicacion;
session_start();
$ret=array();
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/usuario.php';
$usuario=new Usuario;
if($usuario->getStatusUser($_SESSION["iduser"])==3){
	$resp["Error"]=true;
}
elseif($publicacion->UserAccessToPublication($_SESSION["iduser"],$postCoded))
{
	$garage=null;
	if($_POST["t"]==1){
		if($userComment==$_SESSION["iduser"]){
			if($total=$publicacion->comment($postCoded,$_POST["c"],$_SESSION["iduser"],$garage)){
				$ownerPublicacion = $publicacion -> getUserOfWhereIComment($postCoded,$_SESSION["iduser"]);
				$Notificacion = new Notificacion;
				if($_SESSION["iduser"]!=$ownerPublicacion["authorPub"]){
					$Notificacion -> addNotification(8,$_POST["c"] , $_SESSION["iduser"], $ownerPublicacion["authorPub"], NULL, NULL, "/post/?p=".$_POST["p"], 1,true);
				}
				if($ownerPublicacion["authorPub"]!=$ownerPublicacion["perfilPub"]&&$_SESSION["iduser"]!=$ownerPublicacion["perfilPub"]){
					$Notificacion -> addNotification(8,$_POST["c"] , $_SESSION["iduser"], $ownerPublicacion["perfilPub"], NULL, NULL, "/post/?p=".$_POST["p"], 1,true);
				}
				
				$ret["Success"]=$total;
			}
			else{
				$ret["Error"]=true;
			}
		}
		else{
			$ret["Error"]=true;
		}
	}
	elseif ($_POST["t"]==2) {
		$avialableCommentors=$publicacion->getAvialableCommentors($_SESSION["iduser"],$postCoded);
		$post=false;
		if($avialableCommentors){
			foreach ($avialableCommentors as $ac => $commentor) {
				if($userComment==$commentor["id"]){
					$post=true;
				}
			}
		}
		if($post){
			$garage=$userComment;
			if($total=$publicacion->comment($postCoded,$_POST["c"],$_SESSION["iduser"],$garage)){
				$ownerPublicacion = $publicacion -> getUserOfWhereIComment($postCoded,$_SESSION["iduser"]);
				$Notificacion = new Notificacion;
				if($_SESSION["iduser"]!=$ownerPublicacion["authorPub"]){
					$Notificacion -> addNotification(8,$_POST["c"] , $_SESSION["iduser"], $ownerPublicacion["authorPub"],NULL, NULL,  "/post/?p=".$_POST["p"], 1,true);
				}
				if($ownerPublicacion["authorPub"]!=$ownerPublicacion["perfilPub"]&&$_SESSION["iduser"]!=$ownerPublicacion["perfilPub"]){
					$Notificacion -> addNotification(8,$_POST["c"] , $_SESSION["iduser"], $ownerPublicacion["perfilPub"], NULL, NULL, "/post/?p=".$_POST["p"], 1,true);
				}
				$ret["Success"]=$total;
			}
			else{
				$ret["Error"]=true;
			}
		}
		else{
			$ret["Error"]=true;
		}
	}
	else{
		$ret["Error"]=true;
	}
	
}
else{ 
	$ret["Error"]=true;
} 
if(isset($ret["Success"])){ 
	$comment=$publicacion->getComment($ret["Success"]);

	$coder->encode($comment['idComment']);
	$idCommentEncoded=$coder->encoded;
	$coder->encode($comment["authorUser"]);
	$autorCoded=$coder->encoded;
	$coder->encode($comment["authorGarage"]);
	$garageCoded=$coder->encoded;
?>
<li class="comment" data-comment="<?= $idCommentEncoded ?>">
	<a href="<?= ($comment["type"]==1) ? "/perfil/?cuenta=".$autorCoded : "/perfil/garage/timeline/?cuenta=".$autorCoded."&garage=".$garageCoded ?>"><img src="<?= ($comment["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $comment["imgAuthor"] ?>"></a>
	<h5><a href="<?= ($comment["type"]==1) ? "/perfil/?cuenta=".$autorCoded : "/perfil/garage/timeline/?cuenta=".$autorCoded."&garage=".$garageCoded ?>"><?= $comment["author"] ?></a></h5>
	<p><?= $comment["comentario"]?></p>
	<div class="dropdown edit-publication">
		<a class="btn dropdown-toggle edit-publication" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png" class="options">
		</a>
		<ul class="dropdown-menu list-gird-comment publication-list" aria-labelledby="dropdown">
			<li><a class="pointer" onclick="editComment($(this))">Editar </a> </li>
			<li><a class="pointer" onclick="modalToDeleteCom('<?= $idCommentEncoded ?>')">Eliminar</a> </li>
		</ul>
	</div>
	<span class="time" title="<?= date("M d, Y - H:i",strtotime($comment["hora"]))?>">
		<?=($comment["modificada"]!=NULL) ? " - editado ".hace(strtotime($comment["modificada"])) :  hace(strtotime($comment["hora"]))?>
	</span>
</li>
<?php }
?>