<style type="text/css">
	.modal-header-background{
	  background: #ecb214;
	}
	.modify-btn {
	    color: #000;
	    background-color: #ecb216;
	    border-radius: 7px;
	    border: 1px solid #6b5e5e;
	}

</style>
<div id="NewPwdModal" class="modal fade" role="dialog">
	<div class="modal-dialog text-center">
		<div class="modal-content">
			<div class="title-header modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				Modifica tu contrase&ntilde;a
			</div>	
			<div class="modal-body">
				<form action="forgotPassword_submit">
					<div class="form-group">
	                    <div id="check_actual_pwd">
	                        <input type="password" class="form-control form-style " name="verifyPwd" id="verifyPwd" onkeypress="iniciarpress(event)" placeholder="Contrase&ntilde;a actual">
	                        <div id="questionDownload" class="glyphicon glyphicon-question-sign question-icon"></div>
							<div id="questionDownloadText" class="question-icon-text">Si inci&oacute; sesi&oacute;n con Google o Facebook y nunca ha cambiado su contraseña, por favor registre una nueva contraseña con "olvidé mi contraseña"</div>
	                        <p class=" contra"><a id="forgotPwd" class="a-contra" onclick="onClickModal()">Olvid&eacute; mi contrase&ntilde;a</a></p>                   
	                    </div>
	                    <div id="check_new_pwd">
	                        <input type="password" class="form-control form-style" name="newPassword" id="newPassword" placeholder="Nueva contrase&ntilde;a (Mínimo 8 carácteres)" onkeypress="registrarpress(event)">
	                    </div>   
	                    <div id="recheck_new_pwd">
	                        <input type="password" class="form-control form-style" name="reNewPassword" id="reNewPassword" placeholder="Confirma tu nueva contrase&ntilde;a" onkeypress="registrarpress(event)">
	                    </div>                   
	                </div>
				</form>
				<div class="footer-line modal-footer">
					<button class="btn modal-btns" type="button" onclick="modificar()">MODIFICAR CONTRASE&Ntilde;A</button>
				</div>
			</div>	
		</div>
	</div>	
</div>
<script type="text/javascript" src="/js/password.js"></script>
