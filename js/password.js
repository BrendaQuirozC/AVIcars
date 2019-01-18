function recuperar(){

	data = $("#formResetPwd").serialize();
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);

    if($("#getPwd").val()===""){
        $(".form-group").removeClass('has-error');
        $("#pwd").addClass('has-error');
        $("#getPwd").focus();
        return false;
    }
    if(!emailRegex.test($("#getPwd").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Correo elctrónico no válido.',
                    type: 'error'

                });
        $("#mail").closest(".form-group").addClass("has-error");
        $("#getPwd").focus();
        return false;
    }
    var response=grecaptcha.getResponse();
    //console.log(grecaptcha);
    if(!getPwd)
    {   
        new PNotify({
            title: 'AVI cars:',
            text: '¡Parece que eres un robot!',
            type: 'error'

        });
        return false;
    }
    $.ajax({
    	type: 'POST',
        url: '/php/login/updatePassword.php',
        data: data,
        success: function(resp) {
        	if (resp=="0") {
        		new PNotify({
	                title: 'El correo no existe.',
	                type: 'error'
	            });
        	}
            else{
            	new PNotify({
	                title: 'Se ha enviado un correo con las instrucciones a seguir.',
	                type: 'info'
	            });
                setTimeout(function(){location.reload()},2000);
            }
        }
    });
}

function recuperarPwInPage(){
    data = $("#formResetPwd").serialize();
    var emailRegex = new RegExp(/^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i);
    var response=grecaptcha.getResponse(widgetId2);
    if(!emailRegex.test($("#getPwd").val()))
    {
        $next=false;
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Correo elctrónico no válido.',
                    type: 'error'

                });
        $("#mail").closest(".form-group").addClass("has-error");
        $("#getPwd").focus();
        return false;
    }
     if(!response)
    {
        $(".g-recaptcha").children("div").addClass("error-captcha");
         new PNotify({
                    title: 'AVI cars:',
                    text: '¡Parece que eres un robot!',
                    type: 'error'

                });
        return false;                                    
    }
    else
    {
        $(".g-recaptcha").children("div").removeClass("error-captcha");
    }

    $.ajax({
        type: 'POST',
        url: '/php/login/updatePassword.php',
        data: data,
        success: function(resp) {
            if (resp=="0") {
                new PNotify({
                    title: 'El correo no existe.',
                    type: 'error'
                });
            }
            else{
                new PNotify({
                    title: 'Se ha enviado un correo con las instrucciones a seguir.',
                    type: 'info'
                });
                setTimeout(function(){location.reload()},2000);
            }
        }
    });
}

function cambiar(e){
	var token = e.data("token");
	data = $("form").serialize() + "&token="+ token;
	if($("#newPassword").val().length < 8){
        $(".form-group").removeClass('has-error');
        new PNotify({
                    title: 'AVI cars:',
                    text: 'La contraseña debe tener un mínimo de 8 carácteres.',
                    type: 'error'

                });
        $("#check_new_pwd").addClass('has-error');
        $("#newPassword").focus();
        return false;
    }
    if($("#reNewPassword").val()==="" ||  $("#reNewPassword").val()!==$("#newPassword").val())
    {
        $(".form-group").removeClass('has-error');
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Las contraseñas no coinciden.',
                    type: 'error'

                });
        $("#recheck_new_pwd").addClass('has-error');
        $("#reNewPassword").focus();
        return false;
    }

    $.ajax({
    	type: 'POST',
        url: '/php/login/changePassword.php',
        data: data,
        success: function(resp) {
        	if (resp=="1") {
        		new PNotify({
                    title: 'AVI cars:',
                    text: '¡Tu contraseña se ha cambiado!',
                    type: 'success'
                });
                setTimeout(function(){location.replace("/")},2000);
            }
            else
            {
            	new PNotify({
                    title: 'AVI cars:',
                    text: 'Tu tiempo de espera ha expirado.',
                    type: 'error'

                });
            }
        }
    });

}

function modificar(){
    data = $("form").serialize();

    if($("#newPassword").val().length < 8){
        $(".form-group").removeClass('has-error');
        new PNotify({
                    title: 'AVI cars:',
                    text: 'La contraseña debe tener un mínimo de 8 carácteres.',
                    type: 'error'

                });
        $("#check_new_pwd").addClass('has-error');
        $("#newPassword").focus();
        return false;
    }
    if($("#reNewPassword").val()==="" ||  $("#reNewPassword").val()!==$("#newPassword").val())
    {
        $(".form-group").removeClass('has-error');
        new PNotify({
                    title: 'AVI cars:',
                    text: 'Las contraseñas no coinciden.',
                    type: 'error'

                });
        $("#recheck_new_pwd").addClass('has-error');
        $("#reNewPassword").focus();
        return false;
    }

    $.ajax({
        type: 'POST',
        url: '/php/login/changePassword.php',
        data: data,
        success: function(resp) {
            if (resp=="1") {
                new PNotify({
                    title: 'AVI cars:',
                    text: '¡Tu contraseña se ha cambiado!',
                    type: 'success'
                });
                setTimeout(function(){location.replace("/perfil/edit/cuenta/")},2000);
            }
            else
            {
                new PNotify({
                    title: 'AVI cars:',
                    text:  resp,
                    type: 'error'

                });
            }

        }
    });
}

function onClickModal(){
    $('.modal').modal('hide');
    $('#pwdModal').modal('show')
}