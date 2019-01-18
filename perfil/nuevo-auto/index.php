<?php
/**
 * @Author: Cairo G. Resendiz & Brenda Quiroz <3
 * @Date:   2018-03-07 16:36:34
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:52:14
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(isset($_SESSION["iduser"]) && isset($_GET["cuenta"]) && $_SESSION["iduser"]==$_GET["cuenta"])
{
	require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
	require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';	
	require_once ($_SERVER['DOCUMENT_ROOT']).'/php/Venta/Venta.php';  
	$auto=new Auto;
	$Garage = new Garage;
	$Usuario = new Usuario;    
	$Venta = new Venta;
    $cuenta = $_GET["cuenta"];   
	$marcas   = $auto->getMarcas();
	$submarcas= $auto->getSubMarcas();
	$modelos  = $auto->getModels();
	$versiones= $auto->knowVersion();	  
    $colores = $Venta->colorCar();
    $estados = $Venta-> stateCar();
	$garage = $Garage -> accountById($_GET["garage"]);
	$imgPerfil = $Garage->getImgPerfil($_GET["cuenta"]);
	$detalles = $Garage -> getUserdetail($_GET["cuenta"]);
	$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
	$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$_GET["garage"]));
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
	if($garage)
	{
	?>
	<div class="content header-space form-send">
    	<div class=" text-center garages title-garage" data-garage='<?= $garage["idAccount"]?>'>
			<button class="go-back" onclick="history.back()">
				<span class="glyphicon glyphicon-chevron-left"></span>
			</button>
			Mi Garage: <?= $garage["nameAccount"]?>
		</div>
		<?php 
		if($garage["user"] == $cuenta)
		{
			$carpicture=array();
			$carpicture=$auto->imagenes();
			$arrayn=count($carpicture);
			$_SESSION["venta"]["cuentaid"] = $garage["idAccount"];
			$_SESSION["venta"]["nombrecuenta"] = $garage["nameAccount"]; 
		?>
		<h3>Agrega un auto al garage:</h3>
		<form id="formNewCar" class="" method="POST" action="" name="formCarForGarage">
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
 			<div class="form-group col-xs-12 col-md-6 ">
 				<label class="control-label col-xs-12 no-padding">Alias del veh&iacute;culo <span class="obligatorio asterisk">*</span></label>
 				<input class="form-control form-style" name="alias" id="alias" placeholder="Alias o apodo para tu auto" maxlength="17" type="text">
 			</div>
 			<div class="form-group col-xs-12 col-md-6 selectdiv">
 				<label class="control-label col-xs-12 no-padding">Marca</label>
 				<select class="form-control form-style" id="marca" name="marca">
 					<option class="visible" value="0">Selecciona una marca</option>
	 				<?php 
	 				foreach ($marcas as $m => $marca) 
	 				{ ?>
 						<option class="visible" value="<?= $m?>"><?= $marca?></option>
	 				<?php 
	 				} ?>
 					<option class="visible" value="-1">Otra</option>
 				</select>
     		</div>
 			<div class="form-group col-xs-12 col-md-6 selectdiv">
 				<label class="control-label col-xs-12 no-padding">Modelo</label>
 				<select class="form-control form-style" id="modelo" name="submarca">
 					<option value="0">Selecciona el modelo</option>
	 				<?php 
	 				foreach ($submarcas as $sm => $submarca) 
					{ ?>
 						<option data-marca="<?= $submarca["marca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
	 				<?php 
	 				} ?>
 					<option value="-1">Otro</option>
 				</select>
 			</div>
 			<div class="form-group col-xs-12 col-md-6 selectdiv">
 				<label class="control-label col-xs-12 no-padding">A&ntilde;o</label>
 				<select class="form-control form-style" id="ano" name="modelo">
 					<option value="0">Selecciona el a&ntilde;o</option>
	 				<?php 
	 				foreach ($modelos as $md => $modelo) 
	 				{ ?>
 						<option value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
	 				<?php 
	 				} ?>
 					<option value="-1">Otro</option>
 				</select>
 			</div>
 			<div class="form-group col-xs-12 col-md-6 selectdiv">
 				<label class="control-label col-xs-12 no-padding">Versi&oacute;n</label>
 				<select class="form-control form-style" id="version" name="subnombres">
 					<option value="0">Selecciona la versi&oacute;n</option>
	 				<?php 
	 				foreach ($versiones as $vr => $version) 
					{ ?>
 						<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>"> <?= $version["version"]?> <?= $version["subnombre"]?> </option>
	 				<?php 
	 				} ?>
 				</select>
 			</div>
 			<div class="form-group col-xs-12 col-md-6 selectdiv">
 				<label class="control-label col-xs-12 no-padding">Color</label>
 				<select class="form-control form-style" name="color" id="color">
                    <option class="visible" value="0">
                        Selecciona el color
                    </option>
                    <?php
                    foreach ($colores as $colorkey => $color) 
                    { ?>
                    	<option value="<?= $colorkey?>"> <?= $color?> </option>
                    <?php
                    } ?>
                </select>
 			</div>
 			<div class="form-group col-xs-12 col-md-6 selectdiv">
 				<label class="control-label col-xs-12 no-padding">Condici&oacute;n del veh&iacute;culo</label>
 				<select class="form-control form-style" name="estado" id="estado">
                    <option class="visible" value="0">
                        Selecciona el estado del auto
                    </option>
                    <?php
                    foreach ($estados as $keystate => $estado) 
                	{ ?>
                    	<option value="<?= $keystate?>"><?= $estado?> </option>
                    <?php
                    } ?>
                </select>
 			</div>
 			<div class="form-group col-xs-12 col-md-6 ">
 				<label class="control-label col-xs-12 no-padding">VIN</label>
 				<input class="form-control form-style" name="vin" id="vinNum" placeholder="XXXXXXXXXXXXXXXXX" maxlength="17" type="text">
 			</div>
			<input type="hidden" id="garage" name="garage" value="<?= $_GET["garage"]?>">
			<input type="hidden" id="cuenta" name="cuenta" value="<?= $cuenta?>">
		</form>
		<div class="col-xs-12 col-xs-offset-0 col-md-6 col-md-offset-3" style="padding: 20px;">
	    	<div class="body-container-specify">
            	<label class="control-label">Sube imagenes de tu auto: </label>
            	<div id="dropzone">
                	<form id="upload2" action="upload.php" class="dropzone-garage dropzone needsclick" method="post" enctype="multipart/form-data">
                		<div class="fallback">
						    <input name="file"  type="file" multiple />
					   	</div>
                	</form>
            	</div>     
	    	</div>
	    </div>
	    <div class="row text-center">				    
		    <div class="col-xs-12 col-md-6 next-padd changes">
		    	Los cambios con <span class="asterisk">[*]</span> son obligatorios.
		    </div>
		    <div class=" col-xs-12 col-md-6 next-padd">
		    	<a class="pointer advise"  data-toggle="modal" data-target="#soonModal">Aviso de Privacidad</a>
		    </div>
	    </div>

	    <div class="col-xs-12">
	        <button class="btn btn-avi btn-block" type="button" onclick="saveCar()">Guardar</button>
	    </div>    
		<?php 
		}
		?>
	</div>
	<?php
	}
	else
	{
		?>
		<h1>El garage no existe &oacute; la direcci&oacute;n es incorrecta.</h1>
		<?php
	}
}
else
{
?>
    <script> location.replace("../"); </script>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/proximamente/proximamente.php';
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>
<script src="/js/dropzone.js?l=<?= LOADED_VERSION?>"></script>
<script src="/js/garageNewCar.js?l=<?= LOADED_VERSION?>"></script>

