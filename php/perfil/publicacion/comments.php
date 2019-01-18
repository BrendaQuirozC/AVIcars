<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-11 14:20:42
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-21 12:15:48
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/usuario.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicationDate.php';
date_default_timezone_set('America/Mexico_City');
$coder = new Coder();
$coder->decode($_POST["p"]);
session_start();
$publicacion=new Publicacion($coder->toEncode);
$usuario=new Usuario;
if($publicacion->UserAccessToPublication($_SESSION["iduser"],$coder->toEncode))
{
	$avialableCommentors=$publicacion->getAvialableCommentors($_SESSION["iduser"],$coder->toEncode);
	$comments=$publicacion->getCommentsByPublication($coder->toEncode,$_SESSION["iduser"],$_POST["l"]);
	$comments=array_reverse($comments);
	$faltantes=$_POST["t"]-$_POST["l"]-10;
if($faltantes>0){?>

<li onclick="doComment($(this));" data-last="<?= ($_POST["l"]+10) ?>" data-total="<?= $_POST["t"]?>" class="doCom-<?= $_POST["p"]?> more-comments comment" data-p="<?= $_POST["p"]?>">
	Cargar mas (<?= $_POST["t"]-$_POST["l"]-10?>)
</li>
<?php } 
foreach ($comments as $c => $comment) { 
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
		
		<div class="dropdown edit-publication">
			<a class="btn dropdown-toggle edit-publication" type="button" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png" class="options">
			</a>
			<ul class="dropdown-menu list-gird-comment publication-list" aria-labelledby="dropdown">
				<?php if ($comment["authorUser"] == $_SESSION["iduser"]) { ?>
				<li><a class="pointer" onclick="editComment($(this))">Editar </a> </li>
				<?php }
				if ($publicacion->author == $_SESSION["iduser"] || $publicacion->container == $_SESSION["iduser"] || $comment["authorUser"] == $_SESSION["iduser"]) { ?>
				<li><a class="pointer" onclick="modalToDeleteCom('<?= $idCommentEncoded?>')">Eliminar</a> </li>
				<?php 
				}
				if ($comment["authorUser"] != $_SESSION["iduser"]) { 
					$coder->encode($comment["authorUser"]);
					 ?>
				<li><a class="pointer" data-perfil="<?=$coder->encoded?>" data-comment="<?= $idCommentEncoded?>" onclick="modalToReport($(this))">Reportar </a> </li>
				<?php } ?>
			</ul>
		</div>
		<p><?= $comment["comentario"]?></p>
		<span class="time" title="<?= date("M d, Y - H:i",strtotime($comment["hora"]))?>">
			<?=($comment["modificada"]!=NULL) ? " - editado ".hace(strtotime($comment["modificada"])) : hace(strtotime($comment["hora"]))?>
		</span>
    </li>
<?php }
if($_POST["l"]<=0){ ?>
<li class="comentor">
	<?php $userData=$usuario->getUserBasic($_SESSION["iduser"]); 
	$coder->encode($userData["id"]);
	$userComment=$coder->encoded;
	?>
	<img class="header-comment" src="<?= ($userData["img"]=="") ? "/img/icons/avatar1.png" : $userData["img"] ?>" data-t="1" data-e="<?= $userComment?>">&nbsp;<span class="commentor"><?= $userData["name"]?></span>
	<?php if($avialableCommentors){  ?>
	<img class="moreCommentors" src="/img/icons/down.png" onclick="moreComentors($(this))">
	<ul class="navigation-list commet-dp" >
		<li onclick="chooseComentor($(this))" data-t="1" data-e="<?= $userData["id"]?>">
			<img src="<?= ($userData["img"]=="") ? "/img/icons/avatar1.png" : $userData["img"] ?>">&nbsp;<span><?= $userData["name"]?></span>
		</li>
	<?php foreach ($avialableCommentors as $ac => $commentor) { ?>
		<li onclick="chooseComentor($(this))" data-t="2" data-e="<?= $commentor["id"]?>">
			<img src="<?= ($commentor["img"]=="") ? "/img/icons/avatar1.png" : $commentor["img"] ?>">&nbsp;<span><?= $commentor["nombre"]?></span>
		</li>
	<?php } ?>
	</ul>
	<?php 
	}?>
	<textarea rows="3" maxlength="160" placeholder="Escribe un comentario (m&aacute;x 160 caracteres)" class="form-style textComment"></textarea>
	<button class="btn btn-avi" onclick="comentar($(this))" data-p="<?= $_POST["p"]?>">Enviar</button>
</li>
<?php }
}
else{ ?>
<li><h4>Este contenido no esta disponible</h4></li>
<?php } ?>