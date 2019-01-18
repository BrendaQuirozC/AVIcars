<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-06-28 16:10:06
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-21 12:18:19
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/auto/Anuncio.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/notification/Notification.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicationDate.php';
date_default_timezone_set('America/Mexico_City');
$ret=array();
$anuncio = new Anuncio;
$garage=null;
$coder = new Coder();
$coder->decode($_POST["p"]);
$anuncioid=$coder->toEncode;
session_start();
if($_POST["t"]==1){
	if($_POST["el"]==$_SESSION["iduser"]){
		if($comment=$anuncio->commentAd($anuncioid,$_POST["c"],$_SESSION["iduser"],$garage)){
			$ownerPublicacion = $anuncio -> getOwnerAd($anuncioid);
			$Notificacion = new Notificacion;
			if($_SESSION["iduser"]!=$ownerPublicacion["owner"]){
				$Notificacion -> addNotification(23,$_POST["c"] , $_SESSION["iduser"], $ownerPublicacion["owner"], NULL, NULL,"/anuncio/?a=".$_POST["p"], 1,true);
			}
			$ret["Success"]=$comment;
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
	$garage=$_POST["el"];
	if($comment=$anuncio->commentAd($anuncioid,$_POST["c"],$_SESSION["iduser"],$garage)){
		$ownerPublicacion = $anuncio -> getOwnerAd($anuncioid);
		$Notificacion = new Notificacion;
		if($_SESSION["iduser"]!=$ownerPublicacion["owner"]){
			$Notificacion -> addNotification(23,$_POST["c"] , $_SESSION["iduser"], $ownerPublicacion["owner"], NULL, NULL, "/anuncio/?a=".$_POST["p"], 1,true);
		}
		$ret["Success"]=$comment;
	}
	else{
		$ret["Error"]=true;
	}
}
else{
	$ret["Error"]=true;
}
if(isset($ret["Success"])){
$comment=$anuncio->getCommentByID($ret["Success"]);
$coder->encode($comment['idComment']);
$idCommentEncoded=$coder->encoded;
$coder->encode($comment["authorUser"]);
$autorCoded=$coder->encoded;
$coder->encode($comment["authorGarage"]);
$garageCoded=$coder->encoded;
 ?>
<li class="comment" data-comment="<?= $idCommentEncoded?>">
	<a href="<?= ($comment["type"]==1) ? "/perfil/?cuenta=".$autorCoded : "/perfil/garage/timeline/?cuenta=".$autorCoded."&garage=".$garageCoded ?>"><img src="<?= ($comment["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $comment["imgAuthor"] ?>"></a>
	<h5><a href="<?= ($comment["type"]==1) ? "/perfil/?cuenta=".$autorCoded : "/perfil/garage/timeline/?cuenta=".$autorCoded."&garage=".$garageCoded ?>"><?= $comment["author"] ?></a></h5>
	<p><?= $comment["comentario"]?></p>
	<div class="dropdown edit-publication">
		<a class="btn dropdown-toggle edit-publication" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
			<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png" class="options">
		</a>
		<ul class="dropdown-menu list-gird-comment publication-list" aria-labelledby="dropdown">
			<li><a class="pointer" onclick="editCommentAd($(this))">Editar </a> </li>
			<li><a class="pointer" onclick="modalToDeleteComAd('<?= $idCommentEncoded ?>')">Eliminar</a> </li>
		</ul>
	</div>
	<span class="time" title="<?= date("M d, Y - H:i",strtotime($comment["fecha"]))?>">
		<?= hace(strtotime($comment["fecha"]))?>
	</span>
</li>
<?php }
?>