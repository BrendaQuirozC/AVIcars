<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-24 13:19:28
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:56:15
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
require_once  ($_SERVER['DOCUMENT_ROOT']).'/php/Garage/Garage.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/php/likes/Like.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/country.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/notification/Notification.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/report.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/php/Utilities/coder.php";
$coder = new Coder();
$notification=new Notificacion;
$report=new Report;
$arrayMes=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
if(isset($_SESSION["user"]))
{
    $cuentaCoded=$_SESSION["usertkn"];
    $Usuario = new Usuario;
    $Garage = new Garage;
    $id = $_SESSION["iduser"];
    $telefono = $Usuario->getPhone($id);
    $genero = $Usuario->cGetGender();
    $calle = $Usuario->getStreet($id);
    $cp = $Usuario->getZipCode($id);
    $generoUser = $Usuario->getGenderUser($id);
    $cuenta = $_SESSION["iduser"];
    $detalles = $Usuario -> getUserdetail($cuenta);
    $imgPerfil = $Usuario->getImgPerfil($_SESSION["iduser"]);
    $infoPerfil = $Usuario->getInfoPerfil($_SESSION["iduser"]);
    $userConfigurations=$Usuario->getUserConfiguration($_SESSION["iduser"]);
    $mailsConfiguration=array();
    $mailsConfiguration=json_decode(base64_decode($userConfigurations["mails"]));
    $privacyToChange=json_encode(array("tipo" =>1,"privacy"=>$_SESSION["iduser"]));     
    if(!empty($detalles)){
        $metasShare=array(
            "og"    =>  array(
                "title" => "AVI cars by Infotraffic | Perfil",
                "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
                "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
                "url" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'],
                "site_name" => "AVI cars",
                "type" => "website"
            ),
            "tw"    =>  array(
                "title" => "AVI cars by Infotraffic | Perfil",
                "description" => "Perfil de ".$detalles["o_avi_userdetail_name"]." ".$detalles["o_avi_userdetail_last_name"],
                "image" => (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].((!empty($imgPerfil)  && $imgPerfil["avatar"]!="") ? $imgPerfil["avatar"] : "/img/portada.jpg"),
                "image:alt" => "AVI cars",
                "card" => "summary_large_image"
            )
        );
    }     
    include ($_SERVER['DOCUMENT_ROOT']).'/php/perfil/header.php';
    $active="profile";
    $editing = "yes";
    $owner=true;
    $Like = new Like;
    $country=new Country;
    $phoneCodes=$country->getPhoneCodes();
    $actions=$notification->getMailsConfirmation();
    $blockedUsers=$report->getBlockeds($_SESSION["iduser"]);
    include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/headerProfile.php';
    ?>
    <div class="content">
        <div class="form-send">  
        	<div class="config-menu">
        		<form id="formMails">
	        		<h3>Env&iacute;o de Correos</h3>
	        		<p>Elige qu&eacute; correos de notificaci&oacute;n deseas que te env&iacute;e el sistema.</p>
	        		<ul>
	        			<li>
	        				<label class="checkbox">
	        					<input type="checkbox" name="mailsAll" value="0" id="selectAll">Seleccionar / Deseleccionar todos
	        				</label>
	        			</li>
	        		<?php
	        		foreach ($actions as $a => $action) { ?>
	        			<li>
	        				<label class="checkbox">
	        					<input type="checkbox" <?= (in_array($action["id"], $mailsConfiguration)) ? "checked" : "" ?> name="mails[]" value="<?= $action["id"] ?>"><?= $action["descripcion"] ?>
	        				</label>
	        			</li>
	        		<?php }
	        		?>
	        		</ul>
	        		<button class="btn cuenta-btns center-block" type="button" id="saveMails">Guardar</button>
	        	</form>
        	</div>
        	<div class="config-menu">
                <form id="blockedForm">
            		<h3>Usuarios Bloqueados</h3>
            		<select class="form-control" multiple="" name="blocked[]">
                    <?php 
                    $k=0;
                    foreach ($blockedUsers as $bu => $user) { 
                        $k++;
                        $coder->encode($user["iduser"]); ?>
                        <option value="<?= $coder->encoded ?>"><?= $user["name"]?> (<?= $user["username"]?>)</option>
                    <?php } 
                    if($k==0){ ?>
            			<option disabled>(Vac&iacute;o)</option>
                    <?php } ?>
            		</select>
            		<button type="button" class="btn cuenta-btns center-block" id="toBlock">Quitar de la lista</button>
                </form>
        	</div>
            <div class="infoApoyoVial text-center"> 
                <a class="acercade" href="/perfil/edit/configuracion"> Configuraci&oacute;n </a>
                <a class="acercade" target="_blank" href="/Terminos_y_condiciones_AVIcars.pdf"> T&eacute;rminos y Condiciones </a>
                <a class="acercade" target="_blank" href="https://apoyovial.net/acerca-de/"> Acerca de </a>
                <a class="acercade" target="_blank" href="/Aviso_de_Privacidad_AVIcars.pdf"> Aviso de Privacidad </a>
                <a class="acercade" target="_blank" href="/buzon"> Sugerencias </a>
                <a class="acercade" target="_blank" href="/ayuda"> Ayuda </a>
            </div>
        </div>

    <div class="modal fade" id="modalBlocked" role="dialog">
        <div class="modal-dialog text-center">
            <div class="modal-content">
                <div class="title-header modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    Desbloquear usuarios
                </div>  
                <div class="modal-body">
                    <form action="forgotPassword_submit">
                        <p>¿Seguro que deseas desbloquear a estos usuarios?</p>
                        <p>Una vez desbloqueados, tendr&aacute;n acceso nuevamente a todo tu contenido.</p>
                    </form>
                    <hr>
                    <button type="button" class="btn modal-btns" data-dismiss="modal">cerrar</button> |
                    <button type="button" class="btn modal-btns" id="quitBlocked">Desbloquear</button>
                </div>  
            </div>
        </div>
    </div>
    <div class="modal-verify">
        
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
    <script type="text/javascript" src="/js/password.js?l=<?= LOADED_VERSION?>"></script>
    <script type="text/javascript" src="/js/editCuenta.js?l=<?= LOADED_VERSION?>"></script>
    <script type="text/javascript" src="/js/configuration.js?l=<?= LOADED_VERSION?>"></script>
    <?php
    include ($_SERVER['DOCUMENT_ROOT']) . '/php/login/updateNewPassword.php';
    include ($_SERVER['DOCUMENT_ROOT']) . '/php/perfil/footer.php';
}
else 
{
    ?>
     <script> location.replace("../../"); </script>
    <?php
}
?>
