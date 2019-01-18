<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-08 10:32:27
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2018-12-12 15:40:29
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
include ($_SERVER['DOCUMENT_ROOT']).'/login/header.php';
?>
<div class="container-fluid content" >
    <img src="/img/logo_horizontal.png" alt="" class="login-logo visible-xs hidden-sm hidden-md hidden-lg">
    <div class="content-login buzon login-font">
    	<h2 class="login-title">&iexcl;Haz que tu negocio crezca anunci&aacute;ndote en AVIcars!</h2>
    	<p class="buzon-subtitle">D&eacute;janos tus datos para contactarte</p>
    	<form onsubmit="return false;" class="form-size" id="buzonForm">
	    	<div class="form-group col-xs-12 col-sm-6">
	    		<label class="text-left">Correo</label>
	    		<input class="form-control form-style" placeholder="ejemplo@dominio.com" id="mail" name="email1"></input>
	    	</div>
	    	<div class="form-group col-xs-12 col-sm-6">
	    		<label class="text-left">Tel&eacute;fono</label>
	    		<input class="form-control form-style" placeholder="10 d&iacute;gitos" maxlength="10" id="telefono" name="phone_mobile"></input>
	    	</div>
	    	<div class="form-group col-xs-12 col-sm-6">
	    		<label class="text-left">Empresa</label>
	    		<input class="form-control form-style" placeholder="Nombre de la empresa" id="department" name="department"></input>
	    	</div>
	    	<div class="form-group col-xs-12 col-sm-6">
	    		<label class="text-left">Sitio Web</label>
	    		<input class="form-control form-style" placeholder="(Opcional)" id="website" name="website"></input>
	    	</div>
	    	<div class="form-group col-xs-12 col-sm-6">
	    		<label class="text-left">Nombre</label>
	    		<input class="form-control form-style" placeholder="(Opcional)" id="nombre" name="first_name"></input>
	    	</div>
	    	<div class="form-group col-xs-12 col-sm-6">
	    		<label class="text-left">Apellido</label>
	    		<input class="form-control form-style" placeholder="(Opcional)" id="apellido" name="last_name"></input>
	    	</div>
	    	<div class="form-group">
	    		<label class="text-left">Plat&iacute;canos un poco de tu negocio:</label>
	    		<textarea class="buzon-textarea form-control form-style" placeholder="140 Caracteres" maxlength="140" id="comentario" name="description"></textarea>
	    	</div>
	    	<div class="captcha-margin" >                   
                <div id="registerCaptcha" class="captcha captcha-login"></div>
            </div> 
            <hr>
            <div class="text-center">
	            <button id="regis" type="submit" class="btn btn-default login-btn" onclick="sendAdSolicitude()"> ENVIAR SOLICITUD </button>    
	        </div>
	    </form>
    </div>

</div>
<script type="text/javascript" src="/js/buzon.js?l=<?= LOADED_VERSION?>"></script>
<?php
include ($_SERVER['DOCUMENT_ROOT']).'/login/footer.php';
?>