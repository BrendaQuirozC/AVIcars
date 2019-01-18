<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-29 09:19:15
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-29 16:26:21
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();

if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["auto"])){
	header("Location: /");
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Archivo/archivo.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once ($_SERVER["DOCUMENT_ROOT"]).'/php/catalogoAutos/auto.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/php/login/address.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/Anuncio.php";
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once($_SERVER["DOCUMENT_ROOT"])."/php/Utilities/coder.php";
$coder = new Coder();
$cuentaEncoded=$_GET["cuenta"];
$coder->decode($_GET["cuenta"]);
$_GET["cuenta"]=$coder->toEncode;
$autoEncoded=$_GET["auto"];
$coder->decode($_GET["auto"]);
$_GET["auto"]=$coder->toEncode;
$address=new Address;
$auto=new Auto;
$Garage = new Garage;
$anuncio=new Anuncio;
$Seguidor = new Seguidor;
$Usuario = new Usuario;
$detalles = array();
if(isset($_GET["auto"]))
{
    $nombreCuenta= $Usuario->getGarage();
    $garageContain= $Garage-> instanciaById($_GET["auto"]);
    $colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],2);
    $colaboradorCont=$Garage->getAUserAccount($_SESSION["iduser"], $garageContain[0]["i_avi_account_car_account_id"],3);
    if(!empty($garageContain)){
    	$garage = $Garage ->accountById($garageContain[0]["o_avi_account_id"]);
    }
    if((!isset($garage) || $garage["user"]!=$_GET["cuenta"] || $garage["user"]!=$_SESSION["iduser"])&&!$colaborador)
	{

		$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"]));
		$privacidad=3; 
		include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';?>
			<div class="row" style="margin: 80px 0px 25px 0px;">
				<h3 class="text-center">
					Esta p&aacute;gina no est&aacute; disponible
				</h3>
			</div>
			<?php
			exit;
	}
    $imgPerfil = $Usuario->getImgPerfil($garage["user"]);
    $infoPerfil = $Usuario->getInfoPerfil($garage["user"]);
    $cuenta = $garage["user"];
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacyToChange=json_encode(array("tipo" =>3,"privacy"=>$_GET["auto"])); 
	
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
}
$Like = new Like;
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';

if(!empty($detalles))
{
	$Version = new Version;
	$instancia = array();
	$garages = $Garage -> account($cuenta);
	$extrasGarage = $Garage->getGarageExtras($garageContain[0]["o_avi_account_id"]);
	$archivo=new Archivo;
	$files=$archivo->getFilesByObject($_GET["auto"],1);
	$ads=$anuncio->getAnunciosByCar($_GET["auto"]);
	$fileTypes=$archivo->getFileTypesByTypeObject(1);
	$notImage=false;
	$imagenes = $Garage ->imagenesGenerales($_GET["auto"]);
	//echo "<pre>";
	//print_r($garageContain);
	//echo "</pre>";
	$features=array();
	if(!empty($versionCar))
		$features = json_decode($versionCar[$versionNum]["c_vehicle_versions_extraSpecifications"],true);

	if(empty($imagenes))
	{
		$notImage='/img/noimage.png';
	}
	$adDetailCar=$auto->adCar($_GET["auto"]);
	$zipcodeAddressAd=null;
	if(isset($adDetailCar["zipcode"]))
	{
		$zipcodeAddressAd=$address->add($adDetailCar["zipcode"]);
	}
	$currMarca=null;
	$curSubMarca=null;
	$curModelo=null;
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
	$submarcas=$auto->getSubMarcas($currMarca);
	$modelos=$auto->getModels($currMarca,$curSubMarca);
	if($garageContain[0]["o_avi_car_version_id"]  && $curModelo)
		$versiones=$auto->knowVersion($curModelo);

	$active="docs";
	$coder->encode($_GET["auto"]);
	$autoCoded=$coder->encoded;
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/auto/headerAuto.php';
?>
<div class="content">
	<div class="row">
		<div class="col-xs-12">
			<h3>Anuncios</h3>
		</div>
		<div class="col-xs-12">
			<table class="table table-stripped table-ads">
				<thead>
					<tr>
						<th>Auto</th>
						<th>Precio</th>
						<th class="hidden-xs hidden-sm visible-md visible-lg">Publicacion</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php if(empty($ads)){ ?>
					<tr>
						<td colspan="5" align="center">No tienes anuncios</td>
					</tr>
				<?php } ?>
				<?php foreach ($ads as $a => $ad) { 
					$coder->encode($ad["anuncio"]);
					$linkAnuncio=$coder->encoded;
					$coder->encode($ad["autoId"]);
					$autoCoded=$coder->encoded;
					?>
					<tr class="<?= ($ad["vendido"]) ? "sold" : "" ?>">
						<td>
							<a href="/anuncio/?a=<?= urlencode($linkAnuncio)?>"><?= $ad["auto"]?></a>
						</td>
						<td>
							$<?= number_format($ad["precio"],2,".",",")?>
						</td>
						<td class="hidden-xs hidden-sm visible-md visible-lg">
							<?= date("M d, Y - H:i\h\\r\\s",strtotime($ad["publicacion"])) ?>
						</td>
						<td align="center">
							<ul class="list-inline list-files">
							<?php if(!$ad["vendido"]){ ?>
								<li>
									<img title="Editar Anuncio" onclick="window.location.href='/perfil/autos/detalles/editar/?cuenta=<?= $cuentaEncoded ?>&auto=<?= $autoCoded?>'" src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/MyGarages_Movil_boton_editarNombreGarage_infotraffic.png">
								</li>
								<li class="hidden-xs">
									<img title="Marcar como vendido" src="/img/webpageAVI/Movil_infotraffic/MyCars_Movil_infotraffic/MyGarages_Movil_ViewPort_downmenu_boton_Ventas_infotraffic.png" data-car="<?= $autoCoded?>" data-ad="<?= urlencode($linkAnuncio)?>" onclick="toSold($(this))">
								</li>
								<li class="hidden-xs">
									<img title="Eliminar Anuncio" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_gris-16px_infotraffic.png" data-ad="<?= urlencode($linkAnuncio)?>" data-toggle="modal" data-target="#borrarAnuncio" onclick="GoToDeleteAd($(this))">
								</li>
								<li class="hidden-xs submenu">
									<img title="Compartir" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_boton_compartir-opc2_infotraffic.png" onclick="shareThis($(this))">
									<ul class="navigation-list">
										<li onclick="doShare($(this),5)" data-p="<?= $ad["anuncio"] ?>">En AVI cars</li>
										<li onclick="doShareWhatsApp($(this))" data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".urlencode($linkAnuncio) ?>">En WhatsApp</li>
										<li data-target="<?= (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/anuncio/?a=".urlencode($linkAnuncio) ?>" onclick="copyShare(this,$(this))">Copiar link </li>
									</ul>
								</li>
							<?php } ?>
							</ul>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="col-xs-12">
			<h3>Documentos</h3>
		</div>
		<div class="col-xs-12 text-right">
			<button class="btn btn-avi" onclick="loadFile()">Cargar Documento</button>
		</div>
		<div class="col-xs-12">
			<table class="table table-stripped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Tipo</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
				<?php if(empty($files)){ ?>
					<tr>
						<td colspan="3" align="center">No tienes documentos</td>
					</tr>
				<?php } ?>
				<?php foreach ($files as $f => $file) { ?>
					<tr>
						<td><?= ($file["filename"]!="") ? $file["filename"] : $file["typeName"] ?></td>
						<td><?= $file["typeName"] ?></td>
						<td>
							<ul class="list-inline list-files">
								
								<li>
									<img data-toggle="modal" data-target="#descargarfile" onclick="openFile(<?= $file["object"] ?>, <?= $file["id"] ?>, <?= $file["type"] ?>)" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/MyGarages_Movil_ViewPort_downmen_download.png">
								</li>
								<li>
									<img onclick="modalDeleteFile(<?= $file["object"] ?>, <?= $file["id"] ?>, <?= $file["type"] ?>)" src="/img/webpageAVI/Movil_infotraffic/Profile_Movil_infotraffic/Profile_Movil_ViewPort_iconBoton_gris-16px_infotraffic.png">
								</li>
								<li>
									<img data-toggle="modal" data-target="#extrasDocumento" onclick="verExtras($(this))" data-extras="<?= $file["extras"]?>" src="/img/webpageAVI/Movil_infotraffic/Home_Movil_infotraffic/Home_Movil_home_boton_crearNuevo_infotraffic.png">
								</li>
							</ul>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="extrasDocumento" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Informaci&oacute;n Adicional</h4>
			</div>
			<div class="modal-body row text-center" id="adicional">
				
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">CERRAR</button>
			</div>
		</div>
	</div>
</div>
<div id="sellCarModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Vender auto</h4>
			</div>
			<div class="modal-body row text-center">
				<p>&iexcl;Felicidades! Ha vendido su auto. Favor de confirmar que esto es correcto.</p>
				<p class="warnSellCar">* Una vez confirmado este cambio  <b>no podr&aacute; ser revertido.</b> ¿Continuar? </p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">CANCELAR</button> |
				<button type="button" class="btn modal-btns" id="soldCar" data-a="" data-dismiss="modal">CONFIRMAR</button>
			</div>
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
				<button type="button" class="btn modal-btns" id="borrarAd" data-a="" data-dismiss="modal">ELIMINAR</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalAddFile">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Agregar Documento</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="" id="formFiles" name="formCarForGarage">
					<div class="form-group selectdiv">
						<label class="control-label">Elige un tipo de Documento</label>
						<select class="form-control form-style" name="type" id="fileToUploadName">
							<option value="0">Selecciona un tipo de Documento</option>
						<?php foreach ($fileTypes as $t => $tipo) { ?>
							<option value="<?= $tipo["id"] ?>" data-extras="<?= base64_encode($tipo["extra"])?>"><?= $tipo["nombre"] ?></option>
						<?php } ?>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">Nombre de tu documento (Opcional)</label>
						<input type="text" name="nombre" class="form-control form-style">
					</div>
					<div class="form-group">
						<label class="control-label">Sube tu documento</label>
						<input type="file" name="file" id="filesToUploadCar">
						<input type="hidden" name="object" value="<?= $autoCoded?>">
					</div>
					<div id="extradata" class="extradata row"></div>
				</form>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
				<button type="button" class="btn modal-btns" onclick="subir();">Subir</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalDeleteFile">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Borrar Documento</h4>
			</div>
			<div class="modal-body">
				<p class="text-center">¿Seguro que desea borrar el documento?</p>
				<div class="form-group">
					<label class="control-label">Introduce tu contrase&ntilde;a para Eliminar</label>
					<input type="password" name="pwd" id="pwdDel" class="form-control form-style">
				</div>
				<div id="questionDownload" class="glyphicon glyphicon-question-sign question-icon"></div>
				<div id="questionDownloadText" class="question-icon-text">Si inci&oacute; sesi&oacute;n con Google o Facebook y nunca ha cambiado su contraseña, por favor registre una nueva contraseña con "olvidé mi contraseña"</div>
				<p class=" contra"><a class="a-contra" onclick="onClickModal()">Olvid&eacute; mi contrase&ntilde;a</a></p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
				<button type="button" class="btn modal-btns" id="borrarBoton" onclick="deleteFile($(this))" data-u="0" data-t="0" data-f="0">Borrar</button>
			</div>
		</div>
	</div>
</div>
<div id="comprar" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">CONTACTO DEL VENDEDOR</h4>
			</div>
			<div class="modal-body text-center">
				<h5>Tel&eacute;fono: <?= $adDetailCar["phone"] ? $adDetailCar["phone"] : "Sin especificar"?></h5>
				<h5>Correo Electronico: <?= $adDetailCar["email"] ? $adDetailCar["email"] : "Sin especificar"?></h5>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button>
			</div>
		</div>
	</div>
</div>
<div id="descargarfile" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4>Descargar Documento</h4>
			</div>
			<div class="modal-body">
				<form id="descargar" action="/perfil/docs/download/index.php" target="_blank" method="POST">
					<div class="form-group">
						<label class="control-label">Introduce tu contrase&ntilde;a</label>
						<input type="password" name="pwd" id="pwd" class="form-control form-style">
						<input type="hidden" name="u" id="file-u">
						<input type="hidden" name="t" id="file-t">
						<input type="hidden" name="f" id="file-f">
					</div>
				</form>
				<div id="questionDownload" class="glyphicon glyphicon-question-sign question-icon"></div>
				<div id="questionDownloadText" class="question-icon-text">Si inci&oacute; sesi&oacute;n con Google o Facebook y nunca ha cambiado su contraseña, por favor registre una nueva contraseña con "olvidé mi contraseña"</div>
				<p class=" contra"><a class="a-contra" onclick="onClickModal()">Olvid&eacute; mi contrase&ntilde;a</a></p>
			</div>
			<div class="footer-line modal-footer">
				<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
				<button type="button" class="btn modal-btns" id="descargaBoton" onclick="download($(this))" data-u="0" data-t="0" data-f="0">Descargar</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="/js/docs.js?l=<?= LOADED_VERSION?>"></script>
<?php
include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/forgotPassword.php';
}
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
?>

