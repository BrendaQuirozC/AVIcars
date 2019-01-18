<style type="text/css">
	.modal-header-background
	{
	  background: #ecb214;
	}
</style>
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
<script type="text/javascript" src="/js/password.js"></script>
<script type="text/javascript" src="/js/forgotCaptcha.js"></script>