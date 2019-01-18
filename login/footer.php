<?php 
	//footer only for the login page
?>
		<div class="login-please">
			<p class="title">Crea tu perfil en AVIcars con:</p>
			<div class="login-hiding" onclick="hideBanner($(this))" title="ver menos">&times;</div>
			<div class="login-buttons otherlogin">
				<ul>
	                <li>
	                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fb_infotraffic.png" id="facebookBtnLogin">
	                    <div id="fbLink"  class="fb-login-button facebook-btn" data-max-rows="1" data-size="large" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" login_text="Facebook" scope="public_profile,email" onlogin="checkLoginState();" href="javascript:void(0);"></div>
	                </li>
	                 <li>
	                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_fotraffic.png" data-toggle="modal" data-target="#logeate">
	                </li>
	                <li>
	                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_google_infotraffic.png">
	                    <div class=" btn g-signin2" id="loginG" data-onsuccess="onSignIn"></div>
	                    
	                </li>
	                <li>
	                    <img src="/img/webpageAVI/Movil_infotraffic/LogIn_Movil_infotraffic/LogIn_Movil_boton_entracon_twitter_infotraffic.png" id="twitterBtnLogin" onclick='window.open("/php/login/loginTwitter.php?u="+encodeURIComponent(location.pathname+location.search),"_self");'>
	                </li>
	            </ul>
	        </div>
	        <p class="subtitle">Al ingresar usted acepta los <a href="/Terminos_y_condiciones_AVIcars.pdf" target="_blank">t&eacute;rminos y condiciones</a>.</p>
		</div>
		<?php
		
		if(!$twitter){
		?>
		<script type="text/javascript" src="/js/login.js?l=<?= LOADED_VERSION?>"></script>
		<?php }
		else{ ?>
		<script type="text/javascript" src="/js/loginTwitter.js?l=<?= LOADED_VERSION?>"></script>
		<?php }
		?>
		<script type="text/javascript" src="/js/logoutsearch.js?l=<?= LOADED_VERSION?>"></script>
   	</div>  

</body>
</html>