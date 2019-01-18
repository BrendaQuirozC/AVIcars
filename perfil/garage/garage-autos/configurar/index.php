<?php
/**
 * Created by PhpStorm.
 * User: Brenda Quiroz
 * Date: 16/01/2018
 * Time: 12:54 PM
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["garage"])){
	header("Location: /");
}
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/login/address.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$garageEncoded=$_GET["garage"];
$coder->decode($_GET["garage"]);
$_GET["garage"]=$coder->toEncode;
$address = new Address;
$Garage = new Garage;
$detalles = array();
$Usuario = new Usuario;
$country=new Country;
$phoneCodes=$country->getPhoneCodes();
$garageCurr=0;
if(isset($_GET["garage"])){
	$garageCurr=$_GET["garage"];
	$garage=$Garage->accountById($garageCurr);
}
$privacyToChange=json_encode(array("tipo" =>2,"privacy"=>$garageCurr));
$show=true;
if(isset($_GET["cuenta"]) && isset($_GET["garage"]))
{
	$cuenta = $_GET["cuenta"];
    $nombreCuenta= $Usuario->getGarage($_GET["garage"]);
	$garage = $Garage ->accountById($_GET["garage"]);
	if(empty($garage)){
		$show=false;
	}
	else{
		$garages = $Garage -> account($garage["user"]);
		$imgPerfil = $Usuario->getImgPerfil($garage["user"]);
		$detalles = $Garage -> getUserdetail($garage["user"]);
		$infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
		if(empty($infoPerfil)){
			$show=false;
		}else{
			if(!empty($detalles))
			{
				$Version = new Version;
				$instancia = array();
				$llaveGarage = $_GET["garage"];
				$extrasGarage = $Garage->getGarageExtras($_GET["garage"]);
				$privacyGarage = $Garage -> getGarageInfo($_GET["garage"]);
				$active="configure";
				$editing = "yes";
				$Like = new Like;

			}
			else{
				$show=false;
			}
		}
	}
}
else{
	$show=false;
}
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
if($garage["user"]!=$_GET["cuenta"]){
	$show=false;
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(!$owner&&!$colaborador){ 
	$show=false;
}
else{
	$metasShare=array(
		"og"	=>	array(
			"title" => "AVI cars by Infotraffic | Perfil",
		    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
		    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
		    "site_name" => "AVI cars",
		    "type" => "website"
		),
		"tw"	=>	array(
			"title" => "AVI cars by Infotraffic | Perfil",
		    "description" => "Garage ".$garage["nameAccount"]." de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
		    "image:alt" => "AVI cars",
		    "card" => "summary_large_image"
		)
	);
}
if($show){ 
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
	$garageContain = $Garage ->accountInstancia($_GET["garage"]);
	$usosGarage=$Garage->getAccountUses();
		?>
		<div class="content">
		    <div class="row form-send">
				<form id="formEditGarage"  method="post" enctype="multipart/form-data">
			        
		            <div id="name_garage" class="form-group col-md-6 col-xs-12">
		                <label for="garageName">NOMBRE DEL GARAGE</label>
		                <input type="text" class="form-control form-style" name="garageName" autocomplete="off" id="garageName" value="<?= !empty($privacyGarage["nombre"]) ? $privacyGarage["nombre"] : ""?>"  maxlength="45">
		            </div>
			    	<div class="form-group col-md-6 col-xs-12 descrp">
						<label class="control-label col-xs-12 no-padding">DESCRIPCI&Oacute;N</label>
						<textarea class="form-control form-style-box biograf" maxlength="160" rows="3" name="descripcion" id="descripcion" placeholder="Breve descripci&oacute;n de su garage. (M&aacute;x. 160 car&aacute;cteres)"><?= (isset($extrasGarage["description"])) ? $extrasGarage["description"] : "" ?></textarea>
					</div>
		            <div id="phone_garage" class="form-group col-md-6 col-xs-12 form-group-block">
	                    <label for="garagePhone">TELEF&Oacute;NO (OPCIONAL)</label>
	                    <select class="form-control form-style" name="phonecode">
	                        <?php 
	                        foreach ($phoneCodes as $c => $code) { ?>
	                            <option value="<?= $c?>" <?= isset($privacyGarage["telefono"]) ? (($c==$privacyGarage["telefonocode"]) ? "selected" : "") : ""  ?>><?= $c?> <?= $code?></option>
	                        <?php }
	                         ?>
	                    </select>
	                    <input type="text" class="form-control form-style" maxlength="10" name="garagePhone" autocomplete="off" id="garagePhone" placeholder="(10 dígitos)" value="<?= isset($privacyGarage["telefono"]) ? $privacyGarage["telefono"] : ""?>">
	                </div>
	                <div id="change_phone" class="form-group col-md-6 col-xs-12 form-group-block">
	                    <label for="signUpPhone">TELEF&Oacute;NO CELULAR (OPCIONAL)</label>
	                    <select class="form-control form-style" name="cellphonecode">
	                        <?php 
	                        foreach ($phoneCodes as $c => $code) { ?>
	                            <option value="<?= $c?>" <?= isset($privacyGarage["celular"]) ? (($c==$privacyGarage["celularcode"]) ? "selected" : "") : ""  ?>><?= $c?> <?= $code?></option>
	                        <?php }
	                         ?>
	                    </select>
	                    <input type="text" class="form-control form-style" maxlength="10" name="garageCellPhone" autocomplete="off" id="garageCellPhone" placeholder="(10 dígitos)" value=<?= isset($privacyGarage["celular"]) ? $privacyGarage["celular"] : ""?>>
	                    <div class="checkbox checkbox-contact">
	                        <input type="checkbox" name="cellphonewa" value="1" <?= isset($privacyGarage["celular"]) ? ((1==$privacyGarage["celularwa"]) ? "checked" : "") : ""  ?>><img src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/Home_Movil_ViewPort_iconBoton_WhatsApp_infotraffic.png">
	                    </div>
	                </div>
			        <div class="form-group col-md-6 col-xs-12">
		            	<label for="garageZipcode">UBICACI&Oacute;N</label>
		            	<div id="garage_zipcode">
		                    <input type="text" class="form-control form-style" name="garageZipcode" id="garageZipcode" value="<?= (isset($privacyGarage["zip"])) ? $privacyGarage["zip"]: NULL?>" placeholder="C&oacute;digo Postal" onchange="zip($(this))"  maxlength="7">
		                </div>
		                <div id="garage_street" >
		                    <input type="text" class="form-control form-style" name="garageStreet" id="garageStreet" value="<?= (isset($privacyGarage["calle"])) ? $privacyGarage["calle"]: ""?>" placeholder="Calle, n&uacute;mero y colonia"  maxlength="50">
		                </div>          
			            <div>
			                <input id="delegacion" type="text" class="form-control form-style" name="garageCity" placeholder="Delegaci&oacute;n / Municipio" disabled>
			            </div>
			            <div>
			                <input id="estado" type="text" class="form-control form-style" name="garageState" placeholder="Estado" disabled>
			            </div>
			    	</div>
			    	<div class="form-group col-md-6 col-xs-12"> 
				        <label for="garageUse">USO DEL GARAGE</label>
			            <div id="use_garage" class="checkbox-no-padding">  
			           <?php foreach ($usosGarage as $u => $uso) { ?>
			           		<label class="radio">
				                <input type="radio" class="" name="garageUse" value="<?= $u ?>" <?= ($privacyGarage["usoId"]==$u) ? "checked" : "" ?>> <?= $uso?>
				            </label>
			           <?php } ?>
			            
			            </div>
			    	</div>
			    	<input type="hidden" name="cuenta" value="<?= $cuentaEncoded?>">
			    	<input type="hidden" name="garage" value="<?= $garageEncoded?>">
				    <div class="col-xs-12 text-center" style="padding-top: 30px;">
				    	<?php if($garage["verified"]){ ?>
						<button class="btn cuenta-btns" type="button" data-toggle="modal" data-target="#update_info_garage_verified">Guardar</button>
						<?php }else{ ?>
						<button class="btn cuenta-btns" type="button" onclick="personalizarGarage()">Guardar</button>
						<?php } ?>
						<?php if($owner){ ?>
			            <button type="button" class="btn cuenta-btns delete-border" data-toggle="modal" data-target="#deleteGarage"> BORRAR GARAGE</button>
			        	<?php } ?>
			            <button class="btn cuenta-btns delete-border" type="button" onclick="window.location.href='/perfil/garage/colaboradores?cuenta=<?= $cuentaEncoded?>&garage=<?= $garageEncoded?>'">Colaboradores</button>
			    	</div>     
			    </form>
			</div>
		</div>
		<div class="modal fade"  id='deleteGarage' role='dialog'>
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="title-header modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4>Eliminar Garage</h4>
						</div>
						<div class="modal-body">
							Se eliminar&aacute; toda la informaci&oacute;n que el Garage contenga ¿Deseas borrar garage?
						</div>
						<div class="footer-line modal-footer">
							<button type="button" class="btn modal-btns" data-dismiss="modal" >Cerrar</button> |
							<button type="button" class="btn modal-btns" data-padre="<?= $_GET["garage"]?>" onclick="borrarNombre($(this));">Eliminar</button>
						</div>
					</div>
				</div>
			</div>
		<script src="/js/edit.js?l=<?= LOADED_VERSION?>"></script>
		<script type="text/javascript" src="/js/configurar.js?l=<?= LOADED_VERSION?>"></script>
<?php
		
}
else {
	?>
	<div class="row" style="margin: 80px 0px 25px 0px;">
		<h3 class="text-center">
			Esta p&aacute;gina no est&aacute; disponible
		</h3>
	</div>
<?php
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>