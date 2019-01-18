<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-29 16:36:45
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-11-29 16:26:39
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])){
	header("Location: /");
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Archivo/archivo.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/auto/Anuncio.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$Garage = new Garage;
$anuncio=new Anuncio;
$coder=new Coder;
$detalles = array();
if(isset($_GET["cuenta"]))
{
	$coder->decode($_GET["cuenta"]);
	$cuentaCoded=$_GET["cuenta"];
	$_GET["cuenta"]=$coder->toEncode;
	$cuentaDecoded = $_GET["cuenta"];
	$cuenta = $_SESSION["iduser"];
	$Usuario = new Usuario;
    $nCuenta= $Usuario->getCuenta($cuenta);
    $nombreCuenta= $Usuario->getGarage();
    $agrega = $Usuario -> agregando($nCuenta, $cuenta);
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
	$detalles = $Garage -> getUserdetail($cuenta);
	$privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));
	if(!empty($detalles)){
		$metasShare=array(
			"og"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
			    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
			    "site_name" => "AVI cars",
			    "type" => "website"
			),
			"tw"	=>	array(
				"title" => "AVI cars by Infotraffic | Perfil",
			    "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
			    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
			    "image:alt" => "AVI cars",
			    "card" => "summary_large_image"
			)
		);
	}
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
	if($_SESSION["iduser"]!=$cuentaDecoded)
	{
		?>
		<div class="row" style="margin: 80px 0px 25px 0px;">
			<h3 class="text-center">
				Esta p&aacute;gina no est&aacute; disponible
			</h3>
		</div>
		<?php
	}
	elseif(!empty($detalles))
	{
		$Version = new Version;
		$instancia = array();
		$garages = $Garage -> account($cuenta);
		$active="docs";
		$archivo=new Archivo;
		$files=$archivo->getFilesByObject($cuenta,3);
		$ads=$anuncio->getAnunciosByUser($cuenta);
		$fileTypes=$archivo->getFileTypesByTypeObject(3);
		$Like = new Like;
		include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
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
							<th class="hidden-xs hidden-sm visible-md visible-lg">Garage</th>
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
							<td class="hidden-xs hidden-sm visible-md visible-lg">
								<?= $ad["garage"]?>
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
										<img onclick="window.location.href='/perfil/autos/detalles/editar/?cuenta=<?= $cuentaCoded ?>&auto=<?= $autoCoded?>'" src="/img/webpageAVI/Movil_infotraffic/MyGarages_Movil_infotraffic/MyGarages_Movil_boton_editarNombreGarage_infotraffic.png" title="Editar Anuncio">
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
											<li onclick="doShare($(this),5)" data-p="<?= $linkAnuncio ?>">En AVI cars</li>
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
								</ul>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
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
						<div class="form-group col-xs-12 selectdiv">
							<label class="control-label">Elige un tipo de Documento</label>
							<select class="form-control form-style" name="type" id="fileToUploadName">
								<option value="0">Selecciona un tipo de Documento</option>
							<?php foreach ($fileTypes as $t => $tipo) { ?>
								<option value="<?= $tipo["id"] ?>"><?= $tipo["nombre"] ?></option>
							<?php } ?>
							</select>
						</div>
						<div class="form-group col-xs-12">
							<label class="control-label">Nombre de tu documento (Opcional)</label>
							<input type="text" name="nombre" class="form-control form-style">
						</div>
						<div class="form-group">
							<label class="control-label">Sube tu documento</label>
							<input type="file" name="file" id="filesToUploadCar" accept=".xls,.xlsx,.pdf,.png,.jpg,.jpeg,.doc,.docx,.ppt,.pptx,.zip,.rar,.txt">
							<input type="hidden" name="object" value="<?= $cuentaCoded?>">
						</div>
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
					<div id="questionDownloadText" class="question-icon-text">Si inici&oacute; sesi&oacute;n con Google o Facebook y nunca ha cambiado su contraseña, por favor registre una nueva contraseña con "olvidé mi contraseña"</div>
					<p class=" contra"><a class="a-contra" onclick="onClickModal()">Olvid&eacute; mi contrase&ntilde;a</a></p>
				</div>
				<div class="footer-line modal-footer">
					<button type="button" class="btn modal-btns" data-dismiss="modal" >Cancelar</button> |
					<button type="button" class="btn modal-btns" id="descargaBoton" onclick="download($(this))" data-u="0" data-t="0" data-f="0">Descargar</button>
				</div>
			</div>
		</div>
	</div>
	<div id="pwdModal" class="modal fade" role="dialog">
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="title-header modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    Olvid&eacute; mi contrase&ntilde;a
                </div>  
                <div class="modal-body">
                    <form action="forgotPassword_submit" id="formResetPwd">
                        <p>Por favor ingresa tu correo que usas en el sitio:</p>
                        <div id="pwd" class="form-group"> 
                            <input type="email" class="form-control form-style" name="getPwd" id="getPwd" placeholder="E-mail">
                        </div>
                        <div class="captcha-margin"  >
                            <div id="captchaPWD" class="captcha"></div>
                        </div> 
                    </form>
                    <hr>
                        <button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button> |
                        <button type="button" class="btn modal-btns" onclick="recuperarPwInPage()">Recuperar contrase&ntilde;a</button>
                </div>  
            </div>
        </div>  
    </div>
	<script type="text/javascript" src="/js/docs.js?l=<?= LOADED_VERSION?>">
	</script>
	<script type="text/javascript" src="/js/password.js?l=<?= LOADED_VERSION?>">
	</script>
<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/forgotPassword.php';
	}
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
}
?>