<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-12 12:26:27
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-15 12:57:21
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Instancia/Instancia.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Garage = new Garage;
$share=new Share;
$Instancia=new Instancia;
$detalles = array();
if(!isset($_GET["p"])){
	header("Location: /");
	exit();
}
if($_GET["p"]==""){
	header("Location: /");
	exit();
}
if(!isset($_SESSION["iduser"])){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
if(isset($_SESSION["iduser"]))
{
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
    //$nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
    $privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
}
$shareFather=0;
$coder->decode($_GET["p"]);
$post=$coder->toEncode;
$publicacion=new Publicacion;
try {
	$publication=$publicacion->getPublicationByID($post);
} catch (Exception $e) {
	header("Location: /");
}

if(empty($publication)){ 
	header("Location: /");
}
$imagenes=json_decode(base64_decode($publication["imagenes"],true));
$imgMeta="/img/portada.jpg";
if(!empty($imagenes)){
	$imgMeta=$imagenes[0];
}

if($publication["authoGarage"]=="")
{
	$authorPostMeta=$publication["authorName"]." ".$publication["authorLastName"];
} else{
	$authorPostMeta=$publication["authoGarage"];
}
$show=false;
if($publicacion->UserAccessToPublication($_SESSION["iduser"],$post)){
	$show=true;
	$metasShare=array(
		"og"	=>	array(
			"title" => "AVI cars by Infotraffic | ".$authorPostMeta,
		    "description" => $publication["tipo"].". ".$publication["texto"],
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$imgMeta,
		    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/post/?p=".$_GET["p"],
		    "site_name" => "AVI cars",
		    "type" => "website"
		),
		"tw"	=>	array(
			"title" => "AVI cars by Infotraffic | ".$authorPostMeta,
		    "description" => $publication["tipo"].". ".$publication["texto"],
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$imgMeta,
		    "image:alt" => "AVI cars",
		    "card" => "summary_large_image"
		)
	);
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
$load=false;
$postCoded=$_GET["p"];
$shareContent=array();
$Like = new Like;
if($show){
	
	if(!empty($publication)){
		$link=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/post/?p=".$postCoded;
		if($publication["shared"]){
			$sharing=$share->getShared($publication["shared"]);
			$typeSharing=$sharing["type"];
			switch ($sharing["type"]) {
				case 1:
					$toShare=$sharing["user"];
					if($Usuario->UserAccessToUser($_SESSION["iduser"],$toShare)){
						$shareContent=array("type"=>$sharing["type"],"object"=>$Usuario->getUserBasic($toShare));
						$load=true;
						$coder->encode($sharing["user"]);
						$link=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/?cuenta=".$coder->encoded;
					}
					break;
				case 2:
					$toShare=$sharing["garage"];
					if($Garage->UserAccessToGarage($_SESSION["iduser"],$toShare)){
						$shareContent=array("type"=>$sharing["type"],"object"=>$Garage->getInfoGarage($toShare));
						$load=true;
						$coder->encode($shareContent["object"]["owner"]);
						$shareFather=$coder->encoded;
						$coder->encode($sharing["garage"]);
						$link=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$shareFather."&garage=".$coder->encoded;
					}
					break;
				case 3:
					$toShare=$sharing["car"];
					$owner = $_SESSION["iduser"];
					if($Instancia->UserAccessToInstance($_SESSION["iduser"],$toShare)){
						$shareContent=array("type"=>$sharing["type"],"object"=>$Garage->instanciaById($toShare));
						$load=true;
						$coder->encode($shareContent["object"]["user"]);
						$shareFather=$coder->encoded;
						$coder->encode($sharing["car"]);
						$link=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$shareFather."&auto=".$coder->encoded;
					}
					break;
				case 4:
					$toShare=$sharing["post"];

					if($publicacion->UserAccessToPublication($_SESSION["iduser"],$toShare)){
						$load=true;
						$shareContent=array("type"=>$sharing["type"], "object"=>$publicacion->getPublicationByID($sharing["post"]));
						$coder->encode($sharing["post"]);
						$link=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/post/?p=".$coder->encoded;
					}
					break;
				case 5:
					$toShare=$sharing["ad"];
					$shareContent=array("type"=>$sharing["type"], "object"=>$Instancia->getAdvertisementById($sharing["ad"]));
					$load=true;
					if(!empty($shareContent["object"])){
						$link=(isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".$shareContent["object"]["link"];
					}
					break;
				default:
					break;
			}
			
		}
		else
		{
			$load=true;
			$typeSharing=4;
			$toShare=$publication["idPublicacion"];
		}
	}
}
if($load){
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$active="";
	$cuenta=$_SESSION["iduser"];
	$coder->encode($cuenta);
	$cuentaCoded=$coder->encoded;
	$colaborador=false;
	?>

	<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
		<ul>
			<li class="<?= ($active=="profile") ? "active" : ""?>">
				<a href="/perfil/?cuenta=<?=$cuentaCoded?>">
					<span> Timeline</span>
					<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_miTimeline_infotraffic.png" class="navigation-icon"> 
				</a>
			</li>
			<li class="<?= ($active=="garage") ? "active" : ""?>">
				<a href="/perfil/garage/?cuenta=<?=$cuentaCoded?>"> 
					<span> Mis Garages</span> 
					<img src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/LogIn_Movil_icono_garages_gde2_infotraffic.png" class="navigation-icon">
				</a>
			</li>
			<li class="<?= ($active=="auto") ? "active" : ""?>">
				<a href="/perfil/autos/?cuenta=<?=$cuentaCoded?>">
					<span> Mis Autos</span> 
					<img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_misAutos_infotraffic.png" class="navigation-icon"> 
				</a>
			</li>
			<?php if($owner){ ?>
			<li class="<?= ($active=="docs") ? "active" : ""?>">
				<a href="/perfil/docs/?cuenta=<?=$cuentaCoded?>">
					<span> Expediente</span> 
					<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> 
				</a>
			</li>
			<?php } ?>
		</ul>
		
	</div>
	<div class="sidebar sidebar-no-header sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
		<?php
		if(empty($_SESSION["iduser"]))
		{ ?>
			<p>
				<a href="/" target="_blank">
					<img onclick="modalLoginNow()" src="/img/Banner_registro_AVI.png">
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
	<div class="content content-no-header content-nopadding-bottom">
		<div class="row space">
		    <div class="col-xs-12 publication">
		    	<div class="publication-header">
		    		<?php 
		    		if($publication["authoGarage"]=="")
					{
						$coder->encode($publication["usuarioAutor"]);
						$userAuthorCoded=$coder->encoded;
						?>
						<img class="img-profile" src="<?= ($publication["authoGarage"]=="") ? (($publication["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $publication["imgAuthor"]) :  (($publication["authorGarageImg"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $publication["authorGarageImg"]) ?>" onclick="window.location.href='/perfil/?cuenta=<?= $userAuthorCoded?>'">

					<?php } else{
						$coder->encode($publication["usuarioAutor"]);
						$userAuthorCoded=$coder->encoded;
						$coder->encode($publication["cuentaAutor"]);
						$garageAuthorCoded=$coder->encoded;
						$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $publication["cuentaAutor"],3);
					?>
						<img class="img-profile" src="<?= ($publication["authoGarage"]=="") ? (($publication["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $publication["imgAuthor"]) :  (($publication["authorGarageImg"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $publication["authorGarageImg"]) ?>" onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?=$userAuthorCoded?>&garage=<?=$garageAuthorCoded?>'">
					<?php } ?>
		    		
		    		<div class="head-info">
		    			<div class="personal">
							<?php if($publication["authoGarage"]=="")
							{
								$coder->encode($publication["usuarioAutor"]);
								$userAuthorCoded=$coder->encoded;
								?>
								<a class="name-publication" onclick="window.location.href='/perfil/?cuenta=<?= $userAuthorCoded ?>'"><?=$publication["authorName"]?> <?= $publication["authorLastName"]?> <span><?= $publication["userverified"] == 1 ? "<img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?></span></a>

							<?php } else{
								$coder->encode($publication["usuarioAutor"]);
								$userAuthorCoded=$coder->encoded;
								$coder->encode($publication["cuentaAutor"]);
								$garageAuthorCoded=$coder->encoded;
								?>
								<a class="name-publication" onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?= $userAuthorCoded ?>&garage=<?= $garageAuthorCoded ?>'"><?= $publication["authoGarage"]?><span><?= $publication["garageverified"] == 1 ? "<img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?></span></a>
							<?php }
		    				if(isset($publication["contentCar"])||isset($publication["contentGarage"])||isset($publication["contentName"])){
		    					$coder->encode($publication["usuarioDestino"]);
								$userContentCoded=$coder->encoded;
								$coder->encode($publication["cuentaDestino"]);
								$garageContentCoded=$coder->encoded;
								$coder->encode($publication["autoDestino"]);
								$autoContentCoded=$coder->encoded;
		    				?>
		    				<?= ($publication["contentCar"]=="") ? (($publication["contentGarage"]=="") ? (($publication["usernameAuthor"]!=$publication["usernameContent"]) ? "-> <a class='name-publication' href='/perfil/?cuenta=".$userContentCoded."'>".$publication["contentName"]." ".$publication["contentLastName"]."</a>" : "" ) : (($publication["cuentaAutor"]!=$publication["cuentaDestino"]) ? "-> <a class='name-publication' href='/perfil/garage/timeline/?cuenta=".$userContentCoded."&garage=".$garageContentCoded."'>".$publication["contentGarage"]."</a>": "")): "-> <a class='name-publication' href='/perfil/autos/detalles/?cuenta=".$userContentCoded."&auto=".$autoContentCoded."'>".$publication["contentCar"] ?>
		    				<?php } ?>
		    				<?php if(!empty($_SESSION)&&isset($_SESSION["iduser"])&&$_SESSION["iduser"]>0)
		    				{
		    					$coder->encode($publication["usuarioAutor"]);
		    					$authorpost=$coder->encoded;
		    					if ($publication["usuarioAutor"] == $_SESSION["iduser"] || $owner == $_SESSION["iduser"] || $colaborador) 
		    					{ ?>
								<div class="dropdown edit-publication">
									<a class="btn dropdown-toggle edit-publication" type="button" id="dropdown-<?=$p?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png">
									</a>
									<ul class="dropdown-menu list-gird publication-list" aria-labelledby="dropdown-<?=$p?>">
										<?php if ($publication["usuarioAutor"] == $_SESSION["iduser"] && !$publication["shared"] || $colaborador) { ?>
										<li><a class="pointer" onclick="editPublication('<?=$postCoded?>')">Editar </a> </li>
										<?php } ?>
										<li><a class="pointer" onclick="modalToDeletePub('<?=$postCoded?>')">Eliminar</a> </li>
										<?php if ($publication["usuarioAutor"] != $_SESSION["iduser"]) { ?>
										<li><a class="pointer" data-perfil="<?= $authorpost?>" data-publicacion="<?=$postCoded?>" onclick="modalToReport($(this))">Reportar </a> </li>
										<?php } ?>
									</ul>
								</div>
								<?php 
								}
								if($publication["usuarioAutor"] != $_SESSION["iduser"] && $owner != $_SESSION["iduser"] && !$colaborador) 
		    					{ ?>
	    						<div class="dropdown edit-publication">
	    							<a class="btn dropdown-toggle edit-publication" type="button" id="dropdown-<?=$p?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/tres_puntitos.png">
									</a>
									<ul class="dropdown-menu list-gird publication-list" aria-labelledby="dropdown-<?=$p?>">
										<li><a class="pointer" data-perfil="<?= $authorpost?>" data-publicacion="<?=$postCoded?>" onclick="modalToReport($(this))">Reportar </a>  </li>
									</ul>
	    						</div>
		    					<?php 
		    					}
							} ?>
							<hr>	
							<div class="time" title="<?=($publication["modificacion"]!=NULL) ? date("M d, Y - H:i\h\\r\\s",strtotime($publication["modificacion"])) : date("M d, Y - H:i\h\\r\\s",strtotime($publication["fecha"])) ?>">
				    			<?=($publication["modificacion"]!=NULL) ? " - editado ".hace(strtotime($publication["modificacion"])) : hace(strtotime($publication["fecha"])) ?>
				    			<ul>
				    				<?php
			    					$numLikes = $publication["likes"];
			    					if(!$Like->alreadyLike($_SESSION["iduser"], 4, $publication["idPublicacion"])){ ?>
			    					<li data-likes=".bodyLike" class="bodyLike" <?= ($_SESSION["iduser"]>0)  ? 'onclick="like($(this),\''.$postCoded.'\', 4)"' : "" ?>>
			    						<span class="countLikes"><?=$numLikes?></span>
			    						<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
			    					</li>
			    					<?php } else{ ?>
		    						<li data-likes=".bodyLike" class="bodyLike" onclick="unlike($(this),'<?= $postCoded?>', 4)">
			    						<span class="countLikes"><?=$numLikes?></span>
			    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
			    					</li>
			    					<?php } ?>
			    					<li <?= ($_SESSION["iduser"]>0)  ? 'onclick="goComment($(this));"' : "" ?> data-last="0" data-total="<?= $publication["comentarios"]?>" class="doCommentBtn doCom-<?= $postCoded?>" data-p="<?= $postCoded?>">
			    						<span><?= ($publication["comentarios"]>99) ? "+99" : $publication["comentarios"]?></span>
			    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_<?= ($publication["icomment"]>0 ? "yellow" : "white")?>.png" alt="">
			    					</li>
			    					<li>
			    						<span><?= ($publication["shareds"]>99) ? "+99" : $publication["shareds"]?></span>
										<img <?= ($_SESSION["iduser"]>0)  ? 'onclick="shareThis($(this))"' : "" ?> src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2<?= ($publication["ishare"]>0 ? "-SELECTED" : "")?>_infotraffic.png" alt="">
										<ul class="navigation-list">
											<?php
											if($shareFather){
												$coder->encode($shareFather);
												$shareFather=$coder->encoded;
											}
											$coder->encode($toShare);
											$toShare=$coder->encoded;
											?>
											<li onclick="doShare($(this),<?= $typeSharing ?>)" <?= ($shareFather) ? "data-f='$shareFather'" : "" ?> data-p="<?= $toShare ?>">En AVI cars</li>
											<li onclick="doShareWhatsApp($(this))" data-target="<?= $link ?>">En WhatsApp</li>
											<li data-target="<?= $link ?>" onclick="copyShare(this,$(this))">Copiar link </li>
										</ul>
			    					</li>
			    				</ul>	
				    		</div>	
		    			</div>
		    		</div>
		    	</div>
		    	<div class="publication-body">
		    		<?php 
		    		if($publication["tipoId"]!=1){ ?>
			    		<h5>
			    			<?php
			    			if(!empty($publication["url"])) { ?>
							<a href="<?= $publication["url"]?>"><?= $publication["tipo"] ?></a>
							<?php }else{ ?>
								<?= $publication["tipo"] ?>
							<?php } ?>
						</h5>
						<?php 
					}
					if(empty($shareContent)){ ?>
					<p class="text-justify"><?= (!empty($publication["precio"])) ? (($publication["moneda"]=="EUR") ? "&euro;" : "$" )." ".number_format($publication["precio"], 0, '.', ',')." ".$publication["moneda"] : "" ?></p>
					<p class="text-justify">
						<?php 
						$globalUrl = "/(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/\S*)?/";
						if ($menciones = $publicacion ->getMentions($publication["texto"]) ) 
						{
							if(preg_match($globalUrl, $publication["texto"], $url)) //solo con http
							{
								echo preg_replace($globalUrl, "<a href='{$url[0]}' target='_blank'>{$url[0]}</a>", $menciones);
							}
							else
							{
								echo $menciones;
							}
						}
						if ($publication["linkStatus"]) //si existe un link
						{
							$youbelink = strpos($publication["linkStatus"],'youtube.com');
							if($youbelink !=false){ ?>
								<div class='iframe-container'>
									<iframe width='560' height='315' src='<?=$publication["linkStatus"]?>' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
								</div>
								<?php
							}
							if($publication["metas"]!=NULL)
							{
								$json64= base64_decode($publication["metas"]);
								$metaUrls=json_decode($json64,true);
								list($width, $height) = getimagesize($metaUrls['og:image']);
						        if (isset($width) && $width > 500) { ?>
						            <div class="meta-st" onclick="window.open('<?=$metaUrls['og:url']?>','_blank')">
				                        <img class="full" src="<?=$metaUrls['og:image']?>">
			                            <div class="meta-foot">
			                                <p class="link"><?=$metaUrls['og:url']?></p>
			                                <h4><?=$metaUrls['og:title']?></h4>
			                                <p class="descr"><?=$metaUrls['og:description']?></p>
			                            </div>
			                        </div>
						        <?php }
						        else
						        { ?>
						            <div class="meta-st" onclick="window.open('<?=$metaUrls['og:url']?>','_blank')">
				                        <img class="half" src="<?=$metaUrls['og:image']?>">
		                                <div class="half-foot">
		                                    <p class="link"><?=$metaUrls['og:url']?></p>
		                                    <h4><?=$metaUrls['og:title']?></h4>
		                                    <p class="descr"><?=$metaUrls['og:description']?></p>
		                                </div>
			                        </div>
						        <?php }
						    }
						} ?>
					</p>
					<?php }
					else{ 
						$publication["imagenes"]="";
						switch($shareContent["type"]){ 
							case 1:
								$userShared=$shareContent["object"];

								
								?>
								<div class="publication repost">
							    	<div class="publication-header">
							    		<img class="img-profile" src="<?= ($userShared["img"]=="") ? "/img/icons/avatar1.png" : $userShared["img"] ?>">
							    		<div class="head-info">
							    			<div class="personal">
							    				Usuario: <?= $userShared["name"]?>
							    			</div>
							    		</div>
							    	</div>
							    	<div class="publication-body"></div>
							    </div>


							<?php
								break;
							case 2:
								$garageShared=$shareContent["object"];
								
								?>
								<div class="publication repost">
							    	<div class="publication-header">
							    		<img class="img-profile" src="<?= ($garageShared["img"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $garageShared["img"] ?>">
							    		<div class="head-info">
							    			<div class="personal">
							    				Garage: <?= $garageShared["nombre"]?>
							    			</div>
							    		</div>
							    	</div>
							    	<div class="publication-body"></div>
							    </div>


							<?php
								break;
							case 3:
								$autoShared=$shareContent["object"][0];
								?>
								<div class="publication repost">
							    	<div class="publication-header">
							    		<img class="img-profile" src="<?= ($autoShared["avatar"]=="") ? "/img/noimage.png" : $autoShared["avatar"] ?>">
							    		<div class="head-info">
							    			<div class="personal">
							    				Auto: <?= $autoShared["i_avi_account_car_alias"]?>
							    			</div>
							    		</div>
							    	</div>
							    	<div class="publication-body"></div>
							    </div>


							<?php
								break;
							case 4: 
								$repost=$shareContent["object"];
								
								?>
								<div class="publication repost">
							    	<div class="publication-header">
							    		<?php if($repost["authoGarage"]=="")
										{
											$coder->encode($repost["usuarioAutor"]);
											$userAuthorCoded=$coder->encoded;
											?>
											<img class="img-profile" src="<?= ($repost["authoGarage"]=="") ? (($repost["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $repost["imgAuthor"]) :  (($repost["authorGarageImg"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $repost["authorGarageImg"]) ?>" onclick="window.location.href='/perfil/?cuenta=<?= $userAuthorCoded ?>'">

										<?php } else{
											$coder->encode($repost["usuarioAutor"]);
											$userAuthorCoded=$coder->encoded;
											$coder->encode($repost["cuentaAutor"]);
											$garageAuthorCoded=$coder->encoded;
										?>
											<img class="img-profile" src="<?= ($repost["authoGarage"]=="") ? (($repost["imgAuthor"]=="") ? "/img/icons/avatar1.png" : $repost["imgAuthor"]) :  (($repost["authorGarageImg"]=="") ? "/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" : $repost["authorGarageImg"]) ?>" onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?= $userAuthorCoded ?>&garage=<?= $garageAuthorCoded ?>'">
										<?php } ?>
							    		<div class="head-info">
							    			<div class="personal">
							    				<?php if($repost["authoGarage"]=="")
												{
													$coder->encode($repost["usuarioAutor"]);
													$userAuthorCoded=$coder->encoded;
													?>
													<a class="name-publication" onclick="window.location.href='/perfil/?cuenta=<?= $userAuthorCoded?>'"><?=$repost["authorName"]?> <?= $repost["authorLastName"]?> <span><?= $repost["userverified"] == 1 ? "<img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?></span></a>

												<?php } else{
													$coder->encode($repost["usuarioAutor"]);
													$userAuthorCoded=$coder->encoded;
													$coder->encode($repost["cuentaAutor"]);
													$garageAuthorCoded=$coder->encoded;
													?>
													<a class="name-publication" onclick="window.location.href='/perfil/garage/timeline/?cuenta=<?= $userAuthorCoded?>&garage=<?=$garageAuthorCoded?>'"><?= $repost["authoGarage"]?><span><?= $repost["garageverified"] == 1 ? "<img class='icon-verified' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?></span></a>
												<?php }
							    				if(isset($repost["contentCar"])||isset($repost["contentGarage"])||isset($repost["contentName"])){
							    					$coder->encode($repost["usuarioDestino"]);
													$userContentCoded=$coder->encoded;
													$coder->encode($repost["cuentaDestino"]);
													$garageContentCoded=$coder->encoded;
													$coder->encode($repost["autoDestino"]);
													$autoContentCoded=$coder->encoded;
							    				?>
							    				<?= ($repost["contentCar"]=="") ? (($repost["contentGarage"]=="") ? (($repost["usernameAuthor"]!=$repost["usernameContent"]) ? "-> <a class='name-publication' href='/perfil/?cuenta=".$userContentCoded."'>".$repost["contentName"]." ".$repost["contentLastName"]."</a>" : "" ) : "-> <a class='name-publication' href='/perfil/garage/timeline/?cuenta=".$userContentCoded."&garage=".$garageContentCoded."'>".$repost["contentGarage"])."</a>" : "-> <a class='name-publication' href='/perfil/autos/detalles/?cuenta=".$userContentCoded."&auto=".$autoContentCoded."'>".$repost["contentCar"] ?>
							    				<?php } ?>
							    				<hr>
							    				<div class="time" title="<?= date("M d, Y - H:i\h\\r\\s",strtotime($repost["fecha"])) ?>">
									    			<?= hace(strtotime($repost["fecha"]))?>
									    			<ul>
									    				<?php
								    					$numLikes = $repost["likes"];
								    					$coder->encode($repost["idPublicacion"]);
								    					$repostPubCoded=$coder->encoded;
								    					if(!$Like->alreadyLike($_SESSION["iduser"], 4, $repost["idPublicacion"])){ ?>
								    					<li data-likes=".bodyLike" class="pointer" <?= ($_SESSION["iduser"]>0)  ? 'onclick="like($(this), \''.$repostPubCoded.'\', 4)"' : "" ?>>
								    						<span class="countLikes"><?=$numLikes?></span>
								    						<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
								    					</li>
								    					<?php } else{ ?>
							    						<li data-likes=".bodyLike" onclick="unlike($(this), '<?= $repostPubCoded ?>', 4)">
								    						<span class="countLikes"><?=$numLikes?></span>
								    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
								    					</li>
								    					<?php } ?>
								    					<li>
								    						<span><?= ($repost["comentarios"]>99) ? "+99" : $repost["comentarios"]?></span>
								    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_<?= ($repost["icomment"]>0 ? "yellow" : "white")?>.png" alt="">
								    					</li>
								    					<li>
								    						<span><?= ($repost["shareds"]>99) ? "+99" : $repost["shareds"]?></span>
								    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2<?= ($repost["ishare"]>0 ? "-SELECTED" : "")?>_infotraffic.png" alt="">
								    					</li>
								    				</ul>	
									    		</div>
							    			</div>
								    		
								    		
							    		</div>
							    	</div>
							    	<div class="publication-body">
							    		<h5>
							    			<?php if(!empty($repost["url"])) {?>
											<a href="<?= $repost["url"]?>"><?= $repost["tipo"] ?></a>
											<?php }else{ ?>
												<?= $repost["tipo"] ?>
											<?php } ?>
										</h5>
										<p class="text-justify"><?= (!empty($repost["precio"])) ? "$ ".number_format($repost["precio"], 0, '.', ','): "" ?></p>
										<p class="text-justify">
											<?php 
											$globalUrl = "/(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/\S*)?/";
											if ($menciones = $publicacion ->getMentions($repost["texto"]) ) 
											{
												if(preg_match($globalUrl, $repost["texto"], $url)) //solo con http
												{
													echo preg_replace($globalUrl, "<a href='{$url[0]}' target='_blank'>{$url[0]}</a>", $menciones);
												}
												else
												{
													echo $menciones;
												}
											}
											if ($repost["linkStatus"]) //si existe un link
											{
												$youbelink = strpos($repost["linkStatus"],'youtube.com');
												if($youbelink !=false){ ?>
													<div class='iframe-container'>
														<iframe width='560' height='315' src='<?=$repost["linkStatus"]?>' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
													</div>
													<?php
												}
												if($repost["metas"]!=NULL)
												{
													$json64= base64_decode($repost["metas"]);
													$metaUrls=json_decode($json64,true);
													list($width, $height) = getimagesize($metaUrls['og:image']);
											        if (isset($width) && $width > 500) { ?>
											            <div class="meta-st" onclick="window.open('<?=$metaUrls['og:url']?>','_blank')">
									                        <img class="full" src="<?=$metaUrls['og:image']?>">
								                            <div class="meta-foot">
								                                <p class="link"><?=$metaUrls['og:url']?></p>
								                                <h4><?=$metaUrls['og:title']?></h4>
								                                <p class="descr"><?=$metaUrls['og:description']?></p>
								                            </div>
								                        </div>
											        <?php }
											        else
											        { ?>
											            <div class="meta-st" onclick="window.open('<?=$metaUrls['og:url']?>','_blank')">
									                        <img class="half" src="<?=$metaUrls['og:image']?>">
							                                <div class="half-foot">
							                                    <p class="link"><?=$metaUrls['og:url']?></p>
							                                    <h4><?=$metaUrls['og:title']?></h4>
							                                    <p class="descr"><?=$metaUrls['og:description']?></p>
							                                </div>
								                        </div>
											        <?php }
											    }
											} ?>
										</p>
							    	</div>
									
									<div class="gallery">
										<?php
										$imagenes=array();
										if($repost["imagenes"]!="")
										{
											$imagenes=json_decode(base64_decode($repost["imagenes"],true));
											$num_imgs=sizeof($imagenes);
											$mas=false;
											if($num_imgs>4){
												$num_imgs=3;
												$mas=true;
											}
										}
										if($repost["tipoId"]==8){
											$imagenes=array("/img/portada.jpg");
											$num_imgs=1;
											$mas=false;
										}
										if(!empty($imagenes))
										{
											?>
										<div id="" class="photo-container <?= ($num_imgs>1) ? "multiple" : "" ?>" data-photos="<?= $repost["imagenes"]?>">
											<div class="main <?= ($num_imgs==1) ? "only" : "" ?>" style="<?= ($num_imgs>1) ? "background-image: url('".$imagenes[0]."')" : "" ?> " data-index="0">
												<?= ($num_imgs==1) ? "<img src='".$imagenes[0]."' />" : "" ?>
											</div>
							  				
								  			<?php for($k=1; $k<$num_imgs; $k++){ ?>
							  					<div class="other-imgs" style="background-image: url('<?= $imagenes[$k] ?>') " data-index="<?= $k ?>">
								  				</div>
								  			<?php } 
								  			if($k<4&&$mas){ ?>
								  				<div class="other-imgs" style="background-image: url('<?= $imagenes[3] ?>') " data-index="3">
								  					<div>
								  						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_home_boton_verMAS-blanco_infotraffic.png">	
								  					</div>
								  					
								  				</div>
								  			<?php }?>
										</div>
										<?php
										}
										?>
									</div>
									
							    </div>	
						<?php 
								break;
							case 5: 
								if(empty($shareContent["object"])){
									break;
								} 
								else
								{ 
								?>
								<div class="advertisement-post">
										
									<div id="slider-auto" class="carousel" data-ride="carousel">
										<?= (isset($shareContent["object"]["auto"]["marca"])) ? '<img class="logo" src="'.$shareContent["object"]["auto"]["img"].'">' : "" ?>
										<div class="header-ad">
											<p class="submarcaVenta"><?= (isset($shareContent["object"]["auto"]["marca"])) ? "" : $shareContent["object"]["nombreMarca"] ?> <?= (isset($shareContent["object"]["auto"]["submarca"])) ? $shareContent["object"]["auto"]["submarca"] : $shareContent["object"]["nombreSubmarca"] ?> <?= (isset($shareContent["object"]["auto"]["modelo"])) ? $shareContent["object"]["auto"]["modelo"] : $shareContent["object"]["nombreModelo"] ?></p>
										</div>
										<div class="footer-ad">
											<div class="back1"></div>
											<div class="back2"></div>
											<p class="precioVenta"> <?=$shareContent["object"]["currency"]=='EUR'? '&#128;' : '$'?> <?=number_format($shareContent["object"]["precio"], 0, '.', ',').' '.$shareContent["object"]["currency"]?></p>
										</div>
										
										
						  				<ol class="carousel-indicators">
							  			<?php 
							  			if(empty($shareContent["object"]["imagenes"])){
							  				$shareContent["object"]["imagenes"][]="/img/noimage.png";
							  			}
							    		foreach ($shareContent["object"]["imagenes"] as $img => $i) {
							    		?>
						    				<li data-target="#slider-auto" data-slide-to="<?= $img ?>" class="<?= ($img == 0) ? "active" : "" ?>"></li>
										<?php
									  		
								  		}
								  		?>
							  			</ol>			
										<div class="carousel-inner" role="listbox">
										<?php 
							    		foreach ($shareContent["object"]["imagenes"] as $img => $i) {
						    				?>
					    					<div class="item <?= ($img == 0) ? "active" : "" ?>">
					    						<img class="center-block imagen-publicacion pointer" src="<?= $i?>" alt="<?=$img?>" onclick="setTimeout(function(){openPublishSlide();currPublishSlide(<?=$img?>)},200);">
											</div>

										<?php }
										?>
										
										</div>
									</div>
									<div class="advertisement-footer">
										<?php if (isset($shareContent["object"]["texto"])) { ?>
											<p class="text-justify text"><?= $shareContent["object"]["texto"] ?></p>
										<?php } ?>
										<p class="link" > </p>
										<a href="/anuncio/?a=<?= $shareContent["object"]["link"] ?>">
											M&aacute;s informaci&oacute;n
										</a>
										<?php if($_SESSION["iduser"]!=$shareContent["object"]["ownerid"] && $shareContent["object"]["estatus"]!=2 )
										{ 
											$idUserSession=$_SESSION["iduser"];
										 	$coderAd = new Coder($shareContent["object"]["idAd"]);
											$numLikes = $Like->countLikes(5, $shareContent["object"]["idAd"]);
											if(!$Like->alreadyLike($idUserSession, 5, $shareContent["object"]["idAd"])){ ?>
								    		<p class="pp-adSale pointer" onclick="like($(this), '<?= $coderAd->encoded?>', 5)">
								    			Me Interesa
								    		</p>
								    		<?php } 
								    		else
								    		{ ?>
								    		<p class="pp-adSale pointer" onclick="unlike($(this), '<?= $coderAd->encoded ?>', 5)">
								    			Ya no me interesa
								    		</p>
								    		<?php 
								    		}
									    }?>
									</div>
								</div>
						<?php
								break;
								}
						}
					} ?>
		    	</div>
				
				<div class="gallery">
					<?php
					$imagenes=array();
					if($publication["imagenes"]!="")
					{
						$imagenes=json_decode(base64_decode($publication["imagenes"],true));
						$num_imgs=sizeof($imagenes);
						$mas=false;
						if($num_imgs>4){
							$num_imgs=3;
							$mas=true;
						}
					}
					if($publication["tipoId"]==8){
						$imagenes=array("/img/portada.jpg");
						$num_imgs=1;
						$mas=false;
					}
					if(!empty($imagenes))
					{
						?>
					<div id="" class="photo-container <?= ($num_imgs>1) ? "multiple" : "" ?>" data-photos="<?= $publication["imagenes"]?>">
						<div class="main <?= ($num_imgs==1) ? "only" : "" ?>" style="<?= ($num_imgs>1) ? "background-image: url('".$imagenes[0]."')" : "" ?> " data-index="0">
							<?= ($num_imgs==1) ? "<img src='".$imagenes[0]."' />" : "" ?>
						</div>
		  				
			  			<?php for($k=1; $k<$num_imgs; $k++){ ?>
		  					<div class="other-imgs" style="background-image: url('<?= $imagenes[$k] ?>') " data-index="<?= $k?>">
			  				</div>
			  			<?php } 
			  			if($k<4&&$mas){ ?>
			  				<div class="other-imgs" style="background-image: url('<?= $imagenes[3] ?>') " data-index="3">
			  					<div>
			  						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_home_boton_verMAS-blanco_infotraffic.png">	
			  					</div>
			  					
			  				</div>
			  			<?php }?>
					</div>
					<?php
					}
					?>
				</div>
				<div class="publication-menu">
					<ul>
						<?php 
						if(!$Like->alreadyLike($_SESSION["iduser"], 4, $publication["idPublicacion"])){ ?>
						<li class="bodyLike" data-likes=".countLikes" <?= ($_SESSION["iduser"]>0)  ? 'onclick="like($(this),\''.$postCoded.'\', 4)"' : "" ?>>	 
							<img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" class="navigation-icon"> 
							Me gusta
						</li>
						<?php }else{ ?>
						<li class="bodyLike" data-likes=".countLikes" onclick="unlike($(this),'<?= $postCoded?>', 4)">		
							<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" class="navigation-icon"> 
							Me gusta
						</li>
						<?php } 
						?>
						<li <?= ($_SESSION["iduser"]>0)  ? 'onclick="goComment($(this));"' : "" ?> data-last="0" data-total="<?= $publication["comentarios"]?>" class="doCommentBtn doCom-<?= $postCoded?>" data-p="<?= $postCoded?>">
							<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/comment_<?= ($publication["icomment"]>0 ? "yellow" : "white")?>.png" alt="" >
							Comentar
						</li>
						<li>
							<p <?= ($_SESSION["iduser"]>0)  ? 'onclick="shareThis($(this))"' : "" ?>>
								<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2<?= ($publication["ishare"]>0 ? "-SELECTED" : "")?>_infotraffic.png" alt="">
								Compartir
							</p>
							<ul class="navigation-list">
								<?php
								if($shareFather){
									$coder->encode($shareFather);
									$shareFather=$coder->encoded;
								}
								$coder->encode($toShare);
								$toShare=$coder->encoded;
								?>
								<li onclick="doShare($(this),<?= $typeSharing ?>)" <?= ($shareFather) ? "data-f='$shareFather'" : "" ?> data-p="<?= $toShare ?>">En AVI cars</li>
								<li onclick="doShareWhatsApp($(this))" data-target="<?= $link ?>">En WhatsApp</li>
								<li data-target="<?= $link ?>" onclick="copyShare(this,$(this))">Copiar link </li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="publication-comments">
					<ul></ul>
				</div>	
		    </div>
			
		</div>
		<div class="row space">
			<div class="publication">
				<ins class="adsbygoogle" style="display:block" data-ad-format="fluid" data-ad-layout-key="-6t+ed+2i-1n-4w" data-ad-client="ca-pub-9121262896556692" data-ad-slot="4906998202"></ins>
			</div>
		</div>
	</div>
	<div class="content content-inpage" id="posts"></div>
	<script>

         (adsbygoogle = window.adsbygoogle || []).push({});

    </script>
	<script type="text/javascript">
		var lastPost=0;
		var search=true;
		var s="<?= ($publication["cuentaAutor"]=="") ? 'ca' :  'ga'?>";
		var u='<?= ($publication["cuentaAutor"]=="") ? $userAuthorCoded : $garageAuthorCoded ?>';
	</script>
	<script type="text/javascript" src="/js/post.js?l=<?= LOADED_VERSION?>"></script>
	<?php
	if($publication["cuentaAutor"]==96||$publication["cuentaDestino"]==96){ 
		$segurosModal=array(
			"nombre" => "",
			"apellido" => "",
			"mail" => "",
			"edad" => "",
			"cp" => "",
			"telefono" => ""
		);
		if($_SESSION["iduser"]>0){
			$detalle=$Usuario->getUserdetail($_SESSION["iduser"]);
			$nac=date_create($detalle["fechaNacimiento"]);
			$today=date_create("now");
			$diff=date_diff($nac,$today);
			$edad=$diff->format("%y");
			$segurosModal=array(
				"mail" => $detalle["o_avi_user_email"],
				"edad" => $edad,
				"cp" => $detalle["a_avi_useraddress_zip_code"],
				"nombre" => $detalle["o_avi_userdetail_name"],
				"apellido" => $detalle["o_avi_userdetail_last_name"],
				"telefono" => $detalle["phone"]
			);
		}
		?>
		<div class="modal fade" id="modalSellSeguros">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<img class="promo" src="/img/promomodal_400.png">
						<img class="promo-mv" src="/img/promomodal_400.png">
						<div class="promo-modal">
							<div class="promo-modal-title">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Cotiza tu seguro con nosotros
							</div>
							<div class="gracias hidden">
								<p>Muchas gracias por cotizar tu seguro con nosotros.</p>
								<p>En breve uno de nuestros asesores se pondr&aacute; en contacto contigo.</p>
								<button id="buttonFinishModalSeguros">Cotizar nuevamente</button>
							</div>
							<ul id="steps-all" class="cotizarModal">
								<li class="active" data-name="auto" data-id="0">
									<img src="/img/icons/modalSegurosAuto.png">
									Auto
									<div class="top-first"></div>
									<div class="bottom-first"></div>
								</li>
								<li data-name="contacto" data-id="1">
									<img src="/img/icons/modalSegurosContacto.png">
									<div class="top-second"></div>
									<div class="bottom-second"></div>
									Cont&aacute;cto
								</li>
							</ul>
							<div class="progress cotizarModal">
								<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
							</div>
							<form id="formpromomodal" class="cotizarModal">
								<div class="step active" data-name="auto" data-id="0">
								<?php if($_SESSION["iduser"]>0){ ?>
									<div class="form-group selectdiv col-xs-12">
										<select class="form-control form-style" id="autoSegurosModal" name="autos">
											<option value="0">Selecciona un auto</option>
										<?php 
										$allAutosForSeguros=$Garage->getCarsEditableByUser($_SESSION["iduser"],true,false);
										foreach ($allAutosForSeguros as $a => $auto) { 
											$selected="";
											$coder->encode($auto["i_avi_account_car_id"]);
											$autoCodedSelect=$coder->encoded;
											if($currAuto==$auto["i_avi_account_car_id"]){
												$selected="selected";
											}
											?>
											<option <?= $selected ?> value="<?= $autoCodedSelect?>"><?= $auto["alias"]?>. Garage: <?= $auto["garageName"]?><?= (!$auto["propio"]) ? " de ".$auto["garageOwner"] : ""?></option>
										<?php } ?>
											<option value="-1">Otro Auto</option>
										</select>
									</div>
								<?php } ?>
									<div class="form-group selectdiv col-xs-12 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style" id="marcaSegurosTimeline" name="marca" onchange="changeMarcaSegurosTimeline($(this))">
											<option class="visible" value="0">MARCA</option>
											<?php 
											foreach ($marcas as $m => $marca) 
											{ 
												if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO')
												{ ?>
													<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
													<?php 
												}
											} ?>
											<option class="visible" value="-1">Otra Marca</option>
										</select>
										<div class="otraMarca hidden">
											<input type='text' class='form-control form-style' placeholder="Especifica" id='otraMarcaInputSegurosTimeline' name='otraMarcaInput' value='' placeholder="Otra Marca"/>
										</div>
									</div>
									<div class="form-group selectdiv col-xs-6 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style submarca" id="modeloSegurosTimeline" name="submarca" onchange="changeSubmarcaSegurosTimeline($(this))">
											<option value="0">MODELO</option>
										<?php 
										foreach ($submarcas as $sm => $submarca) 
										{  ?>
											<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
										<?php 
										} ?>
											<option class="visible" value="-1">Otro Modelo</option>
										</select>
										<div class='otroModelo hidden'>
											<input type='text' class='form-control form-style' placeholder="Especifica" id='otroModeloInputSegurosTimeline' name='otroModeloInput' value='' placeholder="Otro Modelo"/>
										</div>
									</div>
									<div class="form-group selectdiv col-xs-6 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style" id="anoSegurosTimeline" name="modelo" onchange="changeAnoSegurosTimeline($(this))">
											<option value="0">A&Ntilde;O</option>
											<?php 
											foreach ($modelos as $md => $modelo) 
											{ ?>
												<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
											<?php 
											} ?>
											<option class="visible" value="-1">Otro a&ntilde;o</option>								
										</select>
										<div id='otheryearSegurosTimeline' class='otroAno hidden'>
											<input type='text' class='form-control form-style' placeholder="Especifica" id='otroAnoInputSegurosTimeline' name='otroAnoInput'  value='' placeholder="Otro A&ntilde;o"/>
										</div>
									</div>
									<div class="form-group selectdiv col-xs-12 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style" id="versionSegurosTimeline" name="subnombres" onchange="changeVersionSegurosTimeline($(this))">
											<option value="0">VERSI&Oacute;N</option>
										<?php 
										foreach ($versiones as $vr => $version) 
										{ 
										?>
											<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" > <?= $version["version"]?> <?= $version["subnombre"]?> </option>
										<?php 
										} ?>
											<option class="visible" value="-1">Otra Versi&oacute;n</option>		
										</select>
										<div id='otherverSegurosTimeline' class='otraVersion hidden'>
											<input type='text' maxlength="50" class='form-control form-style' placeholder="Especifica" id='otroVersionInputSegurosTimeline' name='otroVersionInput' value=''placeholder="Otra Versi&oacute;n"/>
										</div>
									</div>
								</div>
								<div class="step" data-name="contacto" data-id="1">
									<?php if($_SESSION["iduser"]==0){ ?>
									<span class="continue-with">Continuar con...</span>
									<ul class="icons-login-continue">
										<li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fb_infotraffic.png" id="facebookBtnLogin">
				                            <div id="fbLink"  class="fb-login-button facebook-btn" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" login_text="Facebook" scope="public_profile,email" onlogin="checkLoginStateModal();" href="javascript:void(0);"></div>
				                        </li>
				                         <li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fotraffic.png" data-target="#inicieSesionSeguros" data-toggle="modal">
				                        </li>
				                        <li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_google_infotraffic.png">
				                            <div class=" btn g-signin2" id="loginG" data-onsuccess="onSignInModal"></div>
				                            
				                        </li>
				                        <li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_twitter_infotraffic.png" id="twitterBtnLogin" onclick='signInTwitter();'>
				                        </li>
	            
									</ul>
									<?php } ?>	
									<div class="form-group col-xs-6">
										<input type="text" name="nombre" class="form-control form-style" placeholder="Nombre(s)" value="<?= $segurosModal["nombre"]?>">
									</div>
									<div class="form-group col-xs-6">
										<input type="text" name="apellido" class="form-control form-style" placeholder="Apellido(s)" value="<?= $segurosModal["apellido"]?>">
									</div>
									<div class="form-group col-xs-12">
										<input type="text" name="mail" class="form-control form-style" placeholder="Correo Electr&oacute;nico" value="<?= $segurosModal["mail"]?>">
									</div>
									<div class="form-group col-xs-6">
										<input type="text" name="edad" class="form-control form-style" placeholder="Edad (A&ntilde;os)" maxlength="2" value="<?= $segurosModal["edad"]?>">
									</div>
									<div class="form-group col-xs-6">
										<input type="text" name="cp" class="form-control form-style" placeholder="C&oacute;digo Postal" maxlength="5" value="<?= $segurosModal["cp"]?>">
									</div>
									<div class="form-group col-xs-12">
										<input type="text" name="telefono" maxlength="10" class="form-control form-style" placeholder="Tel&eacute;fono (8 a 10 d&iacute;gitos)" value="<?= $segurosModal["telefono"]?>">
									</div>
									<?php if($_SESSION["iduser"]==0){ ?>
									<div class="checkbox col-xs-12 cretaeaccountmodal">
										<label class="checkbox"><input type="checkbox" name="login" checked="true">Deseo crear una cuenta en AVIcars</label>
									</div>
									<?php } else {
										$allGaragesMS = $Garage->account($_SESSION["iduser"]);
										$garageColaboradorMS = $Garage->colaboratingGarage($_SESSION["iduser"]); ?>
									<div class="form-group selectdiv col-xs-12 hidden" id="saveCarInGarageModalSeguros">
										<label class="control-label">En qu&eacute; garage quieres guardar el auto?</label>
										<select class="form-control form-style" id="garageNewCarSeguros" name="garage">
											<?php 
											foreach ($allGaragesMS as $keyAllGarages => $someOfAllGarage) 
											{
												$coder->encode($someOfAllGarage["idAccount"]);
												$codeGarage=$coder->encoded;
												$selected="";
												if($actualGarage==$someOfAllGarage["idAccount"]){
													$selected="selected";
												}
												?>
												<option class='visible' value='<?= $codeGarage?>' <?= $selected?>><?=$someOfAllGarage["nameAccount"]?></option>
												<?php
											}
											foreach ($garageColaboradorMS as $keyColaborating => $colaborating) {
												if($colaborating["nivel"]<3){
													$coder->encode($colaborating["idAccount"]);
													$codeGarage=$coder->encoded;
													$selected="";
													if($actualGarage==$colaborating["idAccount"]){
														$selected="selected";
													}
													?>
													<option class='visible' value='<?= $codeGarage?>' <?= $selected?>><?=$colaborating["nameGarage"]?> de <?=$colaborating["ownerName"]?> <?=$colaborating["ownerLastName"]?></option>
													<?php
												}
											}
											?>
											<option class="visible" value="0">Nuevo Garage</option>								
										</select>
									</div>
									<?php } ?>
								</div>
							</form>
							<div class="clearfix cotizarModal"></div>
							<hr class="first cotizarModal">
							<hr class="cotizarModal">
							<p class="legend cotizarModal">Los campos marcados con (*) son obligatorios</p>
							<p class="advise cotizarModal"><a href="/Terminos_y_condiciones_AVIcars.pdf">AVISO DE PRIVACIDAD</a></p>
							<div class="promo-modal-footer cotizarModal">
								<ul>
									<li class="prev">
										<div class="top-first"></div>
										<div class="bottom-first"></div>
										<button type="button" clss="prev">Anterior</button>
										<div class="top-second"></div>
										<div class="bottom-second"></div>
									</li>
									<li >
										<div class="top-first"></div>
										<div class="bottom-first"></div>
										<button type="button" class="sig">Siguiente</button>
										<button type="button" class="last hidden">Enviar</button>
										<div class="top-second"></div>
										<div class="bottom-second"></div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='modal fade' id='modalSuccessModalSeguros' role='dialog'> 
		    <div class="modal-dialog">
		        <div class="modal-content modal-login">
		            <div class="nopad-login title-header modal-header">
		                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h2>Tu Seguro con ApoyoVial</h2>
		            </div>
		            <div class="modal-body-login modal-body">
		                <p id="messageSuccessModalSeguros"></p>
		            </div>
		            <div class="footer-line modal-footer">
		                <button id="iniciarModalSeguros" type="submit" class="btn modal-btns-login" data-dismiss="modal" aria-label="Close"> 
		                    Cerrar
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
		<div class='modal fade' id='inicieSesionSeguros' role='dialog'> 
		    <div class="modal-dialog">
		        <div class="modal-content modal-login">
		            <div class="nopad-login title-header modal-header">
		                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h2>&iexcl;INICIA SESI&Oacute;N!</h2>
		            </div>
		            <div class="modal-body-login modal-body">
		                <form onsubmit="return false;" class="form-size">
		                    <div id="in_usernameModal" class="form-group username-mrg">
		                        <input type="text" class="form-control form-style" name="logInUsernameModal" id="logInUsernameModal" onkeypress="iniciarpressModalSeguros(event)" placeholder="Usuario / Email" maxlength="50">
		                    </div>
		                    <div id="in_pwdModal" class="form-group pwd-mrg">
		                        <input type="password" class="form-control form-style" name="logInPasswordModal" id="logInPasswordModal" onkeypress="iniciarpressModalSeguros(event)" placeholder="Contrase&ntilde;a" maxlength="60">
		                        <p class="contra"><a class="a-contra" href="#" data-toggle="modal" data-target="#pwdModal">Olvid&eacute; mi contrase&ntilde;a</a></p>
		                    </div>
		                </form> 
		            </div>
		            <div class="footer-line modal-footer">
		                <button id="iniciarModalSeguros" type="submit" class="btn modal-btns-login" onclick='conectarModalSeguros()' > 
		                    Iniciar Sesi&oacute;n 
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
		<div class='modal fade' id='setPasswordSeguros' role='dialog'> 
		    <div class="modal-dialog">
		        <div class="modal-content modal-login">
		            <div class="nopad-login title-header modal-header">
		                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h2>Indica tu nueva contrase&ntilde;a</h2>
		            </div>
		            <div class="modal-body-login modal-body">
		                <form onsubmit="return false;" class="form-size" id="formSendPromoSegurosPwd">
		                    <div id="in_usernameModal" class="form-group username-mrg">
		                        <input type="password" class="form-control form-style" name="firstPassword" id="firstPassword" onkeypress="setPasswordSeguros(event)" placeholder="Contrase&ntilde;a" maxlength="60">
		                    </div>
		                    <div id="in_pwdModal" class="form-group pwd-mrg">
		                        <input type="password" class="form-control form-style" name="secondPassword" id="secondPassword" onkeypress="setPasswordSeguros(event)" placeholder="Repite la Contrase&ntilde;a" maxlength="60">
		                    </div>
		                </form> 
		            </div>
		            <div class="footer-line modal-footer">
		                <button id="sendModalPromoSeguros" type="submit" class="btn modal-btns-login" onclick='validatePasswords()'> 
		                    Enviar
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#modalSellSeguros").modal("show");
			})

		</script>
		<?php
		if(isset($_SESSION["carModal"])){ ?>
			<script type="text/javascript">
				var carModalSeguros=<?= json_encode($_SESSION["carModal"])?>;
				var mailModalSeguros="erikfer94@gmail.com";
				var nameModalSeguros="Erik";
				
			</script>
		<?php 
			$twitter=false;
		}
		?>
		<script type="text/javascript" src="/js/ventaSeguros.js"></script>
	<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/forgotPassword.php';
	} 
	
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
}else{?>

	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
	<?php
}?>