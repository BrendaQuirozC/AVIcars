<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-06-21 11:29:59
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-07 13:21:49
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once  $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once  $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once ($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
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
$country=new Country;
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
$Venta = new Venta;
$garageContain= $Garage-> instanciaById($_GET["auto"]);
$currMarca=null;
$curSubMarca=null;
$curModelo=null;
$claseCar=null;
$doors=null;
$fuel=null;
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],2);
$colaboradorCont=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],3);
if(!empty($garageContain))
{
	if($garageContain[0]["o_avi_car_version_id"])
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
	$imagenes=$Garage->imagenesGenerales($_GET["auto"]);
	if(empty($imagenes))
	{
		$notImage='/img/noimage.png';
	}
	$fuel=$garageContain[0]["fuel"];
	$doors=$garageContain[0]["doors"];
	$ventanas=$garageContain[0]["ventanas"];
	$interior=$garageContain[0]["interior"];
	$curVersion=$garageContain[0]["o_avi_car_version_id"];
	$imgPerfil = $Garage->getImgPerfil($_GET["cuenta"]);
	$claseCar=$garageContain[0]["clase"];
	$nameMarca = $garageContain[0]["nombreMarca"];
	$nameSubmarca = $garageContain[0]["nombreSubmarca"];
	$nameModelo = $garageContain[0]["nombreModelo"];
	$nameVersion=$garageContain[0]["nombreVersion"];
	$extras=json_decode($garageContain[0]["extras"],true);
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
	$phoneCodes=$country->getPhoneCodes();
	$garages = $Garage -> account($_GET["cuenta"]);
	$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
	$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"])); 
	$privacidadUser=(isset($infoPerfil["privacidad"])) ? $infoPerfil["privacidad"] : 1; 
	$privacidad=(isset($garageContain[0]["privacidad"])) ? $garageContain[0]["privacidad"] : 1;
	$Like = new Like;
}
else
{
	$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"])); 
}

include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!isset($garage) || $garage["user"]!=$_GET["cuenta"] || ($garage["user"]!=$_SESSION["iduser"]&&!$colaborador))
{

	$privacidad=3; ?>
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
<?php
}
else{
	$llaveGarage = $garageContain[0]["o_avi_account_id"];
	$adDetailCar=$auto->adCar($_GET["auto"]);
	$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
	if(!empty($adDetailCar))
		$metpagos=json_decode($adDetailCar["metodoPago"],true);
	else
		$metpagos=array();
	//include_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/headerAuto.php";
	?>
	<div class="sidebar sidebar-no-header hidden-xs visible-sm visible-md visible-lg">
		<ul>
			<li> <a href="/perfil/autos/detalles/?cuenta=<?=$cuentaEncoded?>&auto=<?=$autoEncoded?>"><span> Timeline</span><img src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_submenu_boton_miTimeline_infotraffic.png" class="navigation-icon"> </a></li>
			<?php if($owner){ ?>
	     	<li> <a href="/perfil/autos/detalles/docs/?cuenta=<?= $cuentaEncoded ?>&auto=<?=$autoEncoded?>"><span> Expediente</span><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen.png" class="navigation-icon"> </a></li>
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
	<?php if($garageContain[0]["status_sell"]==2 ){ ?>
	<div class="content form-send" style="position: relative; top: 100px;">
		<h2 class="text-center">&Eacute;ste auto ya se vendi&oacute;.</h2>
	</div>
	<?php } else{ ?>
	<div class="content form-send" style="position: relative; top: 100px;">
		<?php /*<div class="tacometro">
			<img class="aguja" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyCars_Movil_ViewPort_AGUJA.png">
		</div>*/?>
		<form id="fromEdit" enctype="multipart/form-data">	
			<h2 class="text-center">ANUNCIA TU AUTO CON NOSOTROS</h2>
			<h5 class="text-center">A continuaci&oacute;n te presentamos un listado de caracter&iacute;sticas de tu auto para que podamos brindar un anuncio de venta m&aacute;s completo:</h5>
			<h1 class="text-center">---ANUNCIO---</h1>
			<div class="table-header">
				<table>
					<tr>
						<th colspan="2" class="hheader">CARACTER&Iacute;STICAS DEL AUTO</th>
					</tr>
					<tr>
						<td>
							<div  id="check_marca" class="form-group selectdiv selecposition">
								<select class="form-control form-style" id="marca" name="marca" onchange="selectMarca()">
									<option class="visible" value="0">MARCA</option>
								<?php 
								foreach ($marcas as $m => $marca) 
								{ 
									$selected="";
									if($m==$currMarca)
									{
										$selected="selected";
									}
									if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO'){
								?>
									<option class="visible" value="<?= $m?>" <?= $selected?>><?= $marca?></option>
								<?php 
									}
								} 
								if($currMarca==0 && $nameMarca!=NULL){ ?>
									<option class="visible" value="-1" selected>Otra</option>
								<?php
								} else { ?>
									<option class="visible" value="-1">Otra</option>
								<?php } ?>
								</select>
								<div class="otraMarca col-xs-12 hidden">
									<label class='control-label col-xs-12'>Especifica</label>
									<input  maxlength="50" type='text' class='form-control' id='otraMarcaInput' name='otraMarcaInput' value='<?= $nameMarca ? $nameMarca : "" ?>'/>
								</div>
							</div>
						</td>
						<td rowspan="5">
							<div class="brand-img">
								<img id="brandImg" src="" alt="" class="">
								<span id="text-brand"></span>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id="check_submarca" class="form-group selectdiv selecposition">
								<select class="form-control form-style submarca" id="modelo" name="submarca" onchange="selectSubmarca()">
									<option value="0">SUBMARCA</option>
								<?php 
								foreach ($submarcas as $sm => $submarca) 
								{ 
									$selected="";
									if($submarca["id"]==$curSubMarca)
									{
										$selected="selected";
									}
								?>
									<option data-marca="<?= $submarca["marca"]?>" value="<?= $submarca["id"]?>" <?= $selected?>><?= $submarca["submarca"]?></option>
								<?php 
								} 
								if($curSubMarca==0 && $nameSubmarca!=NULL){ ?>
									<option class="visible" value="-1" selected>Otro</option>
								<?php
								} else { ?>
									<option class="visible" value="-1">Otro</option>
								<?php } ?>
								?>
								</select>
								<div class='otroModelo col-xs-12 hidden'>
									<label class='control-label col-xs-12'>Especifica</label>
									<input maxlength="50" type='text' class='form-control' id='otroModeloInput' name='otroModeloInput' value='<?= $nameSubmarca ? $nameSubmarca : "" ?>'/>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="form-group selectdiv selecposition">
								<select class="form-control form-style" id="ano" name="modelo" onchange="selectModelo()">
									<option value="0">MODELO</option>
								<?php 

								foreach ($modelos as $md => $modelo) 
								{ 
									$selected="";
									if($modelo["id"]==$curModelo)
									{
										$selected="selected";
									}
								?>
									<option value="<?= $modelo["id"]?>" <?= $selected?>> <?= $modelo["modelo"]?> </option>
								<?php 
								} 
								if($curModelo==0 && $nameModelo!=NULL){ ?>
									<option class="visible" value="-1" selected>Otro</option>
								<?php
								} else { ?>
									<option class="visible" value="-1">Otro</option>
								<?php } ?>
								?>	
								</select>
								<div id='otheryear' class='otroAno col-xs-12 hidden'>
									<label class='control-label col-xs-12'>Especifica</label>
									<input type='text'  maxlength="50" class='form-control' id='otroAnoInput' name='otroAnoInput' value='<?= $nameModelo ? $nameModelo : "" ?>'/>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
						<div id="check_version" class="form-group selectdiv selecposition">
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
								<option class="visible" value="-1" selected>Otro</option>
							<?php
							} else { ?>
								<option class="visible" value="-1">Otro</option>
							<?php } ?>
							?>			
							</select>
							<div id='otherver' class='otherver col-xs-12 hidden'>
								<label class='control-label col-xs-12'>Especifica</label>
								<input maxlength="50" type='text' class='form-control' id='otroVersionInput' name='otroVersionInput' value='<?= $nameVersion ? $nameVersion : "" ?>'/>
							</div>
							</select>
						</div>
						</td>
					</tr>
				</table>
			</div>
			<table class="table">
				<tr>
					<th>
						Alias del veh&iacute;culo <span class="asterisk">*</span>
						<input class="form-control form-style" name="alias" id="alias" placeholder="Alias o apodo para tu auto" maxlength="17" type="text" value="<?= $garageContain[0]["i_avi_account_car_alias"]?>">	
					</th>
					<th colspan="2">
						VIN<span class="asterisk">**</span> <span class="font-little">(Vehicle Identification Number)</span>
						<input class="form-control form-style" name="vin" id="vinNum" placeholder="XXXXXXXXXXXXXXXXX" maxlength="17" type="text" value="<?= $garageContain[0]["o_avi_car_vin"]?>">
					</th>
				</tr>
				<tr>
					<td>
						PRECIO
					</td>
					<td colspan="2">
						<div  id="check_price" class="table-form form-group">
							<input class="form-control form-style priceNF" onkeyup="addPriceFormat();" type="text" maxlength="9" id="formatPrice" value="<?= $garageContain[0]["a_avi_sell_detaill_price"]?>" >
							<input class="form-control form-style" type="hidden" maxlength="7" id="precio" name="precio" value="<?= $garageContain[0]["a_avi_sell_detaill_price"]?>" >
							<select class="moneda" name="moneda">
								<option value="MXN" <?=$garageContain[0]["currency"]=='MXN' ? 'selected' : ''?>>MXN</option>
								<option value="USD" <?=$garageContain[0]["currency"]=='USD' ? 'selected' : ''?>>USD</option>
								<option value="EUR" <?=$garageContain[0]["currency"]=='EUR' ? 'selected' : ''?>>EUR</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td>NEGOCIABLE</td>
					<td colspan="2">
						<label class="radio-inline"><input type="radio" name="negociable" value="1" <?= isset($adDetailCar["negociable"]) && $adDetailCar["negociable"]=="1" ? "checked" : "" ?>>SI</label>
						<label class="radio-inline"><input type="radio" name="negociable" value="0" <?= isset($adDetailCar["negociable"]) && $adDetailCar["negociable"]=="0" ? "checked" : "" ?>>NO</label>						
					</td>
				</tr>
				<tr>
					<td colspan="3"><h4>M&Eacute;TODOS DE PAGO</h4></td>
				</tr>
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
			<div class="table-sC">
				<table>
					<tr>
						<td colspan="2">Texto del Anuncio</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="check_anunciotext">
								<textarea name="anunciotext" id="anunciotext" class="form-control" cols="30" rows="3" style="width: 100%" maxlength="320" placeholder="M&Aacute;XIMO 320 CARACTERES"><?= isset($adDetailCar["texto"]) ? $adDetailCar["texto"] : "" ?></textarea>
							</div>
						</td>
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
					<tr>
						<th colspan="2">Datos de Contacto</th>
					</tr>
					<tr>
						<td class="multi-input">
							<select class="form-control form-style" name="phonecode">
								<?php 
								foreach ($phoneCodes as $c => $code) { ?>
									<option value="<?= $c?>"><?= $c?> <?= $code?></option>
								<?php }
								 ?>
							</select>
							<input type="text" id="phone" name="phone" maxlength="10" class="form-control form-style" placeholder="TEL&Eacute;FONO 1" value="<?= (!isset($adDetailCar["phone"]) && $detalles["o_avi_userdetail_phone"]) ? $detalles["o_avi_userdetail_phone"] : (isset($adDetailCar["phone"]) ? $adDetailCar["phone"] : "" ) ?>" >
						</td>
						<td align="center">
							<label class="checkbox">
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
							<input type="text" id="phone2" name="phone2" maxlength="10" class="form-control form-style" placeholder="TEL&Eacute;FONO 2" value="<?= isset($adDetailCar["phone2"]) ? $adDetailCar["phone2"] : "" ?>" >
						</td>
						<td align="center">
							<label class="checkbox">
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
							<input type="text" id="phone3" name="phone3" maxlength="10" class="form-control form-style" placeholder="TEL&Eacute;FONO 3" value="<?= isset($adDetailCar["phone3"]) ? $adDetailCar["phone3"] : "" ?>" >
						</td>
						<td align="center">
							<label class="checkbox">
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
				</table>
			</div>
			
				<div class="table-sC">
					<table class="hheader">
						<tr>
							<td colspan="3">
								<div class="form-group selecposition text-left">
									<span class="icon-vehicle-condition"></span><label class="control-label pad-topp row-label">&nbsp;Estado del veh&iacute;culo: </label>
								</div>
							</td>
						</tr>
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
					<table>
						<tr>
							<td class="td-border-gray" style="width: 50%">
								<div class="form-group selectdiv selecposition">
									<input class="form-control form-style text-left" id="classcar" onclick="tipoClase()" type="button" value="Tipo de auto">
								</div>
							</td>
							<td>
								<div class="form-group selectdiv selecposition">
									<select class="form-control form-style" id="engineCar" name="engineCar">
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
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="leftColumn" id="get-classcar">
									<?php 
									if($claseCar){ 
										foreach ($clases as $cl => $clase) { 
											if($claseCar==$cl)
											{ ?>
										<input type='hidden' name='clasecar' value="<?= $claseCar?>"><img onclick='tipoClase()' class='clases-img' src='<?= $clase["iconos"]?>' ><?=$clase["description"]?>
									<?php
											} 
										}
									} ?>
								</div>
							</td>
							<td>
								<div class="rightColum" id="typeEngine">
									<?php 
									if($garageContain[0]["engineType"]){ 
										foreach ($motores as $mt => $motor)
										{
											if($garageContain[0]["engineType"]==$mt)
											{
												echo $motor;
											} 
										}
									} ?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="form-group selectdiv selecposition">
									<input class="form-control form-style text-left" id="color" onclick="colorCatalogo()" type="button" value="Color">
								</div>
							</td>
							<td>
								<div class="form-group selectdiv selecposition">
									<select class="form-control form-style" id="combustible" name="combustible">
										<option value="0">Combustible</option>
										<option value="1">Gasolina</option>
										<option value="2">Diesel</option>
										<option value="3">Etanol</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="leftColumn" id="get-colorcar">
									<?php if($garageContain[0]["o_avi_car_color"]){ 
										foreach ($colores as $colorkey => $color) { 
											if($garageContain[0]["o_avi_car_color"]==$colorkey){
											?>
											<input value="<?= $garageContain[0]["o_avi_car_color"]?>" type='hidden' name='color'><img onclick='colorCatalogo()' class='clases-img-colors'  src="<?=$color["img"]?>"><?=$color["nombre"]?>
										<?php }
										}
									} ?>
								</div>
							</td>
							<td>
								<div class="rightColum" id="typeFuel">
								<?php if($fuel){
									foreach ($catalogoFuel as $f => $fl) {
										if($f==$fuel){
									 		echo $fl;
									 	} 
									}
								}?>
 								</div>
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="form-group selectdiv selecposition">
									<select class="form-control form-style" id="transmision" name="transmision">
										<option value="0">Transmisi&oacute;n</option>
										<option value="1">Autom&aacute;tica</option>
										<option value="2">Manual</option>
										<option value="3">Semi Autom&aacute;tica</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group selectdiv selecposition">
									<select class="form-control form-style" id="puertas" name="puertas">
										<option value="0">Puertas</option>
										<option value="2">2 Puertas</option>
										<option value="4">4 Puertas</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="leftColumn" id="showtypeTransmision">
									<?php 
									if($garageContain[0]["trans"])
									{
										foreach ($catalogoTrans as $ct => $trans) {
											if($garageContain[0]["trans"]==$ct)
											{ ?>
												<?=$trans["nombre"]?>
											<?php
											}	
										} 
									}?>
									
								</div>
							</td>
							<td>
								<div class="rightColum" id="showPuertas">
									<?= $doors?>
								</div>
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="form-group selectdiv selecposition">
									<select class="form-control form-style" id="ventanas" name="ventanas">
										<option value="0">Ventanas</option>
										<option value="Manuales">Manuales</option>
										<option value="Electricos">El&eacute;ctricos</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group selectdiv selecposition">
									<select class="form-control form-style" id="interior" name="interior">
										<option value="0">Interiores</option>
										<option value="Tela">Tela</option>
										<option value="Piel">Piel</option>
										<option value="Imitaci&oacute;n Piel">Imitaci&oacute;n Piel</option>
										<option value="Piel con tela">Piel con tela</option>
										<option value="Piel con gamuza">Piel con gamuza</option>
										<option value="Vinipiel">Vinipiel</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<td class="td-border-gray">
								<div class="leftColumn" id="showtypeWindows">
									<?= ($ventanas) ? $ventanas : '' ?>
								</div>
							</td>
							<td>
								<div class="rightColum" id="showTypeInteriors">
									<?= ($interior) ? $interior : ''?>
								</div>
							</td>
						</tr>
						<tr style="border-top: 2px solid #aeaeae;">
							<th class="text-center">
								<p>KILOMETRAJE:</p> 
							</th>
							<td>
								<div id="check_kilometraje" class="form-group km-style">
									<input class="form-control form-style kmNF" onkeyup="addKmFormat();" type="text" maxlength="9" id="formatKm" value="<?= $garageContain[0]["o_avi_car_km"]?>" >
									<input class="form-control form-style" name="kilometraje" id="kilometraje" maxlength="7" type="hidden" value="<?= $garageContain[0]["o_avi_car_km"]?>">
								</div>
							</td>
						</tr>
						<tr>
							<th class="text-center">
								<p>N° DE DUEÑOS:</p>
							</th>
							<td>
								<div id="check_duenos" class="form-group km-style">
									<input class="form-control form-style" name="dueños" id="duenos" maxlength="3" type="text" value="<?= $garageContain[0]["dueno"]?>">
								</div>
							</td>
						</tr>
						<tr>
							<th class="text-center">
								<p>POTENCIA:</p>
							</th>
							<td>
								<div id="check_potencia" class="form-group km-style">
									<input class="form-control form-style" maxlength="3" name="potencia" id="potencia" type="text" value="<?= $garageContain[0]["potencia"]?>">
								</div>
							</td>
						</tr>
					</table>
					<table  class="hheader">
						<th colspan="3">
							<p>Tu auto alguna vez ha sido :</p>
						</th>
				        <tr>
			        		<td>RECUPERADO DE ROBO<span class="asterisk">**</span></td>
			        		<td><input type='radio' name='recuperadorobo' value='1' <?= $garageContain[0]["recuperadoStole"] ? "checked" : "" ?>> Si</td>
			        		<td><input type='radio' name='recuperadorobo' value='0' <?= !$garageContain[0]["recuperadoStole"] ? "checked" : "" ?>> No</td>
			        	</tr>
			        	<tr>
			        		<td>RECONSTRU&Iacute;DO<span class="asterisk">**</span></td>
			        		<td><input type='radio' name='reconstruido' value='1' <?= $garageContain[0]["reconstruido"] ? "checked" : "" ?>> Si</td>
			        		<td><input type='radio' name='reconstruido' value='0' <?= !$garageContain[0]["reconstruido"] ? "checked" : "" ?>> No</td>
			        	</tr>
						<tr>
			        		<td>LEGALIZADO<span class="asterisk">**</span></td>
			        		<td><input type='radio' name='legalizado' value='1' <?= $garageContain[0]["legalizado"] ? "checked" : "" ?>> Si</td>
			        		<td><input type='radio' name='legalizado' value='0' <?= !$garageContain[0]["legalizado"] ? "checked" : "" ?>> No</td>
			        	</tr>
			        	<tr class="theader">
				        	<td colspan="3">
								<div class="form-group selecposition text-left pad-top">
									<label data-toggle="modal" data-target="#pagosCorriente" class="control-label row-label pointer"><span class="icon-add"></span> &nbsp;AÑADIR PAGOS AL CORRIENTE DEL AUTO<span class="asterisk">**</span></label>
								</div>
							</td>
						</tr>
						<tr  class="theader">
							<th colspan="2">
								Factura a nombre de :
							</th>
						</tr>
						<tr>
							<tr class="facturas">
							<td>
								<p>PERSONA F&Iacute;SICA <input type='checkbox' name='facturaPfisica' <?= $garageContain[0]["fpersonafisica"] ? "checked" : ""?>></p>
								<p>ASEGURADORA <input type='checkbox' name='facturaAseguradora' <?= $garageContain[0]["faseguradora"] ? "checked" : ""?>></p>
							</td>
							<td colspan="2">
								<p>EMPRESA <input type='checkbox' name='facturaEmpresa' <?= $garageContain[0]["fempresa"] ? "checked" : ""?>></p>
								<p>LOTE <input type='checkbox' name='facturaLote' <?= $garageContain[0]["flote"] ? "checked" : ""?>></p>
							</td>
						</tr>
					</table>
					<div id="check_placa" class="text-center" style="position: relative;">
						<b class="placa-title">Placas:<span class="asterisk">**</span></b>
						<img class="placa-pad plate-size" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_viewport_features_infotraffic/MyCars_Movil_ViewPort_BOX-PLACA_300px_infotraffic.png" alt="">
						<input type="text" name="placa" id="placa" class="input-plate" maxlength="9" value="<?=$garageContain[0]["placa"] ?>">
					</div>
					<label for="holograma">Holograma :</label>
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
				</div>	
			<input type="hidden" id="auto" name="auto" value="<?= $autoEncoded?>">
			<input type="hidden" id="cuenta" name="cuenta" value="<?= $cuentaEncoded?>">
			<div id="interiores" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Interiores</h4>
						</div>
						<div class="modal-body row text-center">
							<div class="table-sC">
								<table>
									<tr>
										<td>Cap. Máxima de Pasajeros</td>
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
										<td>
											Filas de Asientos
										</td>
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
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-avi-white" data-dismiss="modal">cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<div id="pagosCorriente" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Pagos al Corriente</h4>
						</div>
						<div class="modal-body row text-center">
							<div class="table-sC">
								<table>
									<tr>
										<td>DEUDA / REFRENDO</td>
										<td>
											<input type="radio" name="referendo" value="1" <?= isset($extras["pagos"]["referendo"]) && $extras["pagos"]["referendo"]=="1" ? "checked" : "" ?>> Si
										</td>
										<td>
											<input type="radio" name="referendo" value="0" <?= isset($extras["pagos"]["referendo"]) && $extras["pagos"]["referendo"]=="0" ? "checked" : "" ?>> No
										</td>
									</tr>
									<tr>
										<td>
											MULTAS
										</td>
										<td>
											<input type="radio" name="multas"  value="1" <?= isset($extras["pagos"]["multas"]) && $extras["pagos"]["multas"]=="1" ? "checked" : "" ?>> Si
										</td>
										<td>
											<input type="radio" name="multas" value="0" <?= isset($extras["pagos"]["multas"]) && $extras["pagos"]["multas"]=="0" ? "checked" : "" ?>> No
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
			<div id="exteriores" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Exteriores</h4>
						</div>
						<div class="modal-body row text-center">
							<div class="table-sC">
								<table>
									<tr>
										<td>Distancia entre Ejes</td>
										<td>		
											<div id="check_distanciaEjes">		
												<input class="form-control form-style" maxlength="7" name="distanciaEjes" id="distanciaEjes" type="text" value="<?= $extras["Exteriores"]["Distancia entre Ejes"] ?>" placeholder="Milímetro (mm)">
											</div>
										</td>
									</tr>
									<tr class="top-gray">
										<th colspan="2">
											Ancho entre V&iacute;as
										</th>
									</tr>
									<tr align="right">
										<td>
											Delanteras
										</td>
										<td>
											<div id="check_anchoViasDel">	
												<input class="form-control form-style" maxlength="7" id="anchoViasDel" name="anchoViasDel" type="text" value="<?= $extras["Exteriores"]["Ancho entre Vías Delanteras"] ?>" placeholder="Milímetro (mm)">
											</div>
										</td>
									</tr>
									<tr align="right">
										<td>
											Traseras
										</td>
										<td>
											<div id="check_anchoViasTra">
												<input class="form-control form-style" maxlength="7" id="anchoViasTra" name="anchoViasTra" type="text" value="<?= $extras["Exteriores"]["Ancho entre Vías Traseras"] ?>" placeholder="Milímetro (mm)">
											</div>
										</td>
									</tr>
									<tr class="top-gray">
										<td>
											Altura Total
										</td>
										<td>
											<div id="check_alturaTotal">	
												<input class="form-control form-style" maxlength="7" name="alturaTotal" id="alturaTotal" type="text" value="<?= $extras["Exteriores"]["Altura Total"] ?>" placeholder="Milímetro (mm)">
											</div>
										</td>
									</tr>
									<tr>
										<td>Distancia al piso</td>
										<td>
											<div id="check_distanciaPiso">
												<input class="form-control form-style" maxlength="7" name="distanciaPiso" id="distanciaPiso" type="text" value="<?= $extras["Exteriores"]["Distancia al piso"] ?>" placeholder="Milímetro (mm)">
											</div>
										</td>
									</tr>
									<tr>
										<td>
											&Aacute;ngulo M&aacute;ximo de Ataque
										</td>
										<td>
											<div id="check_anguloAtaque">	
												<input class="form-control form-style" maxlength="7" name="anguloAtaque" id="anguloAtaque" type="text" value="<?= $extras["Exteriores"]["Angulo max de ataque"] ?>" placeholder="Grados (°)">
											</div>
										</td>
									</tr>
									<tr>
										<td>
											Circunferencia M&iacute;nima Giro
										</td>
										<td>
											<div id="check_circunferenciaGiro">
												<input class="form-control form-style" maxlength="7" name="circunferenciaGiro" id="circunferenciaGiro" type="text" value="<?= $extras["Exteriores"]["Circunferencia de Giro"] ?>" placeholder="Milímetro (mm)">
											</div>
										</td>
									</tr>
									<tr>
										<td>
											Distribuci&oacute;n del peso en Eje Delantero
										</td>
										<td>
											<div id="check_pesoEjeDelatero">
												<input class="form-control form-style" maxlength="7" name="pesoEjeDelatero" id="pesoEjeDelatero" type="text" value="<?= $extras["Exteriores"]["Peso Eje Delatero"] ?>" placeholder="Porcentaje (%)">
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
												<input class="form-control form-style" name="garantiaFabrica" id="garantiaFabrica" maxlength="100" value="<?= $extras["garantia"]["fabrica"]?>" type="text" placeholder="Ej. 1 año, 3 meses, 30 dias, etc..." >
											</div>
										</td>
									</tr>
									<tr>
										<td>
											VENDEDOR
										</td>
										<td>
											<div id="check_garantiaVendedor">
												<input class="form-control form-style" name="garantiaVendedor" id="garantiaVendedor" maxlength="100" value="<?= $extras["garantia"]["vendedor"]?>" type="text" placeholder="Ej. 1 año, 3 meses, 30 dias, etc..." >
											</div>
										</td>
									</tr>
									<tr>
										<td>
											USUARIO
										</td>
										<td>
											<div id="check_garantiaUsuario">
												<input class="form-control form-style" name="garantiaUsuario" id="garantiaUsuario" maxlength="100" value="<?= $extras["garantia"]["usuario"]?>" type="text" placeholder="Ej. 1 año, 3 meses, 30 dias, etc...">
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
			<div class="col-md-6 ">
				<table class="table-sC">
					<tr>
						<td>
							<div class="top-gray form-group selecposition text-left">
								<label data-toggle="modal" data-target="#interiores" class="control-label pad-topp row-label pointer"><span class="icon-add"></span>&emsp;&emsp;AÑADIR CARACTER&Iacute;STICAS INTERIORES</label>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="top-gray form-group selecposition text-left">
								<label data-toggle="modal" data-target="#garantia" class="control-label pad-topp row-label pointer"><span class="icon-add"></span>&emsp;&emsp;AÑADIR GARANT&Iacute;A</label>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="top-gray form-group selecposition text-left">
								<label onclick="addCharacteristics($(this))" data-class="missingInput" data-aniadir='fallasmenores' class="control-label pad-topp row-label pointer"><span class="icon-add"></span>&emsp;&emsp;AÑADIR FALLAS MENORES</label>
								<div class="space-throw">
									<?php 
									if(!empty($extras["fallasmenores"])){
										foreach ($extras["fallasmenores"] as $ex => $extra) {
										?>
										<div class='agregar'> 
											<div class='input-group'> 
												<input type = 'text' name='fallasmenores[]' value="<?=$extra?>" class='form-control form-style missingInput' placeholder='Añadir' />  
											</div> 
							            </div>
										<?php
										}
									} ?>	
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6 ">
				<table class="table-sC">
					<tr>
						<td>
							<div class="top-gray form-group selecposition text-left">
								<label data-toggle="modal" data-target="#exteriores" class="control-label pad-topp row-label pointer"><span class="icon-add"></span>&emsp;&emsp;AÑADIR CARACTER&Iacute;STICAS EXTERIORES</label>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="top-gray form-group selecposition text-left">
								<label onclick="addCharacteristics($(this))" data-class="pieceInput" data-aniadir='piesas' class="control-label pad-topp row-label pointer"><span class="icon-add"></span> &emsp;&emsp;AÑADIR PIEZAS FALTANTES</label>
								<div class="space-throw">
									<?php 
									if(!empty($extras["piesas"])){
										foreach ($extras["piesas"] as $ex => $extra) {
										?>
										<div class='agregar'> 
											<div class='input-group'> 
												<input type = 'text' name='piesas[]' value="<?=$extra?>" class='form-control form-style pieceInput' placeholder='Añadir' />  
											</div> 
							            </div>
										<?php
										}
									} ?>	
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div class="top-gray form-group selecposition text-left">
								<label onclick="addCharacteristics($(this))" data-class="missingGreaterInput" data-aniadir='fallasmayores' class="control-label pad-topp row-label pointer"><span class="icon-add"></span>&emsp;&emsp;AÑADIR FALLAS MAYORES</label>
								<div class="space-throw">
									<?php 
									if(!empty($extras["fallasmayores"])){
										foreach ($extras["fallasmayores"] as $ex => $extra) {
										?>
										<div class='agregar'> 
											<div class='input-group'> 
												<input type = 'text' name='fallasmayores[]' value="<?=$extra?>" class='form-control form-style missingGreaterInput' placeholder='Añadir' />  
											</div> 
							            </div>
										<?php
										}
									} ?>	
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</form>
		<div class="editVehiclePhotos" id="editingPhotosDiv">
			<h4 class="col-xs-12">Editar imagenes del veh&iacute;culo</h4>
			<?php
			foreach ($imagenes as $keyimg => $imageBase) 
			{	
			?>
				<div class="inline-logos col-xs-3 newImgEdit" style="background-image: url('<?=$imageBase["a_avi_car_img_car"]?>')">
					<div class="font-trash">
				    	<a class="borrar icon-trash icon-trash-car" data-img='<?=$imageBase["imagenId"]?>' data-car='<?=$imageBase["a_avi_car_img_account_car_id"]?>' data-garage='<?= $llaveGarage?>' onclick="showModal($(this))" title="Borrar"></a>
					</div>
				</div>
			<?php
			}
			?>
			<form onsubmit="return false;" method="post" enctype="multipart/form-data" id="photoCar">
				<div class="inline-logos col-xs-6">
					<label for="imagenAuto" class="carImgLabel">
						<img id="addVechilePhoto" class="camara center-block" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/changePhoto.png" alt="">
						<input type='hidden' id='hiddenRouteCar' value=''/>
						<input type='hidden' name='garage' id='garage' value='<?= $_GET["cuenta"]?>'/>
						<input type='hidden' name='car' id='car' value='<?= isset($imageBase["a_avi_car_img_account_car_id"])? $imageBase["a_avi_car_img_account_car_id"] : $_GET["auto"] ?>'/>
					</label>
					<input name="imagenAuto" id="imagenAuto" class="inputfile" type="file" onchange="addingImg()" />
				</div>
			</form>
		</div> 
		<div class="row text-center">				    
		    <div class="col-xs-12 next-padd changes">
		    	Los cambios con <span class="asterisk">[*]</span> son obligatorios.<br>
		    	Los campos con <span class="asterisk">[**]</span> no son visibles al p&uacute;blico.
	        	<button class="btn btn-avi btn-block" type="button" onclick="sendAd($(this))">Anunciar</button>
	        	<?php if(isset($adDetailCar["idAnuncio"])){ 
	        		require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
	        		$coder = new Coder($adDetailCar["idAnuncio"]);
	        		?>
				<span class="delete-account" data-toggle="modal" data-target="#borrarAnuncio">Eliminar Anuncio</span>
	        	<?php }?>
		    </div>
	    </div> 
	</div>
	<div id="borrarAnuncio" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Borrar Anuncio</h4>
				</div>
				<div class="modal-body row text-center">
					<p>Se borrara el anuncio de este auto. ¿Est&aacute;s seguro que deseas eliminarlo?.</p>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal">CANCELAR</button> |
					<button type="button" class="btn modal-btns" id="del-ad" data-a="<?= urlencode($coder->encoded) ?>" data-dismiss="modal">ELIMINAR</button>
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
				<div class="modal-body row text-center">
				<?php foreach ($colores as $colorkey => $color) { ?>
		        	<a class='colores' data-dismiss='modal' data-clase='<?=$colorkey?>'><img class='clases-img-colors' src="<?=$color["img"]?>"><p><?=$color["nombre"]?></p></a>
		        <?php } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-avi-white" data-dismiss="modal">cerrar</button>
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
					<button type="button" class="btn modal-btns" onclick="hideimgCarModal($(this))">cerrar</button> |
					<button type="button" id="eliminarFoto" class="btn modal-btns" onclick="deletedImg($(this))">aceptar</button>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<script src="/js/garageNewCar.js?l=<?= LOADED_VERSION?>"></script>
	<script src="/js/editAuto.js?l=<?= LOADED_VERSION?>"></script>
>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>