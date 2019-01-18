<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-09 12:26:28
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-17 09:52:06
 */
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
$coder = new Coder();
$Like = new Like;
$coder->decode($_POST["c"]);
$cuenta=$coder->toEncode;
$garage=null;
if(isset($_POST["g"])){
	$coder->decode($_POST["g"]);
	$garage=$coder->toEncode;
}
$Garage = new Garage;
$Version = new Version;
$autos = $Garage ->accountsByUserByTen($cuenta, $_POST["t"],$garage);
if(!isset($_SESSION["iduser"])) {
	$owner = false;
}
else{
	$owner=($_SESSION["iduser"]==$cuenta);
}
foreach ($autos as $key => $detailsCar)
{
	$coder->encode($detailsCar["i_avi_account_car_id"]);
	$carEncoded=$coder->encoded;
	$coder->encode($detailsCar["o_avi_account_user_id"]);
	$carOwnerEncoded=$coder->encoded;
	//print_r($detailsCar);
	$versionCar = $Version->feature($detailsCar["o_avi_car_version_id"]);
	//no es necesario guardar toda la ruta, modificar esto a futuro
	$imgenBase = basename($detailsCar["a_avi_car_img_car"]);
	$llaveGarage=$detailsCar["garage"];
	if(!$owner && $detailsCar["i_avi_account_car_privacy"]==3)
	{
		continue;
	} 
	else 
	{ ?>
		<div class="row space <?= ($detailsCar["vendido"]) ? "sold" : "" ?>">
			<div class="col-xs-12 car">
				<?= ($detailsCar["vendido"]) ? "<div class='sello' onclick='window.location.href=\"/perfil/autos/detalles/?cuenta=".$carOwnerEncoded."&auto=".$carEncoded."\"'></div>" : "" ?>
				
				<div class="car-header">
					<a class="car-img" href="/perfil/autos/detalles/?cuenta=<?= $carOwnerEncoded?>&auto=<?= $carEncoded?>" style="background-image: url('<?= ($imgenBase!==""&&$imgenBase!==NULL) ? $detailsCar["a_avi_car_img_car"] : "/img/noimage.png" ?>')">
						<?= $detailsCar["verificado"] == 1 ? "<img class='icon-verified-car' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?>
					</a>
					<div class="header-info">
						<div class="personal ellipsis-title">
							<a href="/perfil/autos/detalles/?cuenta=<?= $carOwnerEncoded?>&auto=<?= $carEncoded?>">
								<?= $detailsCar["alias"]?>
							</a>
							<img src="<?= ($detailsCar["i_avi_account_car_privacy"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png" : (($detailsCar["i_avi_account_car_privacy"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png") ?>" class="<?= ($detailsCar["i_avi_account_car_privacy"]==1) ? "private" : (($detailsCar["i_avi_account_car_privacy"]==2) ? "public" : "secret") ?>" alt="Privacidad"> 
						</div>
						<hr>
					</div>
					<div class="car-info">
						<ul id="ulgarage" class="icon-bar car">
							<?php 
							$numLikes=$detailsCar["likes"];
							if(!$Like->alreadyLike(isset($_SESSION["iduser"]) ? $_SESSION["iduser"] : 0, 3, $detailsCar["i_avi_account_car_id"])){ ?>
	    					<li class="pointer" <?php isset($_SESSION["iduser"]) ? 'onclick="like($(this),\''.$carEncoded.'\', 3)"' : '' ?>>
	    						<span class="likes-garages num-likes"><?= $numLikes?></span>
	    						<img data-toggle="modal" class="likes" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
	    					</li>
	    					<?php }else{ ?>
    						<li class="pointer" onclick="unlike($(this), '<?= $carEncoded?>', 3)">
	    						<span class="likes-garages num-likes"><?= $numLikes?></span>
	    						<img data-toggle="modal" class="likes" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
	    					</li>
	    					<?php } ?>
							<li class="navigation-icon pointer">
	    						<span class="num-shares"><?= ($detailsCar["shared"]>99) ? "+99" : $detailsCar["shared"]?></span>
	    						<img class="shares" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2<?= ($detailsCar["shared"]>0 ? "-SELECTED" : "")?>_infotraffic.png" alt="" onclick="shareThis($(this))">
	    						<ul class="navigation-list">
									<li class="pointer" <?= isset($_SESSION["iduser"]) && ($_SESSION["iduser"]>0)  ? 'onclick="doShare($(this),3)"' : 'onclick=window.location.href="/"' ?> data-f="<?= $carOwnerEncoded ?>" data-p="<?= $carEncoded ?>">En AVI cars</li>
									<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$carOwnerEncoded ."&auto=".$carEncoded?>">En WhatsApp</li>
									<li class="pointer" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/autos/detalles/?cuenta=".$carOwnerEncoded ."&auto=".$carEncoded?>" onclick="copyShare(this,$(this))">Copiar link </li>
								</ul>	
	    					</li>
						</ul>
						<?php
						if($detailsCar["a_avi_sell_car_status"])
						{
						?>
							<b>
								<p class="no-marg"><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyCars_Movil_viewport_features_icon-AUTOENVENTA_infotraffic.png" class="sell-icon-cars-mini" alt=""> 
									<span class="editVehiclePhotos">
										<?=$detailsCar["a_avi_sell_car_currency"]=='EUR' ? '&#128;' : '&#36;' ?>&ensp;<?= number_format($detailsCar["a_avi_sell_detaill_price"], 0, '.', ',').' '.$detailsCar["a_avi_sell_car_currency"]?>
									</span>
								</p>
							</b>
						<?php
						}
						?>
						<p class="no-marg">Marca: <?=$detailsCar["o_avi_car_name_brand"]?></p>
						<p class="no-marg">Modelo: <?=$detailsCar["o_avi_car_name_subbrand"]?></p>
						<p class="no-marg">A&ntilde;o: <?=$detailsCar["o_avi_car_name_model"]?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
if(!empty($autos)){
?>

<div class="row space seemore" onclick="getAutos()">
	Ver Mas
</div>
<?php } ?>