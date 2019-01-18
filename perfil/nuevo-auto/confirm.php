<?php

/**
 * @Author: Cairo G. Resendiz
 * @Date:   2018-03-12 16:27:12
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-01 09:54:04
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(isset($_SESSION["user"]) && isset($_POST["version"]))
{
    require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';
    require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
    require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/country/country.php';
    $versionVentaid = $_POST["version"];
    $Country = new Country;
    $Version = new Version;
    $Venta = new Venta;
    $states = $Country -> states();
    $caracteristicas = $Version -> feature($versionVentaid);
    $precio = $Version -> precio($versionVentaid);
    if($precio !=0 )
    $precio = number_format($precio, 2, '.', ',');
    $estados = $Venta-> stateCar();
    $cp = $Venta->getZipCode($_SESSION["iduser"]);
    $colores = $Venta->colorCar();

    $_SESSION["venta"]["versionVenta"]= $_POST["version"];
    ?>
    <div class="col-xs-6 col-sm-10"></div>
    <div class="btn-group col-xs-4 col-sm-2" role="group" aria-label="..." style="padding: 18px; z-index: 2;"> 
	    <button type="button" class="btn btn-default" data-anio='<?= $caracteristicas[$versionVentaid]["C_Vehicle_Model_System_ID"]?>' data-clase='<?= $caracteristicas[$versionVentaid]["C_Vehicle_Versions_Class_ID"]?>' data-submarca='<?= $caracteristicas[$versionVentaid]["C_Vehicle_SubBrand_System_ID"]?>' onclick="showVersion($(this))">
	      <i class="fa fa-arrow-left"></i>
	      Regresar
	    </button>
	</div>

    <div class="col-xs-12 col-sm-6 col-lg-4 col-xs-offset-0 col-sm-offset-0 col-lg-offset-1 shadown-material" style="background: #f3f3f3; padding: 20px;">
    	<div class="body-container-specify">
    		<div class="row">
                <div class="col-md-12">
                	<label class="control-label">Sube imagenes de tu auto: </label>
                	<div id="dropzone">
	                	<form id="upload2" action="upload.php" class="dropzone needsclick" method="post" enctype="multipart/form-data">
	                		<div class="fallback">
							    <input name="file"  type="file" multiple />
						   	</div>
	                	</form>
                	</div>     
                </div>
                <div class="col-md-12">
                	<div class="form-group">
	                	<label class="control-label">Alias del auto: </label>
	                	<input id="alias" type="text" class="form-control" name="alias" maxlength="20" placeholder="nombre de tu carro">
                	</div>
                </div>
            </div>
    	</div>
    </div>
    <form>
		<div class="col-xs-12 col-sm-6 col-lg-4 col-xs-offset-0 col-sm-offset-0 col-lg-offset-2 venta shadown-material"  style="background: #f3f3f3; padding: 20px;">
			
	        <div class="cotizacion-body">
	            <div class="row text-center">
	                <div class="col-md-6"><h4><?= $caracteristicas[$versionVentaid]["C_Vehicle_Brand"]?></h4></div>
	                <div class="col-md-6"><h4><?= $caracteristicas[$versionVentaid]["C_Vehicle_SubBrand_Name"]?></h4></div>
	            </div>
	            <div class="row text-center">
	                <div class="col-md-12"><h4><?= $caracteristicas[$versionVentaid]["C_Vehicle_Model"]?></h4></div>
	            </div>
	            <div class="row text-center">
	                <div class="col-md-12"><h4><?= $caracteristicas[$versionVentaid]["C_Vehicle_Versions_Name"]?></h4></div>
	            </div>
	            <div class="row cotizacion-title text-center space">
	            	<div class="col-md-12">
	            		<input type="button" class="no-decoretion ad shadown-material" onclick="showPrice()" value="Vende tu Auto con @ApoyoVial">
	            		<input id="sell" name="sell" class="hidden" type="checkbox">
	            	</div>  
		        </div>
		        <div class="row" id="precio" style="display: none;">
	                <div class="col-md-12">
	                    <div class='form-group'>
	                        <label class="control-label">Precio sugerido (MX) : *</label>
	                        <input id="price" type="text" class="form-control" name="precio" maxlength="7" value='<?= $precio?>'>
	                    </div>
	                </div>
                    <div class="col-md-12">
                        <div class='form-group'>
                            <label class="control-label">KM recorridos :*</label>
                            <input id="km" type="text" class="form-control" name="km" id="km" placeholder="" maxlength="7">
                        </div>
                    </div>
	            </div>
	            <div class="row">
	                <div class="col-md-12">
	                    <div class='form-group'>
	                        <label class="control-label">Color del Auto :* </label>
	                        <select class="form-control" name="color" id="color">
	                            <option class="visible" value="0">
	                                Selecciona el color
	                            </option>
	                            <?php
	                            foreach ($colores as $colorkey => $color) {
	                            ?>
	                            <option value="<?= $colorkey?>">
	                                <?= $color?>
	                            </option>
	                            <?php
	                            }
	                            ?>
	                        </select>
	                    </div>
	                </div>
	            </div>
	            <div class="row">
                    <div class="col-md-12">
                        <div class='form-group'>
                            <label class="control-label">Condici&oacute;n del vehiculo :*</label>
                            <select class="form-control" name="estado" id="estado">
                                <option class="visible" value="0">
                                    Selecciona el estado del auto
                                </option>
                                <?php
                                foreach ($estados as $keystate => $estado) {
                                ?>
                                <option value="<?= $keystate?>">
                                    <?= $estado?>
                                </option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
	            <div class="row">
	                <div class="col-md-12">
	                    <div class='form-group'>
	                        <label class="control-label">VIN :*</label>
	                        <input type="text" class="form-control" name="vin" id="vinNum" placeholder="XXXXXXXXXXXXXXXXX" maxlength="17">
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</form>
    <div class="col-md-12 text-center">
        <button type="button" class="btn saveBtn shadown-material" name="button"  onclick="saveCar()" style="height: 54px; padding: 0 2rem;">Guardar</button>
    </div>
        <script>
    	$(document).ready(function(){
    		$("#km").on({
            "focus": function (event) {
                $(event.target).select();
            },
            "keyup": function (event) {
                $(event.target).val(function (index, value ) {
                    return value.replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
                });
            }
            });
	    	$("#price").on({
	            "focus": function (event) {
	                $(event.target).select();
	            },
	            "keyup": function (event) {
	                $(event.target).val(function (index, value ) {
	                    return value.replace(/\D/g, "")
	                        .replace(/([0-9])([0-9]{2})$/, '$1.$2')
	                        .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
	                });
	            }
	        });
		    
     	})
    </script>

    <?php

}
else {

    header('Location: ../');
}
?>