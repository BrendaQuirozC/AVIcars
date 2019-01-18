<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-08-08 10:32:27
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-10-25 10:59:04
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
session_start();
include ($_SERVER['DOCUMENT_ROOT']).'/login/header.php';
?>
<div class="container-fluid login-container" >
    <img src="/img/logo_horizontal.png" alt="" class="login-logo visible-xs hidden-sm hidden-md hidden-lg">
    <div class="content-login buzon login-font">
    	<h2 class="login-title">Buz&oacute;n de Sugerencias</h2>
    	<p class="buzon-subtitle">&iexcl;Ay&uacute;danos a ser mejores! <br> Tus comentarios son importantes para nosotros.</p>
    	<form onsubmit="return false;" class="form-size" id="buzonForm">
	    	<div class="form-group">
	    		<label class="text-left">Correo</label>
	    		<input class="form-control form-style" placeholder="ejemplo@dominio.com" id="mailBuzon" name="email1"></input>
	    	</div>
	    	<div class="form-group">
	    		<label class="text-left">D&eacute;janos tus comentarios:</label>
	    		<textarea class="buzon-textarea form-control form-style" placeholder="140 Caracteres" maxlength="140" id="commentBuzon" name="description"></textarea>
	    	</div>
	    	<div class="captcha-margin" >                  
                <div id="registerCaptcha" class="captcha"></div>
            </div> 
            <hr>
            <div class="text-center">
            	<input id="campaign_id" type="hidden" name="campaign_id" value="5f0930bc-1085-bcb0-2993-5b6ae00ad883" />
            	<input id="assigned_user_id" type="hidden" name="assigned_user_id" value="4c7b09f6-0631-b860-cd67-58d3c9baee05" />
	            <button id="regis" type="submit" class="btn btn-default login-btn" onclick="sendComment()"> ENVIAR COMENTARIO </button>    
	        </div>
	    </form>
    </div>

</div>
<script type="text/javascript" src="/js/buzon.js?l=<?= LOADED_VERSION?>"></script>
<?php
include ($_SERVER['DOCUMENT_ROOT']).'/login/footer.php';
?>