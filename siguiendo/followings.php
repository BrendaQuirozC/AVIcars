<?php

/**
 * @Author: Brenda Quiroz
 * @Date:   2018-06-12 16:22:21
 * @Last Modified by:   Brenda Quiroz
 * @Last Modified time: 2018-11-15 16:40:55
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Publicacion/publicationDate.php';
date_default_timezone_set('America/Mexico_City');
session_start();
$Seguidor = new Seguidor;
$Like = new Like;
$publicacion=new Publicacion;
$coder = new Coder();
$cuenta = $_SESSION["iduser"];
$following = $Seguidor -> siguiendo($cuenta);
$coder -> encode($cuenta);

if(isset($_POST["personas"]))
{
	$following = $Seguidor -> siguiendo($_SESSION["iduser"],$_POST["c"]);

	$k=0;
	foreach ($following as $f => $userFollowed) 
	{ 
		$k++;
		$coder -> encode($userFollowed["siguiendo"]);
		$userFollowedCode = $coder->encoded; ?>
		<div class="people viewingFollowings" data-test="no">
		<img class="userFollowing" onclick="window.location.href='/perfil/?cuenta=<?=$userFollowedCode?>'" src="<?= isset($userFollowed['avatar']) ? $userFollowed['avatar'] : '/img/icons/avatar1.png' ?>" alt="Avatar">
		<div class="text-left no-pading seguidor-top">
			<b onclick="window.location.href='/perfil/?cuenta=<?=$userFollowedCode?>'"><?= $userFollowed["nombre"]?>&ensp;<?=$userFollowed["apellido"]?></b>
			<hr>
			<!--Valida si el perfil del usuario es privado, si lo es, abre la modal que le corresponde-->
			<?php if($userFollowed['privacidad'] == 1){ 
				$modalUnfollow="#Modal_private_p";
			}elseif($userFollowed['privacidad'] == 2){
				$modalUnfollow="#Modal_public_p";
			}else{
				$modalUnfollow="#Modal_secret_p";
			}?>
			<a class="unfollow unfollow-profile<?= $userFollowed['siguiendo']?>" data-elemento="<?= $userFollowed['siguiendo']?>" onclick="enviarDatosModalPerfil($(this),'<?=$userFollowedCode?>',1,'<?= $userFollowed["nombre"]?>','<?= $userFollowed["apellido"]?>');" data-toggle='modal' data-target='<?= $modalUnfollow?>'>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
				Dejar de Seguir
			</a>
			<b class="city"><?= $userFollowed["city"]?></b>
		</div>
	</div>
	<?php
	}
	if($k>0){ ?>
	<div class="people viewingFollowings seemore  text-center" onclick="getFollowers($(this))" data-target="personas">
		Ver m&aacute;s
	</div>
	<?php } ?>
<?php
}
elseif (isset($_POST["publicacion"])) 
{
	$likePub = $Like -> doYouLike($_SESSION["iduser"], 4,$_POST["c"]);
	
	$k=0;
	foreach ($likePub as $lp => $publicationFollowed) 
	{
		$k++;
		$idFollowPub = $publicationFollowed["publicacion"];
		try {
			$publicacionSeguida = $publicacion->getPublicacionById($idFollowPub);
		} catch (Exception $e) {
			$publicacionSeguida = array();
		}
		if(!empty($publicacionSeguida))
		{
			$coder -> encode($publicacionSeguida["usuarioAutor"]);
			$publicacionSeguida["usuarioAutor"] = $coder->encoded;
			$usuarioAutorCoded = $publicacionSeguida['idPublicacion'];
			$coder -> encode($publicacionSeguida['idPublicacion']);
			$publicacionSeguida['idPublicacion'] = $coder->encoded;
		?>
		<div class="publication viewingFollowings">
			<div class="publication-header">
				<img class="img-profile hovImg" onclick="window.location.href='/perfil/?cuenta=<?=$publicacionSeguida["usuarioAutor"]?>'" src="<?= ($publicacionSeguida["authoGarage"]=="") ? (($publicacionSeguida["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $publicacionSeguida["imgAuthor"]) :  (($publicacionSeguida["authorGarageImg"]=="") ? "/img/icons/avatar1.png" : $publicacionSeguida["authorGarageImg"]) ?>">
				<div class="head-info">
					<div class="personal siguiendo-post">
						<b class="hovName" onclick="window.location.href='/perfil/?cuenta=<?=$publicacionSeguida["usuarioAutor"]?>'"><?= ($publicacionSeguida["authoGarage"]=="") ? $publicacionSeguida["authorName"]." ".$publicacionSeguida["authorLastName"] : $publicacionSeguida["authoGarage"]?></b>	
						
					</div>
		    		<hr>
		    		<div class="time" title="<?= date("M d, Y - H:i\h\\r\\s",strtotime($publicacionSeguida["fecha"])) ?>">
		    			<?= hace(strtotime($publicacionSeguida["fecha"])) ?>
		    			<ul>
		    				<?php
	    					$numLikes = $publicacionSeguida["likes"];
	    					if(!$Like->alreadyLike($_SESSION["iduser"], 4, $usuarioAutorCoded)){ ?>
	    					<li style="cursor: unset;">
	    						<span><?=$numLikes?></span>
	    						<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
	    					</li>
	    					<?php } else{ ?>
    						<li style="cursor: unset;">
	    						<span><?=$numLikes?></span>
	    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
	    					</li>
	    					<?php } ?>
							<li style="cursor: unset;">
								<span>
									<?= ($publicacionSeguida["comentarios"]>99) ? "+99" : $publicacionSeguida["comentarios"]?>
								</span>
		    					<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_<?= ($publicacionSeguida["comentarios"]>0 ? "yellow" : "white")?>.png" alt="">
							</li>
							<li style="cursor: unset;">
								<span ><?= ($publicacionSeguida["shareds"]>99) ? "+99" : $publicacionSeguida["shareds"]?></span>
								<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_home_boton_sharepub_infotraffic_v2.png" alt="">
							</li>
						</ul>		
		    		</div>
				</div>
			</div>

			<div class="publication-body">
				<h5>
					<?php if(!empty($publicacionSeguida["url"])) {?>
					<a href="<?= $publicacionSeguida["url"]?>"><?= $publicacionSeguida["tipo"] ?></a>
					<?php }else{ ?>
						<?= $publicacionSeguida["tipo"] ?>
					<?php } ?>
				</h5>
				<p class="text-justify"><?= $publicacionSeguida["texto"]?></p>
			</div>

			<div class="gallery bottom">
				<?php
					$imagenes=array();
					if($publicacionSeguida["imagenes"]!="")
					{
						$imagenes=json_decode(base64_decode($publicacionSeguida["imagenes"],true));
					}
					if(!empty($imagenes))
					{
						?>
				<div id="slider-auto" class="carousel" data-ride="carousel">
					<ol class="carousel-indicators">
					<?php 
		    		foreach ($imagenes as $img => $i) 
		    		{
		    			if($img == 0)
		    			{
	    				?>
							<li data-target="#slider-auto" data-slide-to="<?= $img ?>" class="active"></li>
						<?php
						}
						else
						{
						?>
							<li data-target="#slider-auto" data-slide-to="<?= $img ?>"></li>
						<?php 
						}
					} ?>
		  			</ol>			
					<div class="carousel-inner" role="listbox">
					<?php 
		    		foreach ($imagenes as $img => $i) {
		    			if($img == 0)
		    			{
	    				?>
	    					<div class="item active">
	    						<img class="center-block imagen-publicacion " src="<?= $i?>" alt="<?=$img?>">
							</div>

					<?php
				  		}
				  		else
				  		{
				  			?>
				  			<div class="item">
								<img class="center-block imagen-publicacion pointer" src="<?= $i?>" alt="<?=$img?>" onclick="setTimeout(function(){openPublishSlide();currPublishSlide(<?=$img?>)},200);">
							</div>
				  			<?php
				  		}
			  		}
			  		?>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			<div class="text-right">
			<?php 
			if($Like->alreadyLike($_SESSION["iduser"], 4, $usuarioAutorCoded)){ ?>
				<a class="unfollow" onclick="unlikingFromSiguiendo($(this), '<?=$publicacionSeguida['idPublicacion']?>', 4)">
					Dejar de seguir
				</a>&nbsp;
			<?php } ?>
				<a class="goToPublication" href="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/post/?p=".$publicacionSeguida["idPublicacion"] ?>">Ir a publicaci&oacute;n</a>
			</div>
		</div>
	<?php
		}
	}
	if($k>0){ ?>
	<div class="publication viewingFollowings seemore  text-center" onclick="getFollowers($(this))" data-target="publicacion">
		Ver m&aacute;s
	</div>
<?php
	}
}
elseif (isset($_POST["garages"])) 
{ 
	$followingGar = $Seguidor -> followingGarage($_SESSION["iduser"],$_POST["c"]);
	
	$k=0;
	foreach ($followingGar as $f => $garageFollowed) 
	{
		$k++;
		$coder -> encode($garageFollowed["idPersona"]);
		$followedCode = $coder->encoded;
		$coder ->  encode($garageFollowed["siguiendo"]);
		$followedGarageCode = $coder->encoded;
	?>
	<div class="people viewingFollowingsGarage">
		<div class="text-left no-pading seguidor-top">
			<b onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$followedCode?>&garage=<?=$followedGarageCode?>'"><?= $garageFollowed["garageNombre"]?></b>
			<hr>
			<!--Valida si el garage del usuario es privado, si lo es, abre la modal que le corresponde-->
			<?php if($garageFollowed['privacidad'] == 1){ 
				$modalUnfollow="#Modal_private_g";
			}elseif($garageFollowed['privacidad'] == 2){
				$modalUnfollow="#Modal_public_g";
			}else{
				$modalUnfollow="#Modal_secret_g";
			}?>
			<a class="unfollow unfollow-garage<?= $garageFollowed['siguiendo']?>" data-element="<?= $garageFollowed['siguiendo']?>" onclick="enviarDatosModalGarage($(this),'<?=$followedGarageCode?>',2,'<?= $garageFollowed["garageNombre"]?>');" data-toggle='modal' data-target='<?= $modalUnfollow?>'>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
				Dejar de Seguir
			</a>
			<b class="city" onclick="window.location.href='/perfil/?cuenta=<?=$followedCode?>'"><?= $garageFollowed["nombre"]?>&ensp;<?=$garageFollowed["apellido"]?></b>
		</div>
		<img onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$followedCode?>&garage=<?=$followedGarageCode?>'" src="<?= isset($garageFollowed['garageAvatar']) ? $garageFollowed['garageAvatar'] : '/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg' ?>" alt="Garage" class="imgGarage img-responsive avatar img-up">
		<img class="userInGarage" src="<?= isset($garageFollowed['avatar']) ? $garageFollowed['avatar'] : '/img/icons/avatar1.png' ?>" alt="Avatar" class="img-thumbnail avatar publication-img icon-big-size">
	</div>
	<?php 
	}
	if($k>0){?>
	<div class="people viewingFollowings seemore  text-center" onclick="getFollowers($(this))" data-target="garages">
		Ver m&aacute;s
	</div>
<?php
	}
}
elseif (isset($_POST["autos"])) 
{ 
	$followingCar = $Seguidor -> followingCar($_SESSION["iduser"], $_POST["c"]);
	
	$k=0;
	foreach ($followingCar as $f => $autoSeguido) 
	{
		$k++;
		$coder -> encode($autoSeguido["idUsuario"]);
		$followedCode = $coder->encoded;
		$coder -> encode($autoSeguido["idDueño"]);
		$ownerCode = $coder->encoded;
		$coder ->  encode($autoSeguido["idGarage"]);
		$followedGarageCode = $coder->encoded;
		$coder ->  encode($autoSeguido["siguiendo"]);
		$followedCarCode = $coder->encoded;
		$autoSeguido["siguiendo"] = $coder->encoded;
		$coder ->  encode($autoSeguido["idGarage"]);
		$autoSeguido["idGarage"] = $coder->encoded;
	?>
	<div class="people viewingFollowingsCar <?= ($autoSeguido["vendido"]) ? "sold" : "" ?>">
		<?= ($autoSeguido["vendido"]) ? "<div class='sello' onclick='window.location.href=\"/perfil/autos/detalles/?cuenta=".$followedCode."&auto=".$followedCarCode."\"'></div>" : "" ?>
		<div class="text-left no-pading seguidor-top">
			<b onclick="window.location.href='/perfil/autos/detalles/?cuenta=<?=$followedCode?>&auto=<?=$followedCarCode?>'"><?=$autoSeguido["aliasAuto"]?></b>
			<hr>
			<?php if($autoSeguido['privacidad'] == 1){ 
				$modalUnfollow="#Modal_private_a";
			}elseif($autoSeguido['privacidad'] == 2){
				$modalUnfollow="#Modal_public_a";
			}else{
				$modalUnfollow="#Modal_secret_a";
			}?>
			<a class="unfollow unfollow-car<?= $autoSeguido['siguiendo2']?>" data-elemen="<?= $autoSeguido['siguiendo2']?>" onclick="enviarDatosModalAuto($(this),'<?=$followedCarCode?>',3,'<?= $autoSeguido["aliasAuto"]?>','<?=$ownerCode?>');" data-toggle='modal' data-target='<?= $modalUnfollow?>'>
				<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
				Dejar de Seguir
			</a>
			<b class="city"  onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$followedCode?>&garage=<?=$followedGarageCode?>'">Garage:&ensp;<?=$autoSeguido["garageNombre"]?></b>
		</div>
		<img src="<?= isset($autoSeguido['imgAuto']) ? $autoSeguido['imgAuto'] : '/img/noimage.png' ?>" alt="Garage" class="imgGarage img-responsive avatar img-up" onclick="window.location.href='/perfil/autos/detalles/?cuenta=<?=$followedCode?>&auto=<?=$followedCarCode?>'">
		<img class="userInGarage" src="<?= isset($autoSeguido['garageAvatar']) ? $autoSeguido['garageAvatar'] : '/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg' ?>" alt="Avatar" class="img-thumbnail avatar publication-img icon-big-size">
	</div>
	<?php 
	}
	if($k>0){ ?>
	<div class="people viewingFollowings seemore  text-center" onclick="getFollowers($(this))" data-target="autos">
		Ver m&aacute;s
	</div>
<?php
	}
}
elseif (isset($_POST["servicios"])) 
{ ?>
	Un equipo de monos altamente entrenado se encuentra subiendo miles de datos para tu mejor experiencia.
<?php 
}
elseif (isset($_POST["anuncios"])) 
{
	$likeCar = $Like -> doYouLike($_SESSION["iduser"], 5,$_POST["c"]);
	
	$k=0;
	foreach ($likeCar as $like => $adFollowed) 
	{
		$k++;
		$idFollowAd = $adFollowed["ad"];
		try{
			$anuncioSeguido = $Like -> adYouLike($idFollowAd);
		} catch (Exception $e){
			$anuncioSeguido = array();
		}
		if(!empty($anuncioSeguido))
		{
			$coder -> encode($anuncioSeguido["idUsuario"]);
			$anuncioSeguido["idUsuario"] = $coder->encoded;
			$coder ->  encode($anuncioSeguido["siguiendo"]);
			$anuncioSeguido["siguiendo"] = $coder->encoded;
			$coder ->  encode($anuncioSeguido["idGarage"]);
			$anuncioSeguido["idGarage"] = $coder->encoded;
			?>
			<div class="people viewingFollowingsCar">
				<div class="text-left no-pading seguidor-top">
					<b onclick="window.location.href='/anuncio/?a=<?=$anuncioSeguido["siguiendo"]?>'"><?=$anuncioSeguido["aliasAuto"]?></b>
					<hr>
					<a class="unfollow" onclick="unlikingFromSiguiendo($(this), '<?=$anuncioSeguido["siguiendo"]?>', 5)">
						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png">
						Dejar de Seguir
					</a>
					<b class="city"  onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$anuncioSeguido["idUsuario"]?>&garage=<?=$anuncioSeguido["idGarage"]?>'">Garage:&ensp;<?=$anuncioSeguido["garageNombre"]?></b>
				</div>
				<img src="<?= isset($anuncioSeguido['imgAuto']) ? $anuncioSeguido['imgAuto'] : '/img/noimage.png' ?>" alt="Garage" class="imgGarage img-responsive avatar img-up" onclick="window.location.href='/anuncio/?a=<?=$anuncioSeguido["siguiendo"]?>'">
			</div>
			<?php 
		}
	}
	if($k>0){ ?>
	<div class="people viewingFollowings seemore  text-center" onclick="getFollowers($(this))" data-target="anuncios">
		Ver m&aacute;s
	</div>
<?php 
	}
}
elseif (isset($_POST["ofertas"])) 
{ ?>
	Un equipo de monos altamente entrenado se encuentra subiendo miles de datos para tu mejor experiencia. 
<?php } ?>