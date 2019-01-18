<?php

/**
 * @Author: erikfer94
 * @Date:   2018-10-08 16:56:52
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-15 09:45:49
 */
require_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/Anuncio.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$Anuncio = new Anuncio;
$busqueda=json_decode(file_get_contents('php://input'),true);
$autos=$Anuncio->advancedSearch($busqueda["list"],$busqueda["marca"],$busqueda["modelo"],$busqueda["ano"],$busqueda["clase"],$busqueda["desde"],$busqueda["hasta"],$busqueda["estado"],$busqueda["municipio"]);
?>
<?php
foreach ($autos as $a => $auto) { 
	$coder->encode($auto["ad_id"]);
	$link=$coder->encoded;
	$name="";
	if($auto["marca"]!="")
		$name.=$auto["marca"];
	if($auto["submarca"]!="")
		$name.=" ".$auto["submarca"];
	if($auto["modelo"]!="")
		$name.=" ".$auto["modelo"];
	if($auto["version"]!="")
		$name.=" ".$auto["version"];
	if($name==""){
		$name="Auto en Venta";
	}
	$img="/img/noimage.png";
	if($auto["img"]!=""){
		$img=$auto["img"];
	}
	?>
	<div class="search-element" onclick="window.location.href='/anuncio/?a=<?= $link ?>'">
		<div class="img-search-car" style="background-image: url('<?= $img?>')"></div>
		<p><?= $name?></p>
		<p class='text-green'>
			<img src='/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyCars_Movil_viewport_features_icon-AUTOENVENTA_infotraffic.png' class='sell-icon-cars'>
			<?= ($auto["moneda"]=="EUR") ? "&#128;" : "$" ?><?= number_format($auto["precio"],0)?> <?= $auto["moneda"]?>
		</p>
		<?php if($auto["cp"]!=""){ ?>
			<span style='font-weight:bolder; color:#00992c;'><?= $auto["ciudad"]?>, <?= $auto["estado_nombre"]?></span>
		<?php } ?>
		<?php if($auto["nameGarage"]!=""){ ?>
			<span>Garage: <?= $auto["nameGarage"] ?></span>
		<?php } ?>
	</div>
<?php }
if(!empty($autos)){ ?>
	<div class="text-center seemore" onclick="searchAdvanced()">Ver más</div>
<?php }elseif($busqueda["list"]==0){ ?>
	<div class="text-center "><h4>No se encontraron resultados</h4></div>
<?php } ?>	