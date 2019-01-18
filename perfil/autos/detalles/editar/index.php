<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-05-21 10:48:14
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-07 17:19:59
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["auto"])){
	header("Location: /");
}

require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once  $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once ($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
require_once  $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$autoEncoded=$_GET["auto"];
$coder->decode($_GET["auto"]);
$_GET["auto"]=$coder->toEncode;
$auto=new Auto;
$Garage = new Garage;
$Version = new Version; 
$Usuario = new Usuario; 
$Venta = new Venta;
$country=new Country;
$garageContain= $Garage-> instanciaById($_GET["auto"]);
$currMarca=null;
$curSubMarca=null;
$curModelo=null;
$claseCar=null;
$doors=null;
$fuel=null;
$phoneCodes=$country->getPhoneCodes();
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],2);
$colaboradorCont=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],3);
if(empty($garageContain))
{
	header("Location: /");
}
elseif($garageContain[0]["o_avi_car_version_id"])
{
	$versionCar = $Version->feature($garageContain[0]["o_avi_car_version_id"]);
	if(!empty($versionCar))
	{
		$autoObj=$versionCar[$garageContain[0]["o_avi_car_version_id"]];
		$currMarca=$autoObj["C_Vehicle_Brand_System_ID"];
		$curSubMarca=$autoObj["C_Vehicle_SubBrand_System_ID"];
		$curModelo=$autoObj["C_Vehicle_Model_System_ID"];
	}
}
else
{
	$currMarca=$garageContain[0]["brand"];
	$curSubMarca=$garageContain[0]["subbrand"];
	$curModelo=$garageContain[0]["model"];
}
$marcas=$auto->getMarcas();
$notImage=false;
$notCover=false;
$imagenes=$Garage->imagenesGenerales($_GET["auto"]);
$coverAuto =$Garage-> imagenPortada($_GET["auto"]);
$privacidad=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1;
if(empty($imagenes))
{
	$notImage='/img/noimage.png';
}
if(empty($coverAuto))
{
	$notCover='/img/noimage.png';
}
$fuel=$garageContain[0]["fuel"];
$doors=$garageContain[0]["doors"];
$ventanas=$garageContain[0]["ventanas"];
$interior=$garageContain[0]["interior"];
$colorCar = $garageContain[0]["o_avi_car_color"];
if ($colorCar) {
	$colorName= $Garage->getColorCar($colorCar);
}
$curVersion=$garageContain[0]["o_avi_car_version_id"];
$imgPerfil = $Garage->getImgPerfil($_GET["cuenta"]);
$claseCar=$garageContain[0]["clase"];
if ($claseCar) {
	$typeName= $auto->getTypeCar($claseCar);
}
$estadoAuto= $garageContain[0]["c_avi_car_state"];
$extras=json_decode($garageContain[0]["extras"],true);
$nameMarca = $garageContain[0]["nombreMarca"];
$nameSubmarca = $garageContain[0]["nombreSubmarca"];
$nameModelo = $garageContain[0]["nombreModelo"];
$nameVersion=$garageContain[0]["nombreVersion"];
$submarcas=$auto->getSubMarcas($currMarca);
$modelos=$auto->getModels($currMarca,$curSubMarca);
$versiones=$auto->knowVersion($curModelo);
$carpicture=$auto->imagenes();
$clases = $auto->getClass();
$motores = $auto->getEngineType();
$catalogoFuel = $auto->getTypeFuel();
$catalogoTrans = $auto->getTypeTrans();
$estados = $Venta-> stateCar();
$colores = $Venta->colorCar();
$instancia = array();
$cuenta  = $_GET["cuenta"];
$detalles = $Garage -> getUserdetail($cuenta);
$garages = $Garage -> account($_GET["cuenta"]);
$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"])); 
if ($auto->adCar($_GET["auto"])) {
	$adDetailCar=$auto->adCar($_GET["auto"]);
	$enVenta = isset($adDetailCar["idAnuncio"]);
	$conPrecio = $garageContain[0]["a_avi_sell_detaill_price"];
	if(!empty($adDetailCar))
		$metpagos=json_decode($adDetailCar["metodoPago"],true);
	else
		$metpagos=array();
}
$Like = new Like;

$metasShare=array(
	"og"	=>	array(
		"title" => "AVI cars by Infotraffic | Perfil",
	    "description" => "Auto ".$garageContain[0]["i_avi_account_car_alias"]." del Garage ".$garageContain[0]["o_avi_account_name"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
	    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
	    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
	    "site_name" => "AVI cars",
	    "type" => "website"
	),
	"tw"	=>	array(
		"title" => "AVI cars by Infotraffic | Perfil",
	    "description" => "Auto ".$garageContain[0]["i_avi_account_car_alias"]." del Garage ".$garageContain[0]["o_avi_account_name"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
	    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($garageContain)  && $garageContain[0]["avatar"]!="") ? $garageContain[0]["avatar"] : "/img/PORTADAgarage.jpg"),
	    "image:alt" => "AVI cars",
	    "card" => "summary_large_image"
	)
);
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if((!isset($garage) || $garage["user"]!=$_GET["cuenta"] || $garage["user"]!=$_SESSION["iduser"])&&!$colaborador)
{
	$privacidad=3; ?>
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
<?php
}
else
{
	$llaveGarage = $garageContain[0]["o_avi_account_id"];
	$coder->encode($llaveGarage);
	$llaveGarage=$coder->encoded;
	$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
	//include_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/headerAuto.php";
	?>
	<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
		<ul>
			<li> <a href="/perfil/autos/detalles/?cuenta=<?=$cuentaEncoded?>&auto=<?=$autoEncoded?>"><span> Timeline</span><img src="/img/webpageAVI/Movil_infotraffic/Followers_Movil_infotraffic/Followers_autos.png" class="navigation-icon"> </a></li>
			<?php if($owner||$colaborador){ ?>
	     	<li> <a href="/perfil/autos/detalles/docs/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><span> Expediente</span><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> </a></li>
	     	<li> <a href="/perfil/autos/detalles/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><span> Regresar</span><img src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_downmenu_boton_REGRESAR_infotraffic.png" class="navigation-icon" style="width: 22px;"> </a></li>
	     	<?php } ?>
	    </ul>
		<p><a href="https://apoyovial.net/acerca-de/" target="_blank">Acerca de</a></p>
		<p><a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">T&eacute;rminos y Condiciones</a></p>
		<p><a href="/buzon" target="_blank">Sugerencias</a></p>
		<p><a href="/Aviso_de_Privacidad_AVIcars.pdf" target="_blank">Aviso de Privacidad</a></p>
		<p><a href="/ayuda" target="_blank">Ayuda</a></p>
		<p class="marca">ApoyoVial&reg; 2018</p>
	</div>
	
	<div class="sidebar sidebar-no-header sidebar-right hidden-xs visible-sm visible-md visible-lg" id="sidebar">
		<p>
			<a href="/anunciate" target="_blank">
				<img src="/img/ads/promo_ad/<?= rand(1,3)?>.png">
				Clic aqu&iacute;
			</a>
		</p>
	</div>
	<div class="content content-ad form-send" style="position: relative; top: 100px;">
		<form id="fromEdit" enctype="multipart/form-data">	
			<div class="header-position garages-info">
	    		<div class="header-car">
					<div class="inverserow">
		    			<div id="check_marca" class="form-group selectdiv selecposition s-brand col-md-6 col-sm-6 col-lg-6 col-xs-6">
							<select class="form-control form-style" id="marca" name="marca" onchange="selectMarca()">
								<option class="visible" value="0">MARCA</option>
							<?php 
							$other="";
							foreach ($marcas as $m => $marca) 
							{ 
								$selected="";
								if($m==$currMarca)
								{
									$selected="selected";
								}
								if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO'){
							?>
								<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>" <?= $selected?>><?= $marca?></option>
							<?php 
								}
							} 
							if($currMarca==0 && $nameMarca!=NULL){ ?>
								<option class="visible" value="-1" selected>Otra Marca</option>
							<?php
							} else { ?>
								<option class="visible" value="-1">Otra Marca</option>
							<?php } ?>
							</select>
							<div class="otraMarca col-xs-12 hidden">
								<label class='control-label col-xs-12'>Especifica</label>
								<input type='text' maxlength="50" class='form-control' id='otraMarcaInput' name='otraMarcaInput' value='<?= $nameMarca ? $nameMarca : "" ?>'/>
							</div>
						</div>
						<div id="check_submarca" class="form-group selectdiv selecposition s-subbrand col-md-6 col-sm-6 col-lg-6 col-xs-6">
							<select class="form-control form-style submarca" id="modelo" name="submarca" onchange="selectSubmarca()">
								<option value="0">MODELO</option>
							<?php 
							foreach ($submarcas as $sm => $submarca) 
							{ 
								$selected="";
								if($submarca["id"]==$curSubMarca)
								{
									$selected="selected";
								}
							?>
								<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>" <?= $selected?>><?= $submarca["submarca"]?></option>
							<?php 
							} 
							if($curSubMarca==0 && $nameSubmarca!=NULL){ ?>
								<option class="visible" value="-1" selected>Otro Modelo</option>
							<?php
							} else { ?>
								<option class="visible" value="-1">Otro Modelo</option>
							<?php } ?>
							</select>
							<div class='otroModelo col-xs-12 hidden'>
								<label class='control-label otroModelo col-xs-12'>Especifica modelo</label>
								<input type='text' maxlength="50" class='form-control' id='otroModeloInput' name='otroModeloInput' value='<?= $nameSubmarca ? $nameSubmarca : "" ?>'/>
							</div>
						</div>
						<div class="form-group selectdiv selecposition s-model col-md-6 col-sm-6 col-lg-6 col-xs-6">
								<select class="form-control form-style" id="ano" name="modelo" onchange="selectModelo()">
									<option value="0">A&Ntilde;O</option>
								<?php 

								foreach ($modelos as $md => $modelo) 
								{ 
									$selected="";
									if($modelo["id"]==$curModelo)
									{
										$selected="selected";
									}
								?>
									<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>" <?= $selected?>> <?= $modelo["modelo"]?> </option>
								<?php 
								}
								if($curModelo==0 && $nameModelo!=NULL){ ?>
									<option class="visible" value="-1" selected>Otro A&ntilde;o</option>
								<?php
								} else { ?>
									<option class="visible" value="-1">Otro A&ntilde;o</option>
								<?php } ?>					
								</select>
								<div id='otheryear' class='otroAno col-xs-12 hidden'>
									<label class='control-label col-xs-12'>Especifica a&ntilde;o</label>
									<input type='text' maxlength="50" class='form-control' id='otroAnoInput' name='otroAnoInput' value='<?= $nameModelo ? $nameModelo : "" ?>'/>
								</div>
							</div>
					</div>
					<div class="brand-img">
						<img id="brandImg" src="" alt="" class="">
					</div>
	    			<p class="h-subbrand"><?=isset($submarcaName) ? $submarcaName : ''?> <?=isset($modeloAno) ? $modeloAno : ''?></p>
	    		</div>
				<div id="coverPhotoEdit" class="vignette" style='background-image: url("<?=(!$notCover) ? $coverAuto[0]['a_avi_car_img_car'] : $notCover ?>");'> </div>
				<div class="footer-caredit">
	    			<div id="check_version" class="form-group selectdiv selecposition s-version">
						<select class="form-control form-style" id="version" name="subnombres">
							<option value="0">VERSI&Oacute;N</option>
						<?php 
						foreach ($versiones as $vr => $version) 
						{ 
							$selected="";
							if($version["id"]==$curVersion)
							{
								$selected="selected";
							}
						?>
							<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" <?= $selected?>> <?= $version["version"]?> <?= $version["subnombre"]?> </option>
						<?php 
						}
						if($curVersion==0 && $nameVersion!=NULL){ ?>
							<option class="visible" value="-1" selected>Otra Versi&oacute;n</option>
						<?php
						} else { ?>
							<option class="visible" value="-1">Otra Versi&oacute;n</option>
						<?php } ?>
						</select>
						<div id='otherver' class='otherver o-version hidden'>
							<label class='control-label'>Especifica versi&oacute;n</label>
							<input type='text' maxlength="50" class='form-control' id='otroVersionInput' name='otroVersionInput' value='<?= $nameVersion ? $nameVersion : "" ?>'/>
						</div>
					</div>
	    		</div>
	    	</div>
	    	<?php if(isset($enVenta) && $conPrecio > 0)
			{ ?>
			<div class="list-garage">
				<table>
					<tr>
						<td>Precio</td>
						<td class="priceDiv">
							<div  id="check_price" class="table-form form-group">
								<input class="form-control form-style priceNF" onkeyup="addPriceFormat();" type="text" maxlength="9" id="formatPrice" value="<?= $garageContain[0]["a_avi_sell_detaill_price"]?>" onchange="minPriceEdit()">
								<input class="form-control form-style" type="hidden" maxlength="7" id="precio" name="precio" value="<?= $garageContain[0]["a_avi_sell_detaill_price"]?>" >
								<span class="alert-info hidden"></span>
								<select class="moneda" name="moneda" id="moneda_e">
									<option value="MXN" <?=$garageContain[0]["currency"]=='MXN' ? 'selected' : ''?>>MXN</option>
									<option value="USD" <?=$garageContain[0]["currency"]=='USD' ? 'selected' : ''?>>USD</option>
									<option value="EUR" <?=$garageContain[0]["currency"]=='EUR' ? 'selected' : ''?>>EUR</option>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td>Negociable</td>
						<td>
							<label class="radio-inline"><input type="radio" name="negociable" value="1" <?= isset($adDetailCar["negociable"]) && $adDetailCar["negociable"]=="1" ? "checked" : "" ?>>SI</label>
							<label class="radio-inline"><input type="radio" name="negociable" value="0" <?= isset($adDetailCar["negociable"]) && $adDetailCar["negociable"]=="0" ? "checked" : "" ?>>NO</label>						
						</td>
					</tr>
				</table>
			</div>
			<?php } ?>
			<!--ficha tecnica del auto-->
			<div class=" <?=($enVenta)==true && $conPrecio > 0 ? 'row' : ''?> rowChars">
				<?php if(isset($enVenta) && $conPrecio > 0)
				{ ?>
					<div class="eachCarRow buy-characteristics editLeft col-sm-6 col-md-6 col-lg-6 col-xs-12">
						<h5 class="pointer">
							<a class="text-ad-green" data-toggle="modal" data-target="#comprar">
							&plus; A&ntilde;adir Contacto y Formas de Pago
							<img class="icon-size-menu" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_viewport_features_infotraffic/MyCars_Movil_viewport_features_icon-COMPRA_infotraffic.png" alt="$"></a>
						</h5>
						<div id="check_anunciotext" class="divAd">
							<label>Texto del Anuncio:</label>
							<textarea name="anunciotext" id="anunciotext" class="form-control textAd" cols="30" rows="3" maxlength="320" placeholder="M&Aacute;XIMO 320 CARACTERES"><?= isset($adDetailCar["texto"]) ? $adDetailCar["texto"] : "" ?></textarea>
						</div>
					</div>
				<?php }?>
				<div class="eachCarRow editRight <?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6 col-xs-12' : ''?>">
					<div class="clearfix top-characteristics">
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
							<div class="char-icon-ad" id="get-classcar">
								<?php 
								if($claseCar){ 
									foreach ($clases as $cl => $clase) { 
										if($claseCar==$cl)
										{ ?>
									<input type='hidden' name='clasecar' value="<?= $claseCar?>">
									<img onclick='tipoClase()' class='clases-img-colors' src='<?= $clase["iconos"]?>' >
								<?php
										} 
									}
								}
								else{ ?>
									<img onclick='tipoClase()' class='clases-img-colors' src='/img/auto-icons/Monovolumen-36px.png' >
								<?php } ?>
							</div>
							<div class="form-group selectdiv selecposition typeDiv">
								<input class="form-control form-style text-left charDiv" id="classcar" onclick="tipoClase()" type="button" value="<?=isset($typeName) ? $typeName : 'Tipo'?>">
							</div>
						</div>
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
							<img class="char-icon-ad" src="/img/auto-icons/black_door.png">
							<div class="form-group selectdiv selecposition">
								<select class="form-control form-style charDiv" id="puertas" name="puertas">
									<option value="0">Puertas</option>
									<option value="2" <?=($doors == 2) ? 'selected' : ''?>>2 Puertas</option>
									<option value="4" <?=($doors == 4) ? 'selected' : ''?>>4 Puertas</option>
								</select>
							</div>
						</div>
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
							<img class="char-icon-ad" src="/img/auto-icons/black_gasoline.png">
							<div class="form-group selectdiv selecposition">
								<select class="form-control form-style charDiv" id="combustible" name="combustible">
									<option value="0">Combustible</option>
									<option value="1" <?=($fuel == 1) ? 'selected' : ''?>>Gasolina</option>
									<option value="2" <?=($fuel == 2) ? 'selected' : ''?>>Diesel</option>
									<option value="3" <?=($fuel == 3) ? 'selected' : ''?>>Etanol</option>
								</select>
							</div>
						</div>
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
							<img class="char-icon-ad" src="/img/auto-icons/black_car_seat.png">
							<div class="form-group selectdiv selecposition">
								<select class="form-control form-style charDiv" id="interior" name="interior">
									<?php echo $interior;?>
									<option value="0">Interiores</option>
									<option value="Tela" <?=($interior == "Tela") ? 'selected' : ''?>>Tela</option>
									<option value="Piel" <?=($interior == "Piel") ? 'selected' : ''?>>Piel</option>
									<option value="Imitaci&oacute;n Piel" <?=($interior == "Imitaci&oacute;n Piel") ? 'selected' : ''?>>Imitaci&oacute;n Piel</option>
									<option value="Piel con tela"> <?=($interior == "Piel con tela") ? 'selected' : ''?>Piel con tela</option>
									<option value="Piel con gamuza" <?=($interior == "Piel con gamuza") ? 'selected' : ''?>>Piel con gamuza</option>
									<option value="Vinipiel" <?=($interior == "Vinipiel") ? 'selected' : ''?>>Vinipiel</option>
								</select>
							</div>	
						</div>
					</div>
					<div class="clearfix bottom-characteristics">
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
							<img class="char-icon-ad" src='/img/auto-icons/tautomatica_b.png'>
							<div class="form-group selectdiv selecposition">
								<select class="form-control form-style charDiv" id="transmision" name="transmision">
									<option value="0">Transmisi&oacute;n</option>
									<?php foreach ($catalogoTrans as $ct => $trans) {
										$selected="";
										if($garageContain[0]["trans"]==$ct)
										{
											$selected="selected";
										}  ?>
									<option value="<?=$ct?>" <?= $selected?>> <?=$trans["nombre"]?> </option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-6">
							<img class="char-icon-ad" src="/img/auto-icons/black_motor.png">
							<div class="form-group selectdiv selecposition">
								<select class="form-control form-style charDiv" id="engineCar" name="engineCar">
									<option value="0">Motor</option>
									<?php foreach ($motores as $mt => $motor) {
										$selected="";
										if($garageContain[0]["engineType"]==$mt)
										{
											$selected="selected";
										}  ?>
									<option value="<?=$mt?>" <?= $selected?>><?=$motor?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="<?=($enVenta)==true && $conPrecio > 0 ? 'col-sm-6 col-md-6 col-lg-6' : 'col-sm-3 col-md-3 col-lg-3'?> col-xs-12 control-label pointer vermas-h" data-toggle="modal" data-target="#auto_editarmas">
							<p class="detailstab-plus detailstab-chars">
								&plus;&emsp;Editar m&aacute;s informaci&oacute;n
							</p>
						</div>
					</div>
				</div>
			</div>

			
			<h2 class="about">Sobre el Auto</h2>
			<div class="characteristics row rowChars">
				<div id="check_alias" class="form-group col-xs-12 col-md-6 ">
					<label>Alias: <span class="obligatorio asterisk">*</span></label>
					<input class="form-control form-style" name="alias" id="alias" placeholder="Alias o apodo para tu auto" maxlength="30" type="text" value="<?= $garageContain[0]["i_avi_account_car_alias"]?>">
				</div>
				<div class="form-group col-xs-12 col-md-6 " id="check_starsitas">
					<label>Estado del auto:</label> <span><?= isset($estadoAuto) ? $estadoAuto : ''?></span>
					<p>
					<?php 
					if($garageContain[0]["i_avi_account_car_state"]==1)
					{	for ($i=1; $i <= 5 ; $i++) 
						{ ?>
							<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star.png">
							<?php 
						}
					} 
					elseif ($garageContain[0]["i_avi_account_car_state"]==2)
					{
						for ($i=1; $i <= 4 ; $i++) 
						{ ?>
							<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star.png">
							<?php 
						} ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star_outline.png">
						<?php
					}
					elseif ($garageContain[0]["i_avi_account_car_state"]==3)
					{
						for ($i=1; $i <= 3 ; $i++) 
						{ ?>
							<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star.png">
							<?php 
						} 
						for ($i=1; $i <= 2 ; $i++) 
						{ ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star_outline.png">
						<?php }
					}
					elseif ($garageContain[0]["i_avi_account_car_state"]==4)
					{
						for ($i=1; $i <= 2 ; $i++) 
						{ ?>
							<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star.png">
							<?php 
						} 
						for ($i=1; $i <= 3 ; $i++) 
						{ ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star_outline.png">
						<?php }
					}
					elseif ($garageContain[0]["i_avi_account_car_state"]==5)
					{ ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star.png">
						<?php
						for ($i=1; $i <= 4 ; $i++) 
						{ ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star_outline.png">
						<?php }
					}
					elseif ($garageContain[0]["i_avi_account_car_state"]==6)
					{
						for ($i=1; $i <= 5 ; $i++) 
						{ ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star_outline.png">
						<?php }
					} else{ 
						for ($i=1; $i <= 5 ; $i++) 
						{ ?>
						<img onclick="changeEstado(<?=$i?>)" src="/img/auto-icons/black_star_outline.png">
						<?php }
					}?>
					</p>
				</div>
				<table class="stateFont hidden">
			        <?php
					$i=1;
					$tr="";
					$td="";
			        foreach ($estados as $keystate => $estado) {
			        	$checked="";
			        	if($garageContain[0]["i_avi_account_car_state"]==$keystate)
			        	{
			        		$checked="checked";
			        	}
			        	$td.="<td><input type='radio' name='estado' value='$keystate' $checked> $estado</td>";
			        	if($i==3)
			        	{
			        		$i=0;
			        		$tr.="<tr>".$td."</tr>";
			        		$td="";
			        	}
			        	$i++;
			        }
			        echo $tr;
			    	?>
				</table>
				<div id="check_kilometraje" class="form-group col-xs-12 col-md-6">
					<label>Kilometraje:</label>
					<input class="form-control form-style kmNF" onkeyup="addKmFormat();" type="text" maxlength="9" id="formatKm" value="<?= $garageContain[0]["o_avi_car_km"]?>" >
					<input class="form-control form-style" maxlength="7" name="kilometraje" id="kilometraje" type="hidden" value="<?= $garageContain[0]["o_avi_car_km"]?>">
				</div>
				<div class="col-xs-12 col-md-6">
					<label>Color:</label>
					<div class="colorColumn" id="get-colorcar">
						<?php if($garageContain[0]["o_avi_car_color"]){ 
							foreach ($colores as $colorkey => $color) { 
								if($garageContain[0]["o_avi_car_color"]==$colorkey){
								?>
								<input value="<?= $garageContain[0]["o_avi_car_color"]?>" type='hidden' name='color'><img onclick='colorCatalogo()' class='clases-img-colors'  src="<?=$color["img"]?>">
							<?php }
							}
						} else { ?>
							<img onclick='colorCatalogo()' class='clases-img-colors'  src="/img/colors/none.png">
						<?php } ?>
					</div>
					<div class="form-group selectdiv selecposition colorDiv">
						<input class="form-control form-style text-left" id="color" onclick="colorCatalogo()" type="button" value="<?=isset($colorName) ? $colorName : 'Color'?>">
					</div>
				</div>
			</div>
			<input type="hidden" id="auto" name="auto" value="<?= $autoEncoded?>">
			<input type="hidden" id="cuenta" name="cuenta" value="<?= $cuentaEncoded?>">

			<div id="auto_editarmas" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="title-header modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">M&aacute;s Carater&iacute;sticas</h4>
						</div>
						<div class="modal-body row">
							<div class="table-sC">
								<table class="moreChars">
									<tr>
										<td>Ventanas</td>
										<td>
											<div class="form-group selectdiv selecposition">
												<select class="form-control form-style" id="ventanas" name="ventanas">
													<option value="0">Ventanas</option>
													<option value="Manuales" <?=($ventanas == "Manuales") ? 'selected' : ''?>>Manuales</option>
													<option value="Electricos" <?=($ventanas == "Electricos") ? 'selected' : ''?>>El&eacute;ctricos</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td>Potencia</td>
										<td>
											<div id="check_potencia" class="form-group">
												<input class="form-control form-style" maxlength="3" name="potencia" id="potencia" type="text" value="<?= $garageContain[0]["potencia"]?>">
											</div>
										</td>
									</tr>
									<tr>
										<td>N&uacute;m de Pasajeros</td>
										<td>
											<div class="form-group selectdiv selecposition">
												<select class="form-control form-style" id="pasajeros" name="pasajeros" >
													<option value="0">Total de pasajeros</option>
													<?php
													for ($i=2; $i <11 ; $i++) { 
														$selected="";
														if($extras["interiores"]["num pasajeros"]==$i)
														{
															$selected="selected";
														}
														?>
														<option value="<?=$i?>" <?=$selected?>><?=$i?></option>
													<?php } ?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td>N&uacute;m de Filas de Asientos</td>
										<td>
											<div class="form-group selectdiv selecposition">
												<select class="form-control form-style" id="filas" name="filas" >
													<option value="0">Total de filas</option>
													<?php
													for ($i=2; $i <7 ; $i++) { 
														$selected="";
														if($extras["interiores"]["filas asientos"]==$i)
														{
															$selected="selected";
														}
														?>
														<option value="<?=$i?>" <?=$selected?>><?=$i?></option>
													<?php } ?>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td>N&uacute;m de&ensp;Due&ntilde;os Anteriores</td>
										<td>
											<div id="check_duenos" class="form-group km-style">
												<input class="form-control form-style" maxlength="3" name="duenos" id="duenos" type="text" value="<?= $garageContain[0]["dueno"]?>">
											</div>
										</td>
									</tr>
									<tr>
										<td>Factura a nombre de</td>
										<td>
											PERSONA F&Iacute;SICA <input type='checkbox' name='facturaPfisica' <?= $garageContain[0]["fpersonafisica"] ? "checked" : ""?>>&emsp;&emsp;
											ASEGURADORA <input type='checkbox' name='facturaAseguradora' <?= $garageContain[0]["faseguradora"] ? "checked" : ""?>>&emsp;&emsp;
											EMPRESA <input type='checkbox' name='facturaEmpresa' <?= $garageContain[0]["fempresa"] ? "checked" : ""?>>&emsp;&emsp;
											LOTE <input type='checkbox' name='facturaLote' <?= $garageContain[0]["flote"] ? "checked" : ""?>>
										</td>
									</tr>
									<tr>
										<td>Holograma</td>
										<td>
											<div class="form-group selectdiv selecposition">
												<select class="form-control form-style" id="holograma" name="holograma">
													<option value="-1">Tipo de holograma</option>
													<option value="e" <?=$garageContain[0]["hologram"] =="e" ? "selected" :""?>>Excento</option>
													<option value="00" <?=$garageContain[0]["hologram"] ==="00" ? "selected" :""?>>00</option>
													<option value="0" <?=$garageContain[0]["hologram"] ==="0" ? "selected" :""?>>0</option>
													<option value="1" <?=$garageContain[0]["hologram"] =="1" ? "selected" :""?>>1</option>
													<option value="2" <?=$garageContain[0]["hologram"] =="2" ? "selected" :""?>>2</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td>Garant&iacute;a(s)</td>
										<td>
											<label data-toggle="modal" data-target="#garantia" class="control-label pointer"><span class="icon-add"></span>&nbsp;&nbsp;&nbsp;&nbsp;A&Ntilde;ADIR GARANT&Iacute;A</label>
										</td>
									</tr>
									<tr>
										<td>Piezas faltantes</td>
										<td>
											<div class='agregar'> 
												<div id='check_faltantes' class='input-group'> 
													<input type = 'text' name='piesas[]' id="faltantes" value="<?=(isset($extras["piesas"]) && $extras["piesas"] != '') ? implode($extras["piesas"]) : '' ?>" class='form-control form-style pieceInput' placeholder='A&ntilde;adir' />  
												</div> 
								            </div>
										</td>	
									</tr>
									<tr>
										<td>Fallas menores</td>
										<td>
											<div class='agregar'>  
												<div id='check_fmenores' class='input-group'> 
													<input type = 'text' name='fallasmenores[]' id="fmenores" value="<?=(isset($extras["fallasmenores"]) && $extras["fallasmenores"] != '') ? implode($extras["fallasmenores"]) : ''?>" class='form-control form-style missingInput' placeholder='A&ntilde;adir' />
												</div> 
								            </div>
										</td>
									</tr>
									<tr>
										<td>Fallas mayores</td>
										<td>
											<div class='agregar'> 
												<div id='check_fmayores' class='input-group'> 
													<input type = 'text' name='fallasmayores[]' id="fmayores" value="<?=(isset($extras["fallasmayores"]) && $extras["fallasmayores"] != '') ? implode($extras["fallasmayores"]) : ''?>" class='form-control form-style missingGreaterInput' placeholder='A&ntilde;adir' />  
												</div> 
								            </div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="footer-line modal-footer">
							<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<div id="garantia" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Garant&iacute;a</h4>
						</div>
						<div class="modal-body row text-center">
							<div class="table-sC">
								<table>
									<tr>
										<td>F&Aacute;BRICA</td>
										<td>
											<div id="check_garantiaFabrica">
												<input class="form-control form-style" maxlength="100" id="garantiaFabrica" name="garantiaFabrica" value="<?= isset($extras["garantia"]["fabrica"]) ? $extras["garantia"]["fabrica"] : "" ?>" type="text" placeholder="Ej. 1 año, 3 meses, 30 dias, etc...">
											</div>
										</td>
									</tr>
									<tr>
										<td>
											VENDEDOR
										</td>
										<td>
											<div id="check_garantiaVendedor">
												<input class="form-control form-style" maxlength="100" name="garantiaVendedor" id="garantiaVendedor" value="<?= isset($extras["garantia"]["vendedor"]) ? $extras["garantia"]["vendedor"] : "" ?>" type="text" placeholder="Ej. 1 año, 3 meses, 30 dias, etc...">
											</div>
										</td>
									</tr>
									<tr>
										<td>
											USUARIO
										</td>
										<td>
											<div id="check_garantiaUsuario">
												<input class="form-control form-style" maxlength="100" id="garantiaUsuario" name="garantiaUsuario" value="<?= isset($extras["garantia"]["usuario"]) ? $extras["garantia"]["usuario"] : ""?>" type="text" placeholder="Ej. 1 año, 3 meses, 30 dias, etc...">
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-avi-white" data-dismiss="modal">cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<?php if(isset($enVenta) && $conPrecio > 0)
			{ ?>
			<div id="comprar" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="title-header modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						
						<div class="contacto-modal modal-body">
							<h4 class="text-center">CONTACTO DEL VENDEDOR</h4>
							<table class="table-sC">
								<tr>
									<td class="multi-input">
										<select class="form-control form-style" name="phonecode">
											<?php 
											foreach ($phoneCodes as $c => $code) { ?>
												<option value="<?= $c?>"><?= $c?> <?= $code?></option>
											<?php }
											 ?>
										</select>
										<input type="text" id="phone" name="phone" maxlength="10" class="form-control form-style fontPhone" placeholder="TEL&Eacute;FONO 1" value="<?= (!isset($adDetailCar["phone"]) && $detalles["o_avi_userdetail_phone"]) ? $detalles["o_avi_userdetail_phone"] : (isset($adDetailCar["phone"]) ? $adDetailCar["phone"] : "" ) ?>" >
									</td>
									<td align="center">
										<label class="checkbox fontWA">
											<input type="checkbox" name="phonewa" <?= ((!isset($adDetailCar["phonewa"])) ? "" : ($adDetailCar["phonewa"]==1) ? "checked" : "") ?>>
											Es WhatsApp
										</label>
									</td>
								</tr>
								<tr>
									<td class="multi-input">
										<select class="form-control form-style" name="phone2code">
											<?php 
											foreach ($phoneCodes as $c => $code) { ?>
												<option value="<?= $c?>"><?= $c?> <?= $code?></option>
											<?php }
											 ?>
										</select>
										<input type="text" id="phone2" name="phone2" maxlength="10" class="form-control form-style fontPhone" placeholder="TEL&Eacute;FONO 2" value="<?= isset($adDetailCar["phone2"]) ? $adDetailCar["phone2"] : "" ?>" >
									</td>
									<td align="center">
										<label class="checkbox fontWA">
												<input type="checkbox" name="phone2wa" <?= ((!isset($adDetailCar["phone2wa"])) ? "" : ($adDetailCar["phone2wa"]==1) ? "checked" : "") ?>>
											Es WhatsApp
										</label>
									</td>
								</tr>
								<tr>
									<td class="multi-input">
										<select class="form-control form-style" name="phone3code">
											<?php 
											foreach ($phoneCodes as $c => $code) { ?>
												<option value="<?= $c?>"><?= $c?> <?= $code?></option>
											<?php }
											 ?>
										</select>
										<input type="text" id="phone3" name="phone3" maxlength="10" class="form-control form-style fontPhone" placeholder="TEL&Eacute;FONO 3" value="<?= isset($adDetailCar["phone3"]) ? $adDetailCar["phone3"] : "" ?>" >
									</td>
									<td align="center">
										<label class="checkbox fontWA">
											<input type="checkbox" name="phone3wa" <?= ((!isset($adDetailCar["phone3wa"])) ? "" : ($adDetailCar["phone3wa"]==1) ? "checked" : "") ?>>
											Es WhatsApp
										</label>
									</td>
								</tr>
								<tr>
									<td>
										<div id="check_email">
											<input type="email" id="email" name="email" maxlength="50" class="form-control form-style" placeholder="CORREO ELECTR&Oacute;NICO *" value="<?= (!isset($adDetailCar["email"]) && $detalles["o_avi_user_email"]) ? $detalles["o_avi_user_email"] : (isset($adDetailCar["email"]) ? $adDetailCar["email"] : "" ) ?>">
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<td>
										<div id="check_email2">
											<input type="email" id="email2" name="email2" maxlength="50" class="form-control form-style" placeholder="CORREO ELECTR&Oacute;NICO (OPCIONAL)" value="<?= isset($adDetailCar["email2"]) ? $adDetailCar["email2"] : "" ?>">
										</div>
									</td>
									<td></td>
								</tr>
								<tr>
									<th colspan="2">
										Ubicaci&oacute;n de Venta
									</th>
								</tr>
								<tr>
									<td>
										<div id="check_calle">
											<input type="text" id="calle" maxlength="50" name="calle" class="form-control form-style" placeholder="CALLE Y N&Uacute;MERO" value="<?= isset($adDetailCar["street"]) ? $adDetailCar["street"] : "" ?>">
										</div>
									</td>
									<td>
										<div id="check_colonia">
											<input type="text" id="colonia" name="colonia" maxlength="50" class="form-control form-style" placeholder="COLONIA/DELEGACI&Oacute;N" value="<?= isset($adDetailCar["suburb"]) ? $adDetailCar["suburb"] : "" ?>">
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<div id="check_zipcode">
											<input type="text" id="zipcode" maxlength="7" onchange="zip($(this))" name="zipcode" class="form-control form-style" placeholder="C&Oacute;DIGO POSTAL" value="<?= isset($adDetailCar["zipcode"]) ? $adDetailCar["zipcode"] : "" ?>">
										</div>
									</td>
									<td>
										<input type="text" id="estado" name="estado" class="form-control form-style" placeholder="ESTADO" disabled="">
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<div id="check_locationreference">
											<textarea name="locationreference" id="locationreference" class="form-control" cols="30" rows="3" style="width: 100%" maxlength="125" placeholder="REFERENCIAS DE LA UBICACI&Oacute;N"><?= isset($adDetailCar["reference"]) ? $adDetailCar["reference"] : "" ?></textarea>
										</div>
									</td>
								</tr>
							</table>
							<h4 class="text-center">FORMAS DE PAGO</h4> 
							<table class="payments">
								<tr>
									<td>D&Eacute;BITO O TRANSFERENCIA</td>
									<td><input name="debTransfer" type="radio" value="1" <?= isset($metpagos["debTransfer"]) && $metpagos["debTransfer"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="debTransfer" type="radio" value="0" <?= isset($metpagos["debTransfer"]) && $metpagos["debTransfer"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>
								<tr>
									<td>CR&Eacute;DITO</td>
									<td><input name="credit" type="radio" value="1" <?= isset($metpagos["credit"]) && $metpagos["credit"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="credit" type="radio" value="0" <?= isset($metpagos["credit"]) && $metpagos["credit"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>
								<tr>
									<td>CR&Eacute;DITO BANCARIO</td>
									<td><input name="bankCredit" type="radio" value="1" <?= isset($metpagos["bankCredit"]) && $metpagos["bankCredit"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="bankCredit" type="radio" value="0" <?= isset($metpagos["bankCredit"]) && $metpagos["bankCredit"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>
								<tr>
									<td>AUTOFINANCIAMIENTO</td>
									<td><input name="carfinance" type="radio" value="1" <?= isset($metpagos["carfinance"]) && $metpagos["carfinance"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="carfinance" type="radio" value="0" <?= isset($metpagos["carfinance"]) && $metpagos["carfinance"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>			
								<tr>
									<td>CAMBIO POR AUTO DE MENOR PRECIO</td>
									<td><input name="changeHighPrice" type="radio" value="1" <?= isset($metpagos["changeHighPrice"]) && $metpagos["changeHighPrice"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="changeHighPrice" type="radio" value="0" <?= isset($metpagos["changeHighPrice"]) && $metpagos["changeHighPrice"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>
								<tr>
									<td>CAMBIO POR AUTO DE MAYOR PRECIO</td>
									<td><input name="changeLowPrice" type="radio" value="1" <?= isset($metpagos["changeLowPrice"]) && $metpagos["changeLowPrice"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="changeLowPrice" type="radio" value="0" <?= isset($metpagos["changeLowPrice"]) && $metpagos["changeLowPrice"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>
								<tr>
									<td>ARRENDAMIENTO</td>
									<td><input name="leasing" type="radio" value="1" <?= isset($metpagos["leasing"]) && $metpagos["leasing"]=="1" ? "checked" : "" ?>>Aceptar</td>
									<td><input name="leasing" type="radio" value="0" <?= isset($metpagos["leasing"]) && $metpagos["leasing"]=="0" ? "checked" : "" ?>>No Aceptar</td>
								</tr>
							</table>
						</div>
						<div class="footer-line modal-footer">
							<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</form>

		<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="formCoverCar">
			<div class="editing-car" title="Elegir portada del auto">	
				<label for="portadaAuto" class="filelabel">
					<img class="edit-cover" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
					<input type='hidden' id='hiddenRouteCar' value=''/>
					<input type='hidden' name='garage' id='garage' value='<?= $_GET["cuenta"]?>'/>
					<input type='hidden' name='car' id='car' value='<?= isset($imageBase["a_avi_car_img_account_car_id"])? $imageBase["a_avi_car_img_account_car_id"] : $_GET["auto"] ?>'/>
				</label>
				<input name="portadaAuto" id="portadaAuto" class="inputfile form-type" type="file" onchange="coverCar()" />
			</div>
		</form>

		<div class="editVehiclePhotos" id="editingPhotosDiv">
			<h4 class="col-xs-12">Editar imagenes del veh&iacute;culo</h4>
			<?php
			foreach ($imagenes as $keyimg => $imageBase) 
			{	
				?>
				<div class="inline-logos col-xs-3 newImgEdit" style="background-image: url('<?=$imageBase["a_avi_car_img_car"]?>');">
					<div class="font-trash">
				    	<a class=" icon-trash icon-trash-car" data-img='<?=$imageBase["imagenId"]?>' data-car='<?=$imageBase["a_avi_car_img_account_car_id"]?>' onclick="showModal($(this))" title="Borrar"></a>
				   	</div>
				</div>
				<?php
			}
			?>
			<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="photoCar">
				<div class="inline-logos col-xs-3 camaraButton">
					<label for="imagenAuto" class="carImgLabel">
						<img id="addVechilePhoto" class="camara center-block" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png">
						<input type='hidden' id='hiddenRouteCar' value=''/>
						<input type='hidden' name='garage' id='garage' value='<?= $_GET["cuenta"]?>'/>
						<input type='hidden' name='car' id='car' value='<?= isset($imageBase["a_avi_car_img_account_car_id"])? $imageBase["a_avi_car_img_account_car_id"] : $_GET["auto"] ?>'/>
					</label>
					<input name="imagenAuto" id="imagenAuto" class="inputfile" type="file" onchange="addingImg()" />
				</div>
			</form>
		</div> 
		
		<div style="width: 100%;" class="row text-center">				    
		    <div class="next-padd changes">
		    	Los campos con <span class="asterisk">[*]</span> son obligatorios.
    		</div>
    		<div class="col-xs-12 text-center" style="padding-top: 20px;padding-bottom: 76px;">
			    <button  class="btn cuenta-btns" type="button" onclick="sendDataCar()">Guardar</button>
			    <?php if($owner){?>
	    	 	 |&nbsp;<button  class="btn cuenta-btns" type="button" onclick="changeGarageCar()">Cambiar de Garage</button>
	    	 	<?php } ?>
	    	 	 |&nbsp;<button class="borrar btn cuenta-btns" data-car='<?= $autoEncoded?>' data-garage='<?= $llaveGarage?>' >Eliminar Auto</button>
		    </div>
	    </div>
	</div>
	<div class="modal fade" id="loadImageCoverCar" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4>Recorta tu imagen</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-12 text-center">
							<div id="img-to-upload-car"></div>
							
						</div>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" id="saveCarPicture" class="btn modal-btns doCrop" onclick="">Guardar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="colorsCatalogo" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Seleccione el color de su veh&iacute;culo</h4>
				</div>
				<div class="modal-body text-center">
					<div style="display: inline-block;">
					<?php foreach ($colores as $colorkey => $color) { ?>
			        	<a class='colores' data-dismiss='modal' data-clase='<?=$colorkey?>'><img class='clases-img-colors' src="<?=$color["img"]?>"><p><?=$color["nombre"]?></p></a>
			        <?php } ?>
		        	</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-avi-white" data-dismiss="modal">cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<?php
	$allGarages = Garage::account($_SESSION["iduser"]);
	?>
	<div id="modalChangeGarage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Cambiar Garage</h4>
				</div>
				<div class="modal-body">
					<p>Esta acci&oacute;n movera tu auto a un nuevo garage.</p>
					<div class="selectdiv content-top-75">
						<label class="control-label">Selecciona un Garage</label>
						<select class="form-control form-style" id="changeGarageSelect">
						<?php 
						$actualGarage=$garageContain[0]["o_avi_account_id"];
						foreach ($allGarages as $g => $cochera) { 
							$coder->encode($cochera["idAccount"]);
							$codeGarage=$coder->encoded;
							$selected="";
							$actual="";
							if($actualGarage==$cochera["idAccount"]){
								$selected="selected";
								$actual=" (Actual)";
							}
							?>
							<option value='<?= $codeGarage?>' <?= $selected?>><?=$cochera["nameAccount"]?><?=$actual?></option>
						<?php } ?>
						</select>
					</div>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" id="changeGarage" data-car="<?= $autoEncoded?>" class="btn modal-btns" data-dismiss="modal">Cambiar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="tipoClase" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Seleccione su tipo de auto</h4>
				</div>
				<div class="modal-body">
					<div class="table-sC">
						<table>
						<?php 
						$modClases=(sizeof($clases))%2;
						$i=1;
						$totalIter=1;
						$td="";
						$tr="";
					 	foreach ($clases as $cl => $clase) { 
					 		$td.="<td><a class='clases-auto' data-dismiss='modal' data-clase='$cl'><img class='clases-img' src='".$clase["iconos"]."' >".$clase["description"]."</a></td>";
					 		if($i==2)
					 		{
					 			$i=0;
					 			$tr.="<tr>".$td."</tr>";
				        		$td="";
					 		}
							if($modClases && $totalIter>(sizeof($clases)-1))
							{
								$tr.="<tr>".$td."</tr>";
							}
							$i++;
							$totalIter++;
						} 
							echo $tr;
						?>
						</table>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-avi-white" data-dismiss="modal">cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="modalBorrarAuto" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Eliminar Auto</h4>
				</div>
				<div class="modal-body">
					<p>No podr&aacute; recuperar la informaci&oacute;n del veh&iacute;culo. ¿Est&aacute; seguro que desea eliminarlo?</p>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">Cancelar</button> |
					<button type="button" id="eliminar" class="btn modal-btns" data-dismiss="modal">Eliminar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="modalDelete" class="modal fade" role="dialog" style="z-index: 10009;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="title-header modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Eliminar Foto</h4>
				</div>
				<div class="modal-body">
					<p>Al eliminarla no se podr&aacute; recuperar. ¿Est&aacute; seguro que desea eliminarla?</p>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" onclick="hideimgCarModal($(this))">cerrar</button>|
					<button type="button" id="eliminarFoto" class="btn modal-btns" onclick="deletedImg($(this))">aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<script src="/js/garageNewCar.js?l=<?= LOADED_VERSION?>"></script>
	<script src="/js/editAuto.js?l=<?= LOADED_VERSION?>"></script>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>