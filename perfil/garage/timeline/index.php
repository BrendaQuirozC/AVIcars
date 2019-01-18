<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-05-18 13:22:33
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2019-01-10 17:28:51
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
if(empty($_GET)){
	header("Location: /");
}
elseif(!isset($_GET["cuenta"])||!isset($_GET["garage"])){
	header("Location: /");
}
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Follow/Seguidor.php';
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/catalogoAutos/version.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Publicacion/publicacion.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/share.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
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
				$active="timeline";
				$Like = new Like;
				$publicacion=new Publicacion;
				
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

if($show){
	$metasShare=array(
		"og"	=>	array(
			"title" => "AVI cars by Infotraffic | Garage",
		    "description" => "Garage ".$garage["nameAccount"].(($infoPerfil["privacidad"]!=3) ? " de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"] : ""),
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
		    "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST']."/perfil/garage/timeline/?cuenta=".$cuentaEncoded."&garage=".$garageEncoded,
		    "site_name" => "AVI cars",
		    "type" => "website"
		),
		"tw"	=>	array(
			"title" => "AVI cars by Infotraffic | Garage",
		    "description" => "Garage ".$garage["nameAccount"].(($infoPerfil["privacidad"]!=3) ? " de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"] : ""),
		    "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($extrasGarage)  && $extrasGarage["avatar"]!="") ? $extrasGarage["avatar"] : "/img/PORTADAgarage.jpg"),
		    "image:alt" => "AVI cars",
		    "card" => "summary_large_image"
		)
	);
}
if(!isset($_SESSION["iduser"]) && $garage["privacidad"]!=3){
	$_SESSION["iduser"]=0;
	$coder->encode(0);
	$_SESSION["usertkn"]=$coder->encoded;
	$_SESSION["loads"]=1;
}
$colaborador=$Garage->getAUserAccount($_SESSION["iduser"], $_GET["garage"],1);
include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/header.php';
if(isset($garage)){
	if($garage["user"]!=$_GET["cuenta"]){
		$show=false;
	}
	if(!$owner && ((isset($garage["privacidad"])) ? $garage["privacidad"] : 1)==3  && !$following && !$Seguidor->acepted || $blocked){ 
		$show=false;
	}
}
if($show)
{ 
	include_once $_SERVER["DOCUMENT_ROOT"]."/php/Garage/headerGarage.php";
	$garageContain = $Garage ->accountInstancia($_GET["garage"]);
	?>
	<div class="content" id="posts">
		<h5>Timeline de: <?= $garage["nameAccount"]?></h5>
	</div>
	<?php
	if($_GET["garage"]==96){ 
		$segurosModal=array(
			"nombre" => "",
			"apellido" => "",
			"mail" => "",
			"edad" => "",
			"cp" => "",
			"telefono" => ""
		);
		if($_SESSION["iduser"]>0){
			$detalle=$Usuario->getUserdetail($_SESSION["iduser"]);
			$nac=date_create($detalle["fechaNacimiento"]);
			$today=date_create("now");
			$diff=date_diff($nac,$today);
			$edad=$diff->format("%y");
			$segurosModal=array(
				"mail" => $detalle["o_avi_user_email"],
				"edad" => $edad,
				"cp" => $detalle["a_avi_useraddress_zip_code"],
				"nombre" => $detalle["o_avi_userdetail_name"],
				"apellido" => $detalle["o_avi_userdetail_last_name"],
				"telefono" => $detalle["phone"]
			);
		}
		?>
		<div class="modal fade" id="modalSellSeguros">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body">
						<img class="promo" src="/img/promomodal_400.png">
						<img class="promo-mv" src="/img/promomodal_400.png">
						<div class="promo-modal">
							<div class="promo-modal-title">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								Cotiza tu seguro con nosotros
							</div>
							<div class="gracias hidden">
								<p>Muchas gracias por cotizar tu seguro con nosotros.</p>
								<p>En breve uno de nuestros asesores se pondr&aacute; en contacto contigo.</p>
								<button id="buttonFinishModalSeguros">Cotizar nuevamente</button>
							</div>
							<ul id="steps-all" class="cotizarModal">
								<li class="active" data-name="auto" data-id="0">
									<img src="/img/icons/modalSegurosAuto.png">
									Auto
									<div class="top-first"></div>
									<div class="bottom-first"></div>
								</li>
								<li data-name="contacto" data-id="1">
									<img src="/img/icons/modalSegurosContacto.png">
									<div class="top-second"></div>
									<div class="bottom-second"></div>
									Cont&aacute;cto
								</li>
							</ul>
							<div class="progress cotizarModal">
								<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 50%;"></div>
							</div>
							<form id="formpromomodal" class="cotizarModal">
								<div class="step active" data-name="auto" data-id="0">
								<?php if($_SESSION["iduser"]>0){ ?>
									<div class="form-group selectdiv col-xs-12">
										<select class="form-control form-style" id="autoSegurosModal" name="autos">
											<option value="0">Selecciona un auto</option>
										<?php 
										$allAutosForSeguros=$Garage->getCarsEditableByUser($_SESSION["iduser"],true,false);
										foreach ($allAutosForSeguros as $a => $auto) { 
											$selected="";
											$coder->encode($auto["i_avi_account_car_id"]);
											$autoCodedSelect=$coder->encoded;
											if($currAuto==$auto["i_avi_account_car_id"]){
												$selected="selected";
											}
											?>
											<option <?= $selected ?> value="<?= $autoCodedSelect?>"><?= $auto["alias"]?>. Garage: <?= $auto["garageName"]?><?= (!$auto["propio"]) ? " de ".$auto["garageOwner"] : ""?></option>
										<?php } ?>
											<option value="-1">Otro Auto</option>
										</select>
									</div>
								<?php } ?>
									<div class="form-group selectdiv col-xs-12 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style" id="marcaSegurosTimeline" name="marca" onchange="changeMarcaSegurosTimeline($(this))">
											<option class="visible" value="0">MARCA</option>
											<?php 
											foreach ($marcas as $m => $marca) 
											{ 
												if($marca !='CBO' && $marca !='FORWARD 800' && $marca !='GIANT' && $marca !='HINO')
												{ ?>
													<option data-brand="<?= $marca?>" class="visible" value="<?= $m?>"><?= $marca?></option>
													<?php 
												}
											} ?>
											<option class="visible" value="-1">Otra Marca</option>
										</select>
										<div class="otraMarca hidden">
											<input type='text' class='form-control form-style' placeholder="Especifica" id='otraMarcaInputSegurosTimeline' name='otraMarcaInput' value='' placeholder="Otra Marca"/>
										</div>
									</div>
									<div class="form-group selectdiv col-xs-6 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style submarca" id="modeloSegurosTimeline" name="submarca" onchange="changeSubmarcaSegurosTimeline($(this))">
											<option value="0">MODELO</option>
										<?php 
										foreach ($submarcas as $sm => $submarca) 
										{  ?>
											<option data-marca="<?= $submarca["marca"]?>" data-submarca="<?= $submarca["submarca"]?>" value="<?= $submarca["id"]?>"><?= $submarca["submarca"]?></option>
										<?php 
										} ?>
											<option class="visible" value="-1">Otro Modelo</option>
										</select>
										<div class='otroModelo hidden'>
											<input type='text' class='form-control form-style' placeholder="Especifica" id='otroModeloInputSegurosTimeline' name='otroModeloInput' value='' placeholder="Otro Modelo"/>
										</div>
									</div>
									<div class="form-group selectdiv col-xs-6 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style" id="anoSegurosTimeline" name="modelo" onchange="changeAnoSegurosTimeline($(this))">
											<option value="0">A&Ntilde;O</option>
											<?php 
											foreach ($modelos as $md => $modelo) 
											{ ?>
												<option data-modelo="<?= $modelo["modelo"]?>" value="<?= $modelo["id"]?>"> <?= $modelo["modelo"]?> </option>
											<?php 
											} ?>
											<option class="visible" value="-1">Otro a&ntilde;o</option>								
										</select>
										<div id='otheryearSegurosTimeline' class='otroAno hidden'>
											<input type='text' class='form-control form-style' placeholder="Especifica" id='otroAnoInputSegurosTimeline' name='otroAnoInput'  value='' placeholder="Otro A&ntilde;o"/>
										</div>
									</div>
									<div class="form-group selectdiv col-xs-12 <?= ($_SESSION["iduser"]>0) ? "hidden" : ""?> carChar">
										<select class="form-control form-style" id="versionSegurosTimeline" name="subnombres" onchange="changeVersionSegurosTimeline($(this))">
											<option value="0">VERSI&Oacute;N</option>
										<?php 
										foreach ($versiones as $vr => $version) 
										{ 
										?>
											<option data-modelo="<?= $version["modelo"] ?>" value="<?= $version["id"]?>" > <?= $version["version"]?> <?= $version["subnombre"]?> </option>
										<?php 
										} ?>
											<option class="visible" value="-1">Otra Versi&oacute;n</option>		
										</select>
										<div id='otherverSegurosTimeline' class='otraVersion hidden'>
											<input type='text' maxlength="50" class='form-control form-style' placeholder="Especifica" id='otroVersionInputSegurosTimeline' name='otroVersionInput' value=''placeholder="Otra Versi&oacute;n"/>
										</div>
									</div>
								</div>
								<div class="step" data-name="contacto" data-id="1">
									<?php if($_SESSION["iduser"]==0){ ?>
									<span class="continue-with">Continuar con...</span>
									<ul class="icons-login-continue">
										<li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fb_infotraffic.png" id="facebookBtnLogin">
				                            <div id="fbLink"  class="fb-login-button facebook-btn" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" login_text="Facebook" scope="public_profile,email" onlogin="checkLoginStateModal();" href="javascript:void(0);"></div>
				                        </li>
				                         <li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fotraffic.png" data-target="#inicieSesionSeguros" data-toggle="modal">
				                        </li>
				                        <li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_google_infotraffic.png">
				                            <div class=" btn g-signin2" id="loginG" data-onsuccess="onSignInModal"></div>
				                            
				                        </li>
				                        <li>
				                            <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_twitter_infotraffic.png" id="twitterBtnLogin" onclick='signInTwitter();'>
				                        </li>
	            
									</ul>
									<?php } ?>	
									<div class="form-group col-xs-6">
										<input type="text" name="nombre" class="form-control form-style" placeholder="Nombre(s)" value="<?= $segurosModal["nombre"]?>">
									</div>
									<div class="form-group col-xs-6">
										<input type="text" name="apellido" class="form-control form-style" placeholder="Apellido(s)" value="<?= $segurosModal["apellido"]?>">
									</div>
									<div class="form-group col-xs-12">
										<input type="text" name="mail" class="form-control form-style" placeholder="Correo Electr&oacute;nico" value="<?= $segurosModal["mail"]?>">
									</div>
									<div class="form-group col-xs-6">
										<input type="text" name="edad" class="form-control form-style" placeholder="Edad (A&ntilde;os)" maxlength="2" value="<?= $segurosModal["edad"]?>">
									</div>
									<div class="form-group col-xs-6">
										<input type="text" name="cp" class="form-control form-style" placeholder="C&oacute;digo Postal" maxlength="5" value="<?= $segurosModal["cp"]?>">
									</div>
									<div class="form-group col-xs-12">
										<input type="text" name="telefono" maxlength="10" class="form-control form-style" placeholder="Tel&eacute;fono (8 a 10 d&iacute;gitos)" value="<?= $segurosModal["telefono"]?>">
									</div>
									<?php if($_SESSION["iduser"]==0){ ?>
									<div class="checkbox col-xs-12 cretaeaccountmodal">
										<label class="checkbox"><input type="checkbox" name="login" checked="true">Deseo crear una cuenta en AVIcars</label>
									</div>
									<?php } else {
										$allGaragesMS = $Garage->account($_SESSION["iduser"]);
										$garageColaboradorMS = $Garage->colaboratingGarage($_SESSION["iduser"]); ?>
									<div class="form-group selectdiv col-xs-12 hidden" id="saveCarInGarageModalSeguros">
										<label class="control-label">En qu&eacute; garage quieres guardar el auto?</label>
										<select class="form-control form-style" id="garageNewCarSeguros" name="garage">
											<?php 
											foreach ($allGaragesMS as $keyAllGarages => $someOfAllGarage) 
											{
												$coder->encode($someOfAllGarage["idAccount"]);
												$codeGarage=$coder->encoded;
												$selected="";
												if($actualGarage==$someOfAllGarage["idAccount"]){
													$selected="selected";
												}
												?>
												<option class='visible' value='<?= $codeGarage?>' <?= $selected?>><?=$someOfAllGarage["nameAccount"]?></option>
												<?php
											}
											foreach ($garageColaboradorMS as $keyColaborating => $colaborating) {
												if($colaborating["nivel"]<3){
													$coder->encode($colaborating["idAccount"]);
													$codeGarage=$coder->encoded;
													$selected="";
													if($actualGarage==$colaborating["idAccount"]){
														$selected="selected";
													}
													?>
													<option class='visible' value='<?= $codeGarage?>' <?= $selected?>><?=$colaborating["nameGarage"]?> de <?=$colaborating["ownerName"]?> <?=$colaborating["ownerLastName"]?></option>
													<?php
												}
											}
											?>
											<option class="visible" value="0">Nuevo Garage</option>								
										</select>
									</div>
									<?php } ?>
								</div>
							</form>
							<div class="clearfix cotizarModal"></div>
							<hr class="first cotizarModal">
							<hr class="cotizarModal">
							<p class="legend cotizarModal">Los campos marcados con (*) son obligatorios</p>
							<p class="advise cotizarModal"><a href="/Terminos_y_condiciones_AVIcars.pdf">AVISO DE PRIVACIDAD</a></p>
							<div class="promo-modal-footer cotizarModal">
								<ul>
									<li class="prev">
										<div class="top-first"></div>
										<div class="bottom-first"></div>
										<button type="button" clss="prev">Anterior</button>
										<div class="top-second"></div>
										<div class="bottom-second"></div>
									</li>
									<li >
										<div class="top-first"></div>
										<div class="bottom-first"></div>
										<button type="button" class="sig">Siguiente</button>
										<button type="button" class="last hidden">Enviar</button>
										<div class="top-second"></div>
										<div class="bottom-second"></div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class='modal fade' id='modalSuccessModalSeguros' role='dialog'> 
		    <div class="modal-dialog">
		        <div class="modal-content modal-login">
		            <div class="nopad-login title-header modal-header">
		                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h2>Tu Seguro con ApoyoVial</h2>
		            </div>
		            <div class="modal-body-login modal-body">
		                <p id="messageSuccessModalSeguros"></p>
		            </div>
		            <div class="footer-line modal-footer">
		                <button id="iniciarModalSeguros" type="submit" class="btn modal-btns-login" data-dismiss="modal" aria-label="Close"> 
		                    Cerrar
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
		<div class='modal fade' id='inicieSesionSeguros' role='dialog'> 
		    <div class="modal-dialog">
		        <div class="modal-content modal-login">
		            <div class="nopad-login title-header modal-header">
		                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h2>&iexcl;INICIA SESI&Oacute;N!</h2>
		            </div>
		            <div class="modal-body-login modal-body">
		                <form onsubmit="return false;" class="form-size">
		                    <div id="in_usernameModal" class="form-group username-mrg">
		                        <input type="text" class="form-control form-style" name="logInUsernameModal" id="logInUsernameModal" onkeypress="iniciarpressModalSeguros(event)" placeholder="Usuario / Email" maxlength="50">
		                    </div>
		                    <div id="in_pwdModal" class="form-group pwd-mrg">
		                        <input type="password" class="form-control form-style" name="logInPasswordModal" id="logInPasswordModal" onkeypress="iniciarpressModalSeguros(event)" placeholder="Contrase&ntilde;a" maxlength="60">
		                        <p class="contra"><a class="a-contra" href="#" data-toggle="modal" data-target="#pwdModal">Olvid&eacute; mi contrase&ntilde;a</a></p>
		                    </div>
		                </form> 
		            </div>
		            <div class="footer-line modal-footer">
		                <button id="iniciarModalSeguros" type="submit" class="btn modal-btns-login" onclick='conectarModalSeguros()' > 
		                    Iniciar Sesi&oacute;n 
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
		<div class='modal fade' id='setPasswordSeguros' role='dialog'> 
		    <div class="modal-dialog">
		        <div class="modal-content modal-login">
		            <div class="nopad-login title-header modal-header">
		                <button type="button" class="close-login close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h2>Indica tu nueva contrase&ntilde;a</h2>
		            </div>
		            <div class="modal-body-login modal-body">
		                <form onsubmit="return false;" class="form-size" id="formSendPromoSegurosPwd">
		                    <div id="in_usernameModal" class="form-group username-mrg">
		                        <input type="password" class="form-control form-style" name="firstPassword" id="firstPassword" onkeypress="setPasswordSeguros(event)" placeholder="Contrase&ntilde;a" maxlength="60">
		                    </div>
		                    <div id="in_pwdModal" class="form-group pwd-mrg">
		                        <input type="password" class="form-control form-style" name="secondPassword" id="secondPassword" onkeypress="setPasswordSeguros(event)" placeholder="Repite la Contrase&ntilde;a" maxlength="60">
		                    </div>
		                </form> 
		            </div>
		            <div class="footer-line modal-footer">
		                <button id="sendModalPromoSeguros" type="submit" class="btn modal-btns-login" onclick='validatePasswords()'> 
		                    Enviar
		                </button>
		            </div>
		        </div>
		    </div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#modalSellSeguros").modal("show");
			})

		</script>
		<?php
		if(isset($_SESSION["carModal"])){ ?>
			<script type="text/javascript">
				var carModalSeguros=<?= json_encode($_SESSION["carModal"])?>;
				var mailModalSeguros="erikfer94@gmail.com";
				var nameModalSeguros="Erik";
				
			</script>
		<?php 
			$twitter=false;
		}
		?>
		<script type="text/javascript" src="/js/ventaSeguros.js"></script>
	<?php
	include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/forgotPassword.php';
	 } ?>
	<script type="text/javascript">
		var lastPost=0;
		var search=true;
		var s="g";
		var u='<?= $garageEncoded?>';
	</script>
	<script type="text/javascript" src="/js/timeline.js?l=<?= LOADED_VERSION?>"></script>
<?php }
else 
{
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
