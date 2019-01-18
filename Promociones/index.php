<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-10 08:31:25
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:30:18
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
$auto=new Auto;
$Garage = new Garage;
$Version = new Version;   
require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';

$Venta = new Venta;
$garageContain= $Garage-> instanciaById($_GET["v"]);
$versionCar = $Version->feature($garageContain[0]["o_avi_car_version_id"]);
$autoObj=$versionCar[$garageContain[0]["o_avi_car_version_id"]];
$marcas=$auto->getMarcas();
$currMarca=null;
$curSubMarca=null;
$curModelo=null;
$curVersion=$garageContain[0]["o_avi_car_version_id"];
if(!empty($versionCar))
{
	$currMarca=$autoObj["C_Vehicle_Brand_System_ID"];
	$curSubMarca=$autoObj["C_Vehicle_SubBrand_System_ID"];
	$curModelo=$autoObj["C_Vehicle_Model_System_ID"];
}
$submarcas=$auto->getSubMarcas($currMarca);
$modelos=$auto->getModels($currMarca,$curSubMarca);
$versiones=$auto->knowVersion($curModelo);

$carpicture=$auto->imagenes();
$detalles = $Garage -> getUserdetail($_SESSION["iduser"]);
$estados = $Venta-> stateCar();
$colores = $Venta->colorCar();
?>

<div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3" style="margin-top: 150px; margin-bottom: 50px;">
	<form id="formCampana">
		<div class="carrusel col-xs-12" id="carrusel" data-last="1" data-steps="3">
			<div class="carrusel-left carrusel-nav">
				<i class="fa fa-angle-double-left"></i>
			</div>
			<div class="carrusel-right carrusel-nav">
				<i class="fa fa-angle-double-right"></i>
			</div>
			<div class="carrusel-content">
			<?php
			$i=1;
		foreach ($carpicture as $idbrand => $carp)
		{
		?>
				<div class="img-carrusel carrusel-marca-img img-carrusel-<?=$i?> inline-logos" data-marca="<?=$idbrand?>">
					<img src="<?= $carp["logo"]?>" alt="" class="img-thumbnail img-responsive ">
			</div>
			<?php
			$i+=1;
			}
			?>
			</div>
		</div>
		<div class="form-group col-xs-12 col-md-6 selectdiv">
			<label class="control-label col-xs-12 no-padding">Marca <span class="obligatorio asterisk">*</span></label>
			<select class="form-control form-style" id="marca" name="marca">
				<option class="visible" value="0">Selecciona una marca</option>
			<?php 
			foreach ($marcas as $m => $marca) 
			{ 
				$selected="";
				if($m==$currMarca)
				{
					$selected="selected";
				}
			?>
				<option class="visible" value="<?= $m?>" <?= $selected?>><?= $marca?></option>
			<?php 
			} 
			?>
				<option class="visible" value="-1">Otra</option>
			</select>
		</div>
		<div class="form-group col-xs-12 col-md-6 selectdiv">
			<label class="control-label col-xs-12 no-padding">Modelo <span class="obligatorio asterisk">*</span></label>
			<select class="form-control form-style" id="modelo" name="submarca">
				<option value="0">Selecciona el modelo</option>
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
			?>
				<option value="-1">Otro</option>
			</select>
		</div>
		<div class="form-group col-xs-12 col-md-6 selectdiv">
			<label class="control-label col-xs-12 no-padding">A&ntilde;o <span class="obligatorio asterisk">*</span></label>
			<select class="form-control form-style" id="ano" name="modelo">
				<option value="0">Selecciona el a&ntilde;o</option>
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
			?>
				<option value="-1">Otro</option>
			</select>
		</div>
		<div class="form-group col-xs-12 col-md-6 selectdiv">
			<label class="control-label col-xs-12 no-padding">Versi&oacute;n <span class="obligatorio asterisk">*</span></label>
			<select class="form-control form-style" id="version" name="subnombres">
				<option value="0">Selecciona la versi&oacute;n</option>
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
			?>
			</select>
		</div>
		<div class="form-group col-xs-12 col-md-6 selectdiv">
			<label class="control-label col-xs-12 no-padding">Color <span class="obligatorio asterisk">*</span></label>
			<select class="form-control form-style" name="color" id="color">
	        <option class="visible" value="0">
	            Selecciona el color
	        </option>
	        <?php
	        foreach ($colores as $colorkey => $color) {
	        	$selected="";
	        	if($garageContain[0]["o_avi_car_color"]==$colorkey)
	        	{
	        		$selected="selected";
	        	}
	        ?>
	        <option value="<?= $colorkey?>" <?= $selected?>>
	            <?= $color?>
	        </option>
	        <?php
	        }
	        ?>
	    </select>
		</div>
		<div class="form-group col-xs-12 col-md-6 selectdiv">
			<label class="control-label col-xs-12 no-padding">Condici&oacute;n del veh&iacute;culo <span class="obligatorio asterisk">*</span></label>
			<select class="form-control form-style" name="estado" id="estado">
	        <option class="visible" value="0">
	            Selecciona el estado del auto
	        </option>
	        <?php
	        foreach ($estados as $keystate => $estado) {
	        	$selected="";
	        	if($garageContain[0]["i_avi_account_car_state"]==$keystate)
	        	{
	        		$selected="selected";
	        	}
	        ?>
	        <option value="<?= $keystate?>" <?= $selected?>>
	            <?= $estado?>
	        </option>
	        <?php
	        }
	        ?>
	    </select>
		</div>
		<div class="form-group col-xs-12 col-md-6 ">
			<label class="control-label col-xs-12 no-padding">VIN <span class="obligatorio asterisk">*</span></label>
			<input class="form-control form-style" name="vin" id="vinNum" placeholder="XXXXXXXXXXXXXXXXX" maxlength="17" type="text" value="<?= $garageContain[0]["o_avi_car_vin"]?>">
		</div>
		<div class="form-group col-xs-12 col-md-6 ">
			<label class="control-label col-xs-12 no-padding">Alias del veh&iacute;culo</label>
			<input class="form-control form-style" name="alias" id="alias" placeholder="Alias o apodo para tu auto" maxlength="17" type="text" value="<?= $garageContain[0]["i_avi_account_car_alias"]?>">
		</div>
		<input type="hidden" name="carbrand_c" id="carbrand_c">
		<input type="hidden" name="carsubbrand_c" id="carsubbrand_c">
		<input type="hidden" name="carmodel_c" id="carmodel_c">
		<input type="hidden" name="carversion_c" id="carversion_c">
		<input type="hidden" name="description" id="description">
		<input type="hidden" name="email1" id="email1" value="<?= $detalles["o_avi_user_email"]?>">
		<input id="assigned_user_id" type="hidden" name="assigned_user_id" value="4c7b09f6-0631-b860-cd67-58d3c9baee05" />
		<input id="campaign_id" type="hidden" name="campaign_id" value="<?= $_GET["c"]?>" />
		<div class="col-xs-12 col-md-6 col-xs-offset-0 col-md-offset-3">
			<button class="btn btn-avi btn-block" type="button" onclick="sendData()">Solicitar</button>	
		</div>
	</form>
</div>
<div id="mensaje" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>&Eacute;xito</h4>
			</div>
			<div class="modal-body">
				Gracias por hacer tu solicitud, en breve nos pondremos en ccontacto contigo.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-avi-white" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-avi" onclick="window.location.href='/'">Regresar A ApoyoVial</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/promociones.js?l=<?= LOADED_VERSION?>"></script>