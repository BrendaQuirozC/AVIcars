<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-19 17:33:32
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-15 11:29:39
 */
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
$coder = new Coder();
$Like = new Like;
$Garage = new Garage;
$coder->decode($_POST["c"]);
$cuenta=$coder->toEncode;
$garages = $Garage -> colaboratingGarageByTen($cuenta,$_POST["t"]);
$seguidorGarages = new Seguidor(2);
$owner=($_SESSION["iduser"]==$cuenta);
if(!$owner){
	header('HTTP/1.0 403 Forbidden');
	exit;
}
foreach ($garages as $llaveGarage => $garage)
{		
	$coder->encode($garage["idAccount"]);
	$garageEncoded=$coder->encoded;
	$coder->encode($garage["userOwner"]);
	$garageOwnerEncoded=$coder->encoded;
	$followingGarage=array();
	if(!$owner)
	{
		$followingGarage=$seguidorGarages->followingTo($_SESSION["iduser"], $garage["idAccount"]);
	}
	$garageInfo = $Garage ->accountById($garage["idAccount"]);
	if($owner || ((isset($garageInfo["privacidad"])) ? $garageInfo["privacidad"] : 1)!=3 || (!empty($followingGarage) && $followingGarage["aceptado"]==1))
	{
		$garageContain = $Garage ->accountInstancia($garage["idAccount"]);
		if(!$owner && $garageInfo["privacidad"]==3)
		{
			continue;
		} 
		else 
		{ ?>
			<div class="row space">
				<div class="col-xs-12 list-garage">
		    		<div class="list-garage-header img-garage">
		    			<?php
		    			if(empty($garage["avatar"]))
		    			{ ?>
		    				<img onclick="showGarage($(this))" data-garage='<?= $garageEncoded?>' data-usuario='<?= $garageOwnerEncoded?>' class="pointer img-profile" title="Garage vacio" src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/avatar_default.jpg" alt="">
		    			<?php	
		    			} else 
		    			{ ?>
		    				<div class="pointer img-profile" style="background-image: url('<?=$garage["avatar"]?>');" onclick="showGarage($(this))" data-garage='<?= $garageEncoded?>' data-usuario='<?= $garageOwnerEncoded?>'>
		    				</div>
		    				<?php 
		    			} ?>
		    			<div class="head-info">
			    			<div class="personal ellipsis-title" data-garage='<?= $llaveGarage?>'>
			    				<span class="pointer" onclick="showGarage($(this))" data-garage='<?= $garageEncoded?>' data-usuario='<?= $garageOwnerEncoded?>'> <?= $garage["nameAccount"]?></span>
			    				<img src="<?= ($garageInfo["privacidad"]==1) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_infotraffic.png" : (($garageInfo["privacidad"]==2) ? "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_publico.png" : "/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/candado_ojo.png") ?>" class="<?= ($garageInfo["privacidad"]==1) ? "private" : (($garageInfo["privacidad"]==2) ? "public" : "secret") ?>" >
			    			</div>
				    		<hr>
				    		<div class="time">
								<?= $garage["verified"] == 1 ? "<img class='icon-verified-small' src='/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/medalla_certificada.png' alt='cuenta verificada'>" : "" ?>
				    			<ul id="ulgarage" class="icon-bar">
				    				<li class="pointer">
				    					<span class="stopColaborating" onclick="toStopColaborating($(this))" data-u="<?= $_SESSION["usertkn"]?>" data-g="<?= $garageEncoded?>" data-toggle="modal" data-target="#deleteColaborador">Dejar de Colaborar</span>
				    				</li>
			    					<?php 
			    					$numLikes = $garage["likes"];
			    					if(!$owner)
			    					{
				    					if(!$Like->alreadyLike($_SESSION["iduser"], 2, $garage["idAccount"])){ ?>
				    					<li class="pointer" <?= (!$owner && (empty($followingGarage) || (!empty($following) && $followingGarage["aceptado"]==0)) ) ? 'onclick="seguirPerfil(\''.$garageEncoded.'\', 2);like($(this),\''.$garageEncoded.'\', 2)"' : 'onclick="like($(this), \''.$garageEncoded.'\', 2)"'?>>
				    						<span class="likes-garages"><?= $numLikes?></span>
				    						<img data-toggle="modal" class=" icon-size" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" alt="">
				    					</li>
				    					<?php }else{ ?>
			    						<li class="pointer" onclick="unlike($(this), '<?=$garageEncoded?>', 2)">
				    						<span class="likes-garages"><?= $numLikes?></span>
				    						<img data-toggle="modal" class=" icon-size" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" alt="">
				    					</li>
				    					<?php } 
			    					}
			    					else { ?>
			    					 	<li>
				    						<span class="likes-garages"><?= $numLikes?></span>
				    						<img data-toggle="modal" src=<?= $numLikes>0 ? "/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/favoritos_llanta_fire.png" :  "/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_downmenu_boton_favoritos2_infotraffic.png" ?> alt="">
				    					</li>
			    					<?php } ?>
			    					<li class="navigation-icon pointer" >
			    						<span><?= ($garage["shared"]>99) ? "+99" : $garage["shared"]?></span>
			    						<img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2<?= ($garage["shared"]>0 ? "-SELECTED" : "")?>_infotraffic.png" alt="" onclick="shareThis($(this))">

					    				<ul class="navigation-list">
											<li class="pointer" onclick="doShare($(this),2)" data-f="<?= $garageOwnerEncoded ?>" data-p="<?= $garageEncoded ?>">En AVI cars</li>
											<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$garageOwnerEncoded ."&garage=".$garageEncoded?>">En WhatsApp</li>
											<li class="pointer" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$garageOwnerEncoded ."&garage=".$garageEncoded?>" onclick="copyShare(this,$(this))">Copiar link </li>
										</ul>	
			    					</li>
			    				</ul>	
				    		</div>
			    		</div>
		    		</div>
		    	</div>
		    </div>
		<?php
		}		
	}
}
if(!empty($garages)){
?>

<div class="row space seemore" onclick="getGarages()">
	Ver Mas
</div>
<?php } ?>
